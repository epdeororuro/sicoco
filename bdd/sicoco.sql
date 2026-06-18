-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-06-2026 a las 15:40:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sicoco`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_BAJA_USUARIO` (IN `_IDUSUARIO` INT)   BEGIN
DECLARE OP VARCHAR(100);
IF ((SELECT ACTIVO FROM USUARIOS WHERE IDUSUARIO=_IDUSUARIO)='SI') THEN
BEGIN
 SET OP='1';
 UPDATE USUARIOS SET ACTIVO='NO',
 FECHA_BAJA= current_timestamp()
 WHERE IDUSUARIO=_IDUSUARIO;
END;
ELSE
 SET OP='Error, La cuenta No se Encuentra Activa';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CIERRE_GESTION` ()   BEGIN
    DECLARE OP VARCHAR(100);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;
        SELECT 'Error interno en la BD al ejecutar el Cierre de Gestión.' AS OP;
    END;

    START TRANSACTION;

    -- A) Liberar todos los espacios del catálogo
    UPDATE catalogo SET ESTADO = 'DISPONIBLE' WHERE ESTADO = 'ALQUILADO';

    -- B) Contratos Vigentes que SÍ tienen pagos pendientes -> Pasan a Cuentas por Cobrar (CXC)
    UPDATE arriendos a
    SET a.VIGENTE = 'CXC'
    WHERE a.VIGENTE = 'SI'
      AND EXISTS (SELECT 1 FROM pagos p WHERE p.IDARRIENDO = a.IDARRIENDO AND p.PENDIENTE = 'SI');

    -- C) Contratos Vigentes que NO tienen deudas -> Pasan a Finalizado (FIN)
    UPDATE arriendos a
    SET a.VIGENTE = 'FIN'
    WHERE a.VIGENTE = 'SI'
      AND NOT EXISTS (SELECT 1 FROM pagos p WHERE p.IDARRIENDO = a.IDARRIENDO AND p.PENDIENTE = 'SI');

    COMMIT;
    SELECT '1' AS OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CONFIRMA_CONTRATO` (IN `_IDARRIENDO` INT)   BEGIN
    DECLARE OP VARCHAR(100);
    
    IF (SELECT COUNT(*) FROM DETALLE WHERE IDARRIENDO=_IDARRIENDO) > 0 THEN
    BEGIN
        -- AQUÍ SE ELIMINÓ LA RESTRICCIÓN DE PAGO DE GARANTÍA
        IF (SELECT VIGENTE FROM ARRIENDOS WHERE IDARRIENDO=_IDARRIENDO) = 'PR' THEN
        BEGIN
            DECLARE _IDCATALOGO INTEGER;
            DECLARE FINAL_CURSOR INTEGER DEFAULT 0;
            DECLARE LST_DETALLE CURSOR FOR SELECT IDCATALOGO FROM DETALLE WHERE IDARRIENDO=_IDARRIENDO;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET FINAL_CURSOR = 1;

            OPEN LST_DETALLE;
            bucle: LOOP
                FETCH LST_DETALLE INTO _IDCATALOGO;
                IF FINAL_CURSOR = 1 THEN LEAVE bucle; END IF;
                UPDATE CATALOGO SET ESTADO='ALQUILADO' WHERE IDCATALOGO=_IDCATALOGO;
            END LOOP bucle;
            CLOSE LST_DETALLE;

            UPDATE ARRIENDOS SET VIGENTE='SI' WHERE IDARRIENDO=_IDARRIENDO;
            CALL SP_GENERA_PAGOS(_IDARRIENDO);
            SET OP='1';
        END;
        ELSE
            SET OP='Error, el Contrato ya se encuentra Vigente o hubo un error de estado.';
        END IF;
    END;
    ELSE
        SET OP='Error, el Registro No Cuenta con Detalle de Servicios Alquilados'; 
    END IF;
    SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DEL_AREA` (IN `_IDAREA` INT)   BEGIN
DECLARE OP VARCHAR(100);
IF(SELECT COUNT(*) FROM CATALOGO
   WHERE IDAREA=_IDAREA)=0 THEN
BEGIN
 SET OP='1';
 DELETE FROM AREAUBICACION
  WHERE IDAREA=_IDAREA;
END;
ELSE
 SET OP='Error, No es posible Eliminar el registro, este tiene dependencia';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DEL_CATALOGO` (IN `_IDCATALOGO` INT)   BEGIN
DECLARE OP VARCHAR(100);
IF (SELECT COUNT(*) FROM DETALLE
    WHERE IDCATALOGO=_IDCATALOGO)=0 THEN
BEGIN
 SET OP='1';
 DELETE FROM CATALOGO 
 WHERE IDCATALOGO =_IDCATALOGO;
END;
ELSE
 SET OP='Error, No es posible Eliminar el Registro, este tiene Historial';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DEL_CLIENTE` (IN `_IDCLIENTE` INT)   BEGIN
DECLARE OP VARCHAR(100);
IF (SELECT COUNT(*) FROM ARRIENDOS
    WHERE IDCLIENTE=_IDCLIENTE)=0 THEN
BEGIN
 SET OP='1';
 DELETE FROM CLIENTES 
 WHERE IDCLIENTE = _IDCLIENTE;
END;
ELSE
 SET OP='Error, no es posible eliminar el registro, este tiene historial de contratos';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DEL_CONTRATO` (IN `_IDARRIENDO` INT)   BEGIN
DECLARE OP VARCHAR(100);
IF (SELECT COUNT(*) FROM PAGOS
    WHERE IDARRIENDO=_IDARRIENDO)=0 THEN
BEGIN
 DELETE FROM DETALLE 
 WHERE IDARRIENDO=_IDARRIENDO;
 DELETE FROM ARRIENDOS
 WHERE IDARRIENDO=_IDARRIENDO;
 SET OP='1';
END;
ELSE
 SET OP='Error, El Registro de Contrato cuenta historial de Pagos';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DEL_DETALLE` (IN `_IDDETALLE` INT)   BEGIN
DECLARE OP VARCHAR(100);
DECLARE _IDARRIENDO INT;
SET _IDARRIENDO=(SELECT IDARRIENDO FROM 
                 DETALLE WHERE 
                 IDDETALLE=_IDDETALLE);
IF (SELECT COUNT(*) FROM PAGOS WHERE IDARRIENDO=_IDARRIENDO)=0 THEN
BEGIN
 SET OP='1';
  DELETE FROM DETALLE 
  WHERE IDDETALLE=_IDDETALLE;
  
  UPDATE arriendos 
SET MONTO= (SELECT SUM(ALQUILER_NOMINAL) 
            FROM DETALLE WHERE IDARRIENDO=_IDARRIENDO)
WHERE IDARRIENDO=_IDARRIENDO;

  
END;
ELSE
SET OP='Error, Tiene registro de pagos';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DEL_USUARIO` (IN `_idusuario` INT)   BEGIN
DECLARE OP VARCHAR(100);
IF((SELECT COUNT(*) FROM ARRIENDOS
    WHERE IDUSUARIO=_idusuario) +
  (SELECT COUNT(*) FROM PAGOS 
   WHERE IDUSUARIO = _idusuario) =0
  AND
  (SELECT ACTIVO FROM usuarios 
   WHERE IDUSUARIO=_idusuario)='SI') THEN
BEGIN
 SET OP='1';
 DELETE FROM USUARIOS 
WHERE IDUSUARIO=_idusuario;
END;
ELSE
 SET OP='Error, El registro tiene historial ó no se encuentra Activo';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_GENERA_PAGOS` (IN `_IDARRIENDO` INT)   BEGIN
    DECLARE _FECHA DATE;
    DECLARE _MONTO DECIMAL(10,2);
    DECLARE _MONTO_FRACCION DECIMAL(10,2);
    DECLARE _TIEMPO INT;
    DECLARE CADENA VARCHAR(7);
    DECLARE I INT;
    DECLARE _DIAS_MES INT;
    DECLARE _DIAS_RESTANTES INT;

    -- Obtener datos iniciales del contrato
    SET _FECHA=(SELECT STR_TO_DATE(FECHA_INICIO, '%Y-%m-%d') FROM arriendos WHERE IDARRIENDO=_IDARRIENDO);
    SET _MONTO=(SELECT MONTO FROM arriendos WHERE IDARRIENDO=_IDARRIENDO);
    SET _TIEMPO=(SELECT TIEMPOCONTRATO FROM arriendos WHERE IDARRIENDO=_IDARRIENDO);
     
    SET I = 0;
    WHILE I < _TIEMPO DO
        SET CADENA = LEFT(_FECHA, 7);
         
        -- Evaluar si es el primer mes y el contrato no inicia el día 1 (Prorrateo)
        IF I = 0 AND DAY(_FECHA) > 1 THEN
            SET _DIAS_MES = DAY(LAST_DAY(_FECHA));
            SET _DIAS_RESTANTES = _DIAS_MES - DAY(_FECHA) + 1;
            
            -- Fórmula: (Monto Total / Días que tiene el mes) * Días Restantes
            SET _MONTO_FRACCION = ROUND((_MONTO / _DIAS_MES) * _DIAS_RESTANTES, 2);
            
            INSERT INTO pagos (IDARRIENDO, PERIODO, MONTO) VALUES (_IDARRIENDO, CADENA, _MONTO_FRACCION);
        ELSE
            -- Mes completo para los meses siguientes
            INSERT INTO pagos (IDARRIENDO, PERIODO, MONTO) VALUES (_IDARRIENDO, CADENA, _MONTO);
        END IF;

        -- Sumar un mes y pasar al primer día del siguiente mes para el ciclo
        SET _FECHA = ADDDATE(LAST_DAY(_FECHA), INTERVAL 1 DAY);
          
        SET I = I + 1;
    END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INSERT_AREA` (IN `_REFERENCIA` VARCHAR(50), IN `_UBICACION` VARCHAR(50))   BEGIN
DECLARE OP VARCHAR(1);
INSERT INTO AREAUBICACION 
(REFERENCIA, UBICACION) VALUES
(UPPER(_REFERENCIA), UPPER(_UBICACION));
SET OP='1';
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INSERT_CATALOGO` (IN `_IDAREA` INT, IN `_DESCRIPCION` VARCHAR(100), IN `_ALQUILER` DECIMAL)   BEGIN
DECLARE OP VARCHAR(100);
SET OP='1';
INSERT INTO CATALOGO 
(IDAREA, DESCRIPCION, ALQUILER)
VALUES (_IDAREA, UPPER(_DESCRIPCION), _ALQUILER);
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INSERT_CLIENTE` (IN `_NOMBRE` VARCHAR(150), IN `_CEDULA` VARCHAR(15), IN `_CONTACTOS` VARCHAR(20), IN `_DIRECCION` VARCHAR(70))   BEGIN
DECLARE OP VARCHAR(1);
SET OP='1';
INSERT INTO clientes (NOMBRE_COMPLETO, CEDULA, CONTACTOS, DIRECCION) VALUES
(UPPER(_NOMBRE), _CEDULA, _CONTACTOS, UPPER(_DIRECCION));
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INSERT_CONTRATO` (IN `_IDUSUARIO` INT, IN `_IDCLIENTE` INT, IN `_ACTIVIDAD` VARCHAR(100), IN `_RAZONSOCIAL` VARCHAR(100), IN `_CONTRATO` VARCHAR(30), IN `_FECHA_SUSCRIPCION` VARCHAR(10), IN `_FECHAINICIO` VARCHAR(10), IN `_TIEMPOCONTRATO` INT)   BEGIN
DECLARE OP VARCHAR(10);
SET OP='1';
INSERT INTO ARRIENDOS
(IDUSUARIO, IDCLIENTE, ACTIVIDAD, RAZONSOCIAL, CONTRATO, FECHA_SUSCRIPCION, FECHA_INICIO, TIEMPOCONTRATO) VALUES
(_IDUSUARIO, _IDCLIENTE, UPPER(_ACTIVIDAD), UPPER(_RAZONSOCIAL), UPPER(_CONTRATO), _FECHA_SUSCRIPCION, _FECHAINICIO, _TIEMPOCONTRATO);
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INSERT_DETALLE` (IN `_IDARRIENDO` INT, IN `_IDCATALOGO` INT)   BEGIN
DECLARE OP VARCHAR(2);
DECLARE _ALQUILER DECIMAL;
SET _ALQUILER=(SELECT ALQUILER FROM 
               CATALOGO WHERE 
               IDCATALOGO=_IDCATALOGO);
INSERT INTO DETALLE (IDCATALOGO, IDARRIENDO, ALQUILER_NOMINAL) VALUES
(_IDCATALOGO, _IDARRIENDO, _ALQUILER);
UPDATE arriendos 
SET MONTO= (SELECT SUM(ALQUILER_NOMINAL) 
            FROM DETALLE WHERE IDARRIENDO=_IDARRIENDO)
WHERE IDARRIENDO=_IDARRIENDO;
SET OP='1';
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INSERT_USUARIO` (IN `_nombre` VARCHAR(50), `_usr` VARCHAR(20), `_clave` VARCHAR(255), `_idrol` INT)   BEGIN
DECLARE OP VARCHAR(100);
if ((select count(*) from usuarios WHERE USR like _usr)=0) THEN
BEGIN
 INSERT INTO usuarios (NOMBRE, USR, PASS, IDROL)
 VALUES(UPPER( _nombre), _usr, _clave, _idrol);
 SET OP='1';
 END;
ELSE
 SET OP='Error, el Nombre de Usuario debe ser diferente';
end if;
select OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LOGIN` (IN `p_usr` VARCHAR(20))   BEGIN
    SELECT 
        IDUSUARIO, 
        USR, 
        PASS, 
        CEL, 
        IDROL AS ROL
    FROM usuarios 
    WHERE ACTIVO = 'SI' 
      AND USR = p_usr 
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_USUARIO` (IN `_idusuario` INT, IN `_nombre` VARCHAR(50), IN `_usuario` VARCHAR(20), IN `_clave` VARCHAR(255), IN `_idrol` INT)   BEGIN
DECLARE OP VARCHAR(100);
IF((SELECT COUNT(*) FROM usuarios
    WHERE USR = _usuario and IDUSUARIO!=_idusuario)=0)
  AND ((SELECT ACTIVO FROM usuarios 
       WHERE IDUSUARIO=_idusuario)='SI')  THEN
BEGIN
 SET OP='1';
 UPDATE USUARIOS SET 
        NOMBRE= UPPER(_nombre),
        USR= _usuario,
        PASS=_clave,
        IDROL=_idrol
 WHERE IDUSUARIO=_idusuario;
END;
ELSE
 SET OP='Error, la Cuenta de Usuario no se encuentra Activa ó debe utilizar un nombre de usuario diferente';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MOD_AREA` (IN `_IDAREA` INT, IN `_REFERENCIA` VARCHAR(50), IN `_UBICACION` VARCHAR(50))   BEGIN
DECLARE OP VARCHAR(1);
UPDATE AREAUBICACION
SET REFERENCIA=UPPER(_REFERENCIA),
    UBICACION=UPPER(_UBICACION)
WHERE IDAREA=_IDAREA;
SET OP='1';
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MOD_ARRENDAMIENTO_UNIFICADO` (IN `_IDARRIENDO` INT, IN `_CEDULA` VARCHAR(15), IN `_NOMBRES` VARCHAR(50), IN `_PATERNO` VARCHAR(50), IN `_MATERNO` VARCHAR(50), IN `_CONTACTOS` VARCHAR(20), IN `_DIRECCION` VARCHAR(70), IN `_IDCATALOGO` INT, IN `_ACTIVIDAD` VARCHAR(100), IN `_RAZONSOCIAL` VARCHAR(100), IN `_CONTRATO` VARCHAR(30), IN `_FECHA_SUSCRIPCION` VARCHAR(10), IN `_FECHAINICIO` VARCHAR(10), IN `_TIEMPOCONTRATO` INT)   BEGIN
    DECLARE _IDCLIENTE INT;
    DECLARE _ALQUILER DECIMAL(10,0);
    DECLARE _NOMBRE_COMPLETO VARCHAR(150);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;
        SELECT 'Error interno en la BD al modificar el contrato.' AS OP;
    END;

    START TRANSACTION;

    -- 1. Construir Nombre Completo
    SET _NOMBRE_COMPLETO = TRIM(CONCAT(IFNULL(_PATERNO,''), ' ', IFNULL(_MATERNO,''), ' ', IFNULL(_NOMBRES,'')));

    -- 2. Actualizar Cliente
    IF (SELECT COUNT(*) FROM clientes WHERE CEDULA = _CEDULA) > 0 THEN
        SELECT IDCLIENTE INTO _IDCLIENTE FROM clientes WHERE CEDULA = _CEDULA LIMIT 1;
        UPDATE clientes SET 
            NOMBRES = UPPER(_NOMBRES), PATERNO = UPPER(_PATERNO), MATERNO = UPPER(_MATERNO),
            NOMBRE_COMPLETO = UPPER(_NOMBRE_COMPLETO), CONTACTOS = _CONTACTOS, DIRECCION = UPPER(_DIRECCION)
        WHERE IDCLIENTE = _IDCLIENTE;
    ELSE
        INSERT INTO clientes (NOMBRES, PATERNO, MATERNO, NOMBRE_COMPLETO, CEDULA, CONTACTOS, DIRECCION)
        VALUES (UPPER(_NOMBRES), UPPER(_PATERNO), UPPER(_MATERNO), UPPER(_NOMBRE_COMPLETO), _CEDULA, _CONTACTOS, UPPER(_DIRECCION));
        SET _IDCLIENTE = LAST_INSERT_ID();
    END IF;

    -- 3. Modificar Cabecera de Arriendo
    UPDATE arriendos SET 
        IDCLIENTE = _IDCLIENTE, ACTIVIDAD = UPPER(_ACTIVIDAD), RAZONSOCIAL = UPPER(_RAZONSOCIAL),
        CONTRATO = UPPER(_CONTRATO), FECHA_SUSCRIPCION = _FECHA_SUSCRIPCION, FECHA_INICIO = _FECHAINICIO,
        TIEMPOCONTRATO = _TIEMPOCONTRATO
    WHERE IDARRIENDO = _IDARRIENDO;

    -- 4. Modificar el Detalle y actualizar Monto Total
    SELECT ALQUILER INTO _ALQUILER FROM catalogo WHERE IDCATALOGO = _IDCATALOGO LIMIT 1;

    IF (SELECT COUNT(*) FROM detalle WHERE IDARRIENDO = _IDARRIENDO) > 0 THEN
        UPDATE detalle SET IDCATALOGO = _IDCATALOGO, ALQUILER_NOMINAL = _ALQUILER 
        WHERE IDARRIENDO = _IDARRIENDO LIMIT 1;
    ELSE
        INSERT INTO detalle (IDCATALOGO, IDARRIENDO, ALQUILER_NOMINAL) VALUES (_IDCATALOGO, _IDARRIENDO, _ALQUILER);
    END IF;

    UPDATE arriendos SET MONTO = (SELECT SUM(ALQUILER_NOMINAL) FROM detalle WHERE IDARRIENDO = _IDARRIENDO) WHERE IDARRIENDO = _IDARRIENDO;

    COMMIT;
    SELECT '1' AS OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MOD_CATALOGO` (IN `_IDCATALOGO` INT, IN `_IDAREA` INT, IN `_DESCRIPCION` VARCHAR(100), IN `_ALQUILER` DECIMAL)   BEGIN
DECLARE OP VARCHAR (100);
IF (SELECT COUNT(*) FROM CATALOGO 
   WHERE IDCATALOGO =_IDCATALOGO
          AND ESTADO ='DISPONIBLE')=1 THEN
BEGIN
 SET OP='1';
 UPDATE CATALOGO
 SET IDAREA=_IDAREA,
     DESCRIPCION= UPPER(_DESCRIPCION),
     ALQUILER=_ALQUILER
 WHERE IDCATALOGO=_IDCATALOGO;
END;
ELSE
 SET OP='Error, El registro no se encuentra Disponible';
END IF;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MOD_CLIENTE` (IN `_IDCLIENTE` INT, IN `_NOMBRE` VARCHAR(150), IN `_CEDULA` VARCHAR(15), IN `_CONTACTOS` VARCHAR(20), IN `_DIRECCION` VARCHAR(70))   BEGIN
DECLARE OP VARCHAR(10);
SET OP='1';
UPDATE clientes SET
 NOMBRE_COMPLETO=UPPER(_NOMBRE),
 CEDULA= _CEDULA,
 CONTACTOS=_CONTACTOS,
 DIRECCION= UPPER(_DIRECCION)
WHERE IDCLIENTE=_IDCLIENTE;
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MOD_CONTRATO` (IN `_IDARRIENDO` INT, IN `_IDCLIENTE` INT, IN `_ACTIVIDAD` VARCHAR(100), IN `_RAZONSOCIAL` VARCHAR(100), IN `_FECHA_SUSCRIPCION` VARCHAR(10), IN `_FECHA_INICIO` VARCHAR(10), IN `_TIEMPOCONTRATO` INT, IN `_CONTRATO` VARCHAR(30))   BEGIN
DECLARE OP VARCHAR(2);
UPDATE ARRIENDOS SET
IDCLIENTE=_IDCLIENTE,
ACTIVIDAD=UPPER(_ACTIVIDAD),
RAZONSOCIAL=UPPER(_RAZONSOCIAL),
FECHA_SUSCRIPCION=_FECHA_SUSCRIPCION,
CONTRATO=UPPER(_CONTRATO),
FECHA_INICIO=_FECHA_INICIO,
TIEMPOCONTRATO=_TIEMPOCONTRATO
WHERE IDARRIENDO=_IDARRIENDO;
SET OP='1';
SELECT OP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_NUEVO_ARRENDAMIENTO` (IN `_IDUSUARIO` INT, IN `_CEDULA` VARCHAR(15), IN `_NOMBRES` VARCHAR(50), IN `_PATERNO` VARCHAR(50), IN `_MATERNO` VARCHAR(50), IN `_CONTACTOS` VARCHAR(20), IN `_DIRECCION` VARCHAR(70), IN `_IDCATALOGO` INT, IN `_ACTIVIDAD` VARCHAR(100), IN `_RAZONSOCIAL` VARCHAR(100), IN `_CONTRATO` VARCHAR(30), IN `_FECHA_SUSCRIPCION` VARCHAR(10), IN `_FECHAINICIO` VARCHAR(10), IN `_TIEMPOCONTRATO` INT)   BEGIN
    DECLARE _IDCLIENTE INT;
    DECLARE _IDARRIENDO INT;
    DECLARE _ALQUILER DECIMAL(10,0);
    DECLARE _NOMBRE_COMPLETO VARCHAR(150);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;
        SELECT 'Error interno en la BD al guardar el contrato.' AS OP;
    END;

    START TRANSACTION;

    SET _NOMBRE_COMPLETO = TRIM(CONCAT(IFNULL(_PATERNO,''), ' ', IFNULL(_MATERNO,''), ' ', IFNULL(_NOMBRES,'')));

    IF (SELECT COUNT(*) FROM clientes WHERE CEDULA = _CEDULA) > 0 THEN
        SELECT IDCLIENTE INTO _IDCLIENTE FROM clientes WHERE CEDULA = _CEDULA LIMIT 1;
        UPDATE clientes SET 
            NOMBRES = UPPER(_NOMBRES), PATERNO = UPPER(_PATERNO), MATERNO = UPPER(_MATERNO),
            NOMBRE_COMPLETO = UPPER(_NOMBRE_COMPLETO), CONTACTOS = _CONTACTOS, DIRECCION = UPPER(_DIRECCION)
        WHERE IDCLIENTE = _IDCLIENTE;
    ELSE
        INSERT INTO clientes (NOMBRES, PATERNO, MATERNO, NOMBRE_COMPLETO, CEDULA, CONTACTOS, DIRECCION)
        VALUES (UPPER(_NOMBRES), UPPER(_PATERNO), UPPER(_MATERNO), UPPER(_NOMBRE_COMPLETO), _CEDULA, _CONTACTOS, UPPER(_DIRECCION));
        SET _IDCLIENTE = LAST_INSERT_ID();
    END IF;

    INSERT INTO arriendos (IDUSUARIO, IDCLIENTE, ACTIVIDAD, RAZONSOCIAL, CONTRATO, FECHA_SUSCRIPCION, FECHA_INICIO, TIEMPOCONTRATO) 
    VALUES (_IDUSUARIO, _IDCLIENTE, UPPER(_ACTIVIDAD), UPPER(_RAZONSOCIAL), UPPER(_CONTRATO), _FECHA_SUSCRIPCION, _FECHAINICIO, _TIEMPOCONTRATO);
    
    SET _IDARRIENDO = LAST_INSERT_ID();

    SELECT ALQUILER INTO _ALQUILER FROM catalogo WHERE IDCATALOGO = _IDCATALOGO LIMIT 1;
    
    INSERT INTO detalle (IDCATALOGO, IDARRIENDO, ALQUILER_NOMINAL) 
    VALUES (_IDCATALOGO, _IDARRIENDO, _ALQUILER);

    UPDATE arriendos SET MONTO = (SELECT SUM(ALQUILER_NOMINAL) FROM detalle WHERE IDARRIENDO = _IDARRIENDO) WHERE IDARRIENDO = _IDARRIENDO;

    -- AQUÍ SE ELIMINÓ LA INSERCIÓN AUTOMÁTICA DE "GARANTIA" A LA TABLA DE PAGOS

    COMMIT;
    SELECT '1' AS OP;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areaubicacion`
--

CREATE TABLE `areaubicacion` (
  `IDAREA` int(11) NOT NULL,
  `REFERENCIA` varchar(50) NOT NULL,
  `UBICACION` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areaubicacion`
--

INSERT INTO `areaubicacion` (`IDAREA`, `REFERENCIA`, `UBICACION`) VALUES
(1, 'STAND', 'PLANTA ALTA'),
(2, 'OFICINA DEPARTAMENTAL', 'ZONA OESTE'),
(3, 'OFICINA PROVINCIAL', 'ZONA ESTE'),
(4, 'KIOSCO', 'PLANTA BAJA'),
(5, 'ESPACIO', 'DISPERSO'),
(6, 'BODEGA', 'DISPERSO'),
(7, 'SERVICIO BÁSICO', 'INFRAESTRUCTURA DEL EDIFICIO'),
(8, 'CARRILES OESTE', 'EXTERIOR'),
(9, 'CARRILES ESTE', 'EXTERIOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arriendos`
--

CREATE TABLE `arriendos` (
  `IDARRIENDO` int(11) NOT NULL,
  `IDUSUARIO` int(11) NOT NULL,
  `IDCLIENTE` int(11) NOT NULL,
  `ACTIVIDAD` varchar(100) NOT NULL,
  `RAZONSOCIAL` varchar(100) NOT NULL,
  `CONTRATO` varchar(30) NOT NULL,
  `FECHA_SUSCRIPCION` varchar(10) DEFAULT NULL,
  `FECHA_INICIO` varchar(10) NOT NULL,
  `TIEMPOCONTRATO` int(11) NOT NULL,
  `MONTO` decimal(10,0) NOT NULL DEFAULT 0,
  `OBSERVACIONES` varchar(250) NOT NULL DEFAULT 'SIN OBSERVACIÓN',
  `VIGENTE` varchar(3) NOT NULL DEFAULT 'PR',
  `FECHA_REGISTRO` datetime NOT NULL DEFAULT current_timestamp(),
  `ARCHIVO_PDF` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arriendos`
--

INSERT INTO `arriendos` (`IDARRIENDO`, `IDUSUARIO`, `IDCLIENTE`, `ACTIVIDAD`, `RAZONSOCIAL`, `CONTRATO`, `FECHA_SUSCRIPCION`, `FECHA_INICIO`, `TIEMPOCONTRATO`, `MONTO`, `OBSERVACIONES`, `VIGENTE`, `FECHA_REGISTRO`, `ARCHIVO_PDF`) VALUES
(1, 7, 10, 'BODEGA PARA GUARDAR DULCES', 'SIN DATO', 'CON-LEG/BOD-01/2026', '2026-06-17', '2026-06-17', 7, 600, 'SIN OBSERVACIÓN', 'SI', '2026-06-17 14:33:49', NULL),
(2, 7, 1, 'VENTA DE ROPA', 'SIN DATO', 'CONT-LEG-STAND1', '2026-06-17', '2026-06-17', 7, 600, 'SIN OBSERVACIÓN', 'SI', '2026-06-17 14:56:35', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `IDCATALOGO` int(11) NOT NULL,
  `IDAREA` int(11) NOT NULL,
  `DESCRIPCION` varchar(100) NOT NULL,
  `ALQUILER` decimal(10,0) NOT NULL DEFAULT 0,
  `ESTADO` varchar(15) NOT NULL DEFAULT 'DISPONIBLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`IDCATALOGO`, `IDAREA`, `DESCRIPCION`, `ALQUILER`, `ESTADO`) VALUES
(1, 6, 'BODEGA 2', 200, 'DISPONIBLE'),
(3, 6, 'BODEGA 3', 200, 'DISPONIBLE'),
(4, 6, 'BODEGA 4', 200, 'DISPONIBLE'),
(6, 1, 'STAND 1', 600, 'ALQUILADO'),
(7, 1, 'STAND 4', 700, 'DISPONIBLE'),
(8, 1, 'STAND 3', 700, 'DISPONIBLE'),
(10, 6, 'BODEGA 1', 600, 'ALQUILADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `IDCLIENTE` int(11) NOT NULL,
  `NOMBRES` varchar(50) DEFAULT NULL,
  `PATERNO` varchar(50) DEFAULT NULL,
  `MATERNO` varchar(50) DEFAULT NULL,
  `NOMBRE_COMPLETO` varchar(150) NOT NULL,
  `CEDULA` varchar(15) NOT NULL,
  `CONTACTOS` varchar(20) NOT NULL,
  `DIRECCION` varchar(70) NOT NULL,
  `LATITUD` decimal(10,8) DEFAULT NULL,
  `LONGITUD` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`IDCLIENTE`, `NOMBRES`, `PATERNO`, `MATERNO`, `NOMBRE_COMPLETO`, `CEDULA`, `CONTACTOS`, `DIRECCION`, `LATITUD`, `LONGITUD`) VALUES
(1, 'ALVARO', 'FLORES ', 'PINTO', 'FLORES  PINTO ALVARO', '8545213', '54625135-6546513', 'ZONAS DE ORURO', NULL, NULL),
(2, NULL, NULL, NULL, 'JAIME ZENTENO ROBLES', '6521485', '5256187-72315874', 'NORTE PLAZA SEBASTIAN PAGADOR', NULL, NULL),
(3, NULL, NULL, NULL, 'FERNANDO QUISPE CASTRO', '36254125', '72358427', 'EL ALTO VILLA ADELA', NULL, NULL),
(4, NULL, NULL, NULL, 'DIVAR GUTIERREZ SORIA GALVARRO', '2421324', '1502021458', 'ZONA NORTE FRENTE AL SAPO', NULL, NULL),
(5, NULL, NULL, NULL, 'BORIS MONTAÑO AJATA', '2517854', '683524785', 'ZONA NORTE PLAZA SEBASTIAN PAGADOR', NULL, NULL),
(6, NULL, NULL, NULL, 'ROBERTO WILSON SOTO VERA', '74875854', '72438529', 'JUNIN ESQUINA BRASIL', NULL, NULL),
(7, NULL, NULL, NULL, 'ALEJANDRO LIMA FERNANDEZ', '3562847', '683152457', 'CALLE 3 ESQUINA CALLE 4 ZONA NORTE', NULL, NULL),
(8, NULL, NULL, NULL, 'MIGUEL JORGE SOTO', '42515687', '685472154', 'CALLE 1 Y CALLE 4 ZONA YPFB', NULL, NULL),
(9, NULL, NULL, NULL, 'AMPARO SORIA VALDE', '102030', '683259741', 'ZONA SUD CALLE 4 BARRIO SOROA', NULL, NULL),
(10, 'REYNALDO JESUS', 'FLORES', 'JAILLITA', 'FLORES JAILLITA REYNALDO JESUS', '7403044', '60408150', 'C/J MENDOZA #148', NULL, NULL),
(11, 'JUAN', 'PEREZ', 'PEREZ', 'PEREZ PEREZ JUAN', '85465854', '71852583', 'CALLE 1', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correlativos`
--

CREATE TABLE `correlativos` (
  `ID` int(11) NOT NULL,
  `ULTIMO_RECIBO` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `correlativos`
--

INSERT INTO `correlativos` (`ID`, `ULTIMO_RECIBO`) VALUES
(1, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle`
--

CREATE TABLE `detalle` (
  `IDDETALLE` int(11) NOT NULL,
  `IDCATALOGO` int(11) NOT NULL,
  `IDARRIENDO` int(11) NOT NULL,
  `ALQUILER_NOMINAL` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle`
--

INSERT INTO `detalle` (`IDDETALLE`, `IDCATALOGO`, `IDARRIENDO`, `ALQUILER_NOMINAL`) VALUES
(1, 10, 1, 600),
(2, 6, 2, 600);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `garantias_cumplimiento`
--

CREATE TABLE `garantias_cumplimiento` (
  `IDGARANTIA` int(11) NOT NULL,
  `CITE_ADJUDICACION` varchar(50) NOT NULL,
  `CI_POSTULANTE` varchar(15) NOT NULL,
  `NOMBRE_POSTULANTE` varchar(150) NOT NULL,
  `IDCATALOGO` int(11) NOT NULL,
  `MONTO` decimal(10,2) NOT NULL,
  `FECHA_COBRO` datetime NOT NULL DEFAULT current_timestamp(),
  `FECHA_DEVOLUCION` datetime DEFAULT NULL,
  `ESTADO` varchar(20) NOT NULL DEFAULT 'RETENIDA',
  `IDARRIENDO` int(11) DEFAULT NULL,
  `USUARIO` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_accesos`
--

CREATE TABLE `log_accesos` (
  `IDLOG` int(11) NOT NULL,
  `IDUSUARIO` int(11) NOT NULL,
  `TOKEN` varchar(6) NOT NULL,
  `FECHA_CREACION` datetime DEFAULT current_timestamp(),
  `FECHA_EXPIRACION` datetime NOT NULL,
  `ESTADO` varchar(15) DEFAULT 'PENDIENTE',
  `IP_ACCESO` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_accesos`
--

INSERT INTO `log_accesos` (`IDLOG`, `IDUSUARIO`, `TOKEN`, `FECHA_CREACION`, `FECHA_EXPIRACION`, `ESTADO`, `IP_ACCESO`) VALUES
(1, 7, '809967', '2026-06-17 15:30:24', '2026-06-17 15:35:24', 'USADO', '::1'),
(2, 7, '595471', '2026-06-18 09:00:50', '2026-06-18 09:05:50', 'USADO', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_cierres`
--

CREATE TABLE `log_cierres` (
  `IDLOGCIERRE` int(11) NOT NULL,
  `FECHA_INICIO` date NOT NULL,
  `FECHA_FIN` date NOT NULL,
  `FECHA_GENERACION` datetime NOT NULL DEFAULT current_timestamp(),
  `USUARIO` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_estornos`
--

CREATE TABLE `log_estornos` (
  `IDLOG` int(11) NOT NULL,
  `NRO_RECIBO` varchar(50) NOT NULL,
  `MONTO_TOTAL` decimal(10,2) NOT NULL,
  `PERIODOS_COBRADOS` varchar(255) NOT NULL,
  `CLIENTE` varchar(255) NOT NULL,
  `CEDULA` varchar(50) NOT NULL,
  `CONTRATO` varchar(50) NOT NULL,
  `ACTIVIDAD` varchar(255) NOT NULL,
  `METODO_PAGO` varchar(50) DEFAULT NULL,
  `NRO_COMPROBANTE` varchar(100) DEFAULT NULL,
  `NRO_FACTURA_SIAT` varchar(100) DEFAULT NULL,
  `CAJERO_ORIGINAL` varchar(100) NOT NULL,
  `FECHA_COBRO` datetime NOT NULL,
  `USUARIO_QUE_ANULA` varchar(100) NOT NULL,
  `FECHA_ANULACION` datetime NOT NULL DEFAULT current_timestamp(),
  `MOTIVO` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_estornos`
--

INSERT INTO `log_estornos` (`IDLOG`, `NRO_RECIBO`, `MONTO_TOTAL`, `PERIODOS_COBRADOS`, `CLIENTE`, `CEDULA`, `CONTRATO`, `ACTIVIDAD`, `METODO_PAGO`, `NRO_COMPROBANTE`, `NRO_FACTURA_SIAT`, `CAJERO_ORIGINAL`, `FECHA_COBRO`, `USUARIO_QUE_ANULA`, `FECHA_ANULACION`, `MOTIVO`) VALUES
(1, '000010', 600.00, '2026-08', 'FLORES  PINTO ALVARO', '8545213', 'CONT-LEG-STAND1', 'VENTA DE ROPA', 'EFECTIVO', '', '', 'wil.arroyo', '2026-06-18 09:01:30', 'wil.arroyo', '2026-06-18 09:01:57', 'ADSD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `IDPAGO` int(11) NOT NULL,
  `IDARRIENDO` int(11) NOT NULL,
  `PERIODO` varchar(30) NOT NULL,
  `MONTO` decimal(10,2) NOT NULL DEFAULT 0.00,
  `FECHA_PAGO` datetime DEFAULT NULL,
  `PENDIENTE` varchar(2) NOT NULL DEFAULT 'SI',
  `NRO_RECIBO` varchar(20) DEFAULT NULL,
  `USR` varchar(20) DEFAULT NULL,
  `METODO_PAGO` varchar(50) DEFAULT 'EFECTIVO',
  `NRO_COMPROBANTE` varchar(100) DEFAULT NULL,
  `NRO_FACTURA_SIAT` varchar(100) DEFAULT NULL,
  `ESTADO_RECIBO` varchar(15) NOT NULL DEFAULT 'ACTIVO',
  `MOTIVO_ANULACION` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`IDPAGO`, `IDARRIENDO`, `PERIODO`, `MONTO`, `FECHA_PAGO`, `PENDIENTE`, `NRO_RECIBO`, `USR`, `METODO_PAGO`, `NRO_COMPROBANTE`, `NRO_FACTURA_SIAT`, `ESTADO_RECIBO`, `MOTIVO_ANULACION`) VALUES
(1, 1, '2026-06', 280.00, '2026-06-17 14:53:41', 'NO', '000001', 'wil.arroyo', 'EFECTIVO', '', '', 'ACTIVO', NULL),
(2, 1, '2026-07', 600.00, NULL, 'SI', NULL, 'wil.arroyo', 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(3, 1, '2026-08', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(4, 1, '2026-09', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(5, 1, '2026-10', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(6, 1, '2026-11', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(7, 1, '2026-12', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(8, 2, '2026-06', 280.00, '2026-06-17 14:58:22', 'NO', '000008', 'wil.arroyo', 'EFECTIVO', '', '', 'ACTIVO', NULL),
(9, 2, '2026-07', 600.00, '2026-06-17 15:56:14', 'NO', '000009', 'wil.arroyo', 'EFECTIVO', '', '', 'ACTIVO', NULL),
(10, 2, '2026-08', 600.00, '2026-06-18 09:02:18', 'NO', '000011', 'wil.arroyo', 'TRANSFERENCIA', '', '', 'ACTIVO', NULL),
(11, 2, '2026-09', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(12, 2, '2026-10', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(13, 2, '2026-11', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL),
(14, 2, '2026-12', 600.00, NULL, 'SI', NULL, NULL, 'EFECTIVO', NULL, NULL, 'ACTIVO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propuestas`
--

CREATE TABLE `propuestas` (
  `IDPROPUESTA` int(11) NOT NULL,
  `CI_POSTULANTE` varchar(15) NOT NULL,
  `NOMBRE_POSTULANTE` varchar(150) NOT NULL,
  `IDCATALOGO` int(11) NOT NULL,
  `MONTO` decimal(10,2) NOT NULL DEFAULT 100.00,
  `FECHA_COBRO` datetime NOT NULL DEFAULT current_timestamp(),
  `FECHA_DEVOLUCION` datetime DEFAULT NULL,
  `ESTADO` varchar(20) NOT NULL DEFAULT 'RETENIDA',
  `USUARIO` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `IDROL` int(11) NOT NULL,
  `DESCRIPCION` varchar(50) NOT NULL,
  `SIGLA` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`IDROL`, `DESCRIPCION`, `SIGLA`) VALUES
(1, 'Administrador', 'ADM'),
(2, 'Reportes', 'REP'),
(3, 'Registros', 'REG'),
(4, 'Pagos', 'PAG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IDUSUARIO` int(11) NOT NULL,
  `NOMBRE` varchar(50) NOT NULL,
  `CEL` varchar(8) NOT NULL,
  `USR` varchar(20) NOT NULL,
  `PASS` varchar(255) NOT NULL,
  `FECHA_ALTA` datetime NOT NULL DEFAULT current_timestamp(),
  `FECHA_BAJA` datetime DEFAULT NULL,
  `ACTIVO` varchar(2) NOT NULL DEFAULT 'SI',
  `IDROL` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IDUSUARIO`, `NOMBRE`, `CEL`, `USR`, `PASS`, `FECHA_ALTA`, `FECHA_BAJA`, `ACTIVO`, `IDROL`) VALUES
(1, 'MILTON TORREZ ', '', 'miltorrez', '$2y$10$9JF9KjGZEabxEkebAsTR5OWFOx7CUv8nT8HgUYAQ5WG0C5Xyw621m', '2026-06-08 10:15:07', '2026-06-08 12:29:09', 'NO', 4),
(4, 'ERIKA ALEJANDRA JORGE SOTO', '', 'erika.jorge', '123', '2023-11-27 17:42:16', '2026-06-09 12:16:07', 'NO', 4),
(6, 'WILFREDO ARROYO ALEJANDRO', '', 'wili.arroyo', '123', '2023-11-27 23:02:42', '2023-11-27 23:54:17', 'NO', 2),
(7, 'WILFREDO ARROYO ALEJANDRO', '60408150', 'wil.arroyo', '$2y$10$faCDW1RQFp0YRARRP375HOynlk/EHh6ZW9KHQDQWiaHAjLiP/3fCC', '2023-11-28 20:55:46', '2026-05-18 08:38:23', 'SI', 1),
(8, 'ALBERTO VARGAS CONDORI', '', 'alberto.vargas', '123', '2023-11-28 20:56:50', '2023-12-05 04:22:16', 'NO', 3),
(9, 'ALEJANDRA SANDY LAURA', '', 'ale.sandy', '$2y$10$LLhIErX2M1N1yVfgUp/1K.qn9mYn4IRmOkHUqadsa1ms/bXBaarlm', '2024-03-23 17:02:03', '2026-05-18 13:57:19', 'NO', 4),
(10, 'ANA MARINA', '', 'Anamar', '$2y$10$Li/3Q411UC8x40b9YA/xB.ikdeD1O0eRSZWXq0eSO8hJXpdDaoat2', '2026-06-08 11:59:27', NULL, 'SI', 4),
(11, 'JHUDIT PEREZ', '', 'JPEREZ', '$2y$10$wtSdoKD3lqHVX0bxjn.RQOiMOxK9SQoqj5KkL/ZKbX9u47nQgGlqq', '2026-06-08 12:28:30', '2026-06-08 12:29:07', 'NO', 4),
(12, 'PEDOR', '', 'ASDSS', '$2y$10$zGUPHKOj6cpJtiVNvNN9fO2Rt3Q8z2aTHZZeZO.IazF15j3ziqTnW', '2026-06-08 12:28:52', '2026-06-08 12:29:00', 'NO', 1),
(13, '123', '', '123', '$2y$10$xNRcJJBrAy0BuqZdkXU7S.fuw6IAhBsqb5M19PM.DmPJfBXuxpbSK', '2026-06-08 12:29:48', '2026-06-08 13:18:25', 'NO', 4),
(14, '123123', '', '1232', '$2y$10$BRTrQtDfMFzXPB3p33LFHuVKQ1ehWLVyYZyWzqY1Weuwe3zp/g.Kq', '2026-06-08 13:19:18', '2026-06-08 13:47:46', 'NO', 1),
(15, 'REAS1', '', '123as', '$2y$10$3mnuXhxVc782viwvqtbG7.4tmr4RYZNyZlXw3DWTXPyHstRW1klg6', '2026-06-08 13:54:51', '2026-06-08 14:50:09', 'NO', 4),
(16, 'YGF', '', '13hgg', '$2y$10$qkhk0Cp05BwRJVLPyWe15eE6CDx5odg8//E/8NnJTHCENFSdeNZd2', '2026-06-08 14:23:30', NULL, 'SI', 4),
(17, '41234', '', 'asdass1', '$2y$10$rdqBUqyIO5GBo5bch8CN8eskhFFHR/kYsjq.X/FD7XdRNjgj1/QEG', '2026-06-08 14:50:20', '2026-06-08 14:50:50', 'NO', 4),
(18, '4123', '', '1312asd', '$2y$10$lZTLb1XGzYAy/aZOIUN.POZtf8mowMaKFaxluNGaIN7w/dXywLxva', '2026-06-08 15:23:08', '2026-06-08 15:23:55', 'NO', 4),
(19, '12414', '', '312312', '$2y$10$4/a1mDTAv3IUorWiSH9RPul7anuaQpLK5j4Z6G98UdeJJgy/e6Vfm', '2026-06-08 15:36:51', '2026-06-08 15:37:04', 'NO', 4),
(20, 'JOSE JOSE', '', '123asdad', '$2y$10$UmTJkQzU25loY/bzFdbDDe7ZzilPh7AHpHSCpwT.9coDLdS.pkpkO', '2026-06-09 10:55:38', NULL, 'SI', 4);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_areas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_areas` (
`IDAREA` int(11)
,`DISTRIBUCION` varchar(103)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_catalogo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_catalogo` (
`IDCATALOGO` int(11)
,`IDAREA` int(11)
,`DISTRIBUCION` varchar(103)
,`DESCRIPCION` varchar(100)
,`ALQUILER` decimal(10,0)
,`ESTADO` varchar(15)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_contratos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_contratos` (
`IDARRIENDO` int(11)
,`IDUSUARIO` int(11)
,`IDCLIENTE` int(11)
,`ACTIVIDAD` varchar(100)
,`RAZONSOCIAL` varchar(100)
,`CONTRATO` varchar(30)
,`FECHA_INICIO` varchar(10)
,`TIEMPOCONTRATO` int(11)
,`MONTO` decimal(10,0)
,`OBSERVACIONES` varchar(250)
,`VIGENTE` varchar(3)
,`FECHA_REGISTRO` datetime
,`REPRESENTANTE` varchar(168)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_detalle`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_detalle` (
`IDDETALLE` int(11)
,`IDARRIENDO` int(11)
,`IDCATALOGO` int(11)
,`DISTRIBUCION` varchar(103)
,`DESCRIPCION` varchar(100)
,`ALQUILER` decimal(10,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_resumen_gral_contrato`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_resumen_gral_contrato` (
`IDARRIENDO` int(11)
,`GENERAL` varchar(399)
,`REFERENCIAL` varchar(100)
,`ESPECIFICO` varchar(31)
,`VIGENTE` varchar(3)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_resumen_pagos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_resumen_pagos` (
`IDARRIENDO` int(11)
,`VIGENTE` varchar(3)
,`IDPAGO` int(11)
,`PERIODO` varchar(30)
,`MONTO` decimal(10,2)
,`FECHA_PAGO` datetime
,`PENDIENTE` varchar(2)
,`USR` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_areas`
--
DROP TABLE IF EXISTS `v_areas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_areas`  AS SELECT `areaubicacion`.`IDAREA` AS `IDAREA`, concat(`areaubicacion`.`REFERENCIA`,' - ',`areaubicacion`.`UBICACION`) AS `DISTRIBUCION` FROM `areaubicacion` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_catalogo`
--
DROP TABLE IF EXISTS `v_catalogo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_catalogo`  AS SELECT `c`.`IDCATALOGO` AS `IDCATALOGO`, `a`.`IDAREA` AS `IDAREA`, concat(`a`.`REFERENCIA`,' - ',`a`.`UBICACION`) AS `DISTRIBUCION`, `c`.`DESCRIPCION` AS `DESCRIPCION`, `c`.`ALQUILER` AS `ALQUILER`, `c`.`ESTADO` AS `ESTADO` FROM (`areaubicacion` `a` join `catalogo` `c`) WHERE `a`.`IDAREA` = `c`.`IDAREA` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_contratos`
--
DROP TABLE IF EXISTS `v_contratos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_contratos`  AS SELECT `a`.`IDARRIENDO` AS `IDARRIENDO`, `a`.`IDUSUARIO` AS `IDUSUARIO`, `a`.`IDCLIENTE` AS `IDCLIENTE`, `a`.`ACTIVIDAD` AS `ACTIVIDAD`, `a`.`RAZONSOCIAL` AS `RAZONSOCIAL`, `a`.`CONTRATO` AS `CONTRATO`, `a`.`FECHA_INICIO` AS `FECHA_INICIO`, `a`.`TIEMPOCONTRATO` AS `TIEMPOCONTRATO`, `a`.`MONTO` AS `MONTO`, `a`.`OBSERVACIONES` AS `OBSERVACIONES`, `a`.`VIGENTE` AS `VIGENTE`, `a`.`FECHA_REGISTRO` AS `FECHA_REGISTRO`, concat(`c`.`CEDULA`,' - ',`c`.`NOMBRE_COMPLETO`) AS `REPRESENTANTE` FROM (`arriendos` `a` join `clientes` `c`) WHERE `a`.`IDCLIENTE` = `c`.`IDCLIENTE` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_detalle`
--
DROP TABLE IF EXISTS `v_detalle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_detalle`  AS SELECT `d`.`IDDETALLE` AS `IDDETALLE`, `d`.`IDARRIENDO` AS `IDARRIENDO`, `d`.`IDCATALOGO` AS `IDCATALOGO`, `v`.`DISTRIBUCION` AS `DISTRIBUCION`, `v`.`DESCRIPCION` AS `DESCRIPCION`, `v`.`ALQUILER` AS `ALQUILER` FROM (`detalle` `d` join `v_catalogo` `v`) WHERE `d`.`IDCATALOGO` = `v`.`IDCATALOGO` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_resumen_gral_contrato`
--
DROP TABLE IF EXISTS `v_resumen_gral_contrato`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_resumen_gral_contrato`  AS SELECT `v_contratos`.`IDARRIENDO` AS `IDARRIENDO`, concat(`v_contratos`.`REPRESENTANTE`,' - ACTIVIDAD: ',`v_contratos`.`ACTIVIDAD`,' - RAZON SOCIAL: ',`v_contratos`.`RAZONSOCIAL`) AS `GENERAL`, concat('CONTRATO: ',`v_contratos`.`CONTRATO`,' - INICIO: ',`v_contratos`.`FECHA_INICIO`,' - TIEMPO CONTRATO: ',`v_contratos`.`TIEMPOCONTRATO`,' [MESES]') AS `REFERENCIAL`, concat('IMPORTE MENSUAL BS: ',`v_contratos`.`MONTO`) AS `ESPECIFICO`, `v_contratos`.`VIGENTE` AS `VIGENTE` FROM `v_contratos` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_resumen_pagos`
--
DROP TABLE IF EXISTS `v_resumen_pagos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_resumen_pagos`  AS SELECT `rc`.`IDARRIENDO` AS `IDARRIENDO`, `rc`.`VIGENTE` AS `VIGENTE`, `p`.`IDPAGO` AS `IDPAGO`, `p`.`PERIODO` AS `PERIODO`, `p`.`MONTO` AS `MONTO`, `p`.`FECHA_PAGO` AS `FECHA_PAGO`, `p`.`PENDIENTE` AS `PENDIENTE`, `p`.`USR` AS `USR` FROM (`pagos` `p` join `v_resumen_gral_contrato` `rc`) WHERE `rc`.`IDARRIENDO` = `p`.`IDARRIENDO` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areaubicacion`
--
ALTER TABLE `areaubicacion`
  ADD PRIMARY KEY (`IDAREA`);

--
-- Indices de la tabla `arriendos`
--
ALTER TABLE `arriendos`
  ADD PRIMARY KEY (`IDARRIENDO`),
  ADD KEY `IDCLIENTE` (`IDCLIENTE`),
  ADD KEY `IDUSUARIO` (`IDUSUARIO`);

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`IDCATALOGO`),
  ADD KEY `IDAREA` (`IDAREA`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`IDCLIENTE`);

--
-- Indices de la tabla `correlativos`
--
ALTER TABLE `correlativos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`IDDETALLE`),
  ADD KEY `IDCATALOGO` (`IDCATALOGO`),
  ADD KEY `IDARRIENDO` (`IDARRIENDO`);

--
-- Indices de la tabla `garantias_cumplimiento`
--
ALTER TABLE `garantias_cumplimiento`
  ADD PRIMARY KEY (`IDGARANTIA`),
  ADD KEY `IDCATALOGO` (`IDCATALOGO`),
  ADD KEY `IDARRIENDO` (`IDARRIENDO`);

--
-- Indices de la tabla `log_accesos`
--
ALTER TABLE `log_accesos`
  ADD PRIMARY KEY (`IDLOG`);

--
-- Indices de la tabla `log_cierres`
--
ALTER TABLE `log_cierres`
  ADD PRIMARY KEY (`IDLOGCIERRE`);

--
-- Indices de la tabla `log_estornos`
--
ALTER TABLE `log_estornos`
  ADD PRIMARY KEY (`IDLOG`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`IDPAGO`),
  ADD KEY `IDARRIENDO` (`IDARRIENDO`);

--
-- Indices de la tabla `propuestas`
--
ALTER TABLE `propuestas`
  ADD PRIMARY KEY (`IDPROPUESTA`),
  ADD KEY `IDCATALOGO` (`IDCATALOGO`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IDROL`),
  ADD UNIQUE KEY `SIGLA` (`SIGLA`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IDUSUARIO`),
  ADD KEY `fk_usuario_rol` (`IDROL`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areaubicacion`
--
ALTER TABLE `areaubicacion`
  MODIFY `IDAREA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `arriendos`
--
ALTER TABLE `arriendos`
  MODIFY `IDARRIENDO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `IDCATALOGO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `IDCLIENTE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `correlativos`
--
ALTER TABLE `correlativos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle`
--
ALTER TABLE `detalle`
  MODIFY `IDDETALLE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `garantias_cumplimiento`
--
ALTER TABLE `garantias_cumplimiento`
  MODIFY `IDGARANTIA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_accesos`
--
ALTER TABLE `log_accesos`
  MODIFY `IDLOG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `log_cierres`
--
ALTER TABLE `log_cierres`
  MODIFY `IDLOGCIERRE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_estornos`
--
ALTER TABLE `log_estornos`
  MODIFY `IDLOG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `IDPAGO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `propuestas`
--
ALTER TABLE `propuestas`
  MODIFY `IDPROPUESTA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IDUSUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `garantias_cumplimiento`
--
ALTER TABLE `garantias_cumplimiento`
  ADD CONSTRAINT `garantias_cumplimiento_ibfk_1` FOREIGN KEY (`IDCATALOGO`) REFERENCES `catalogo` (`IDCATALOGO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

<div class="login-box shadow-lg" style="border-radius: 10px;">
  
  <!-- Cabecera Institucional -->
  <div class="login-logo mb-3">
    <img src="<?php echo URL; ?>img/logos/logo.png" alt="EPDEOR Logo" class="img-fluid drop-shadow" style="max-height: 130px;">
    <br>
    <b class="text-guindo" style="font-size: 1.4rem; font-weight: 800; letter-spacing: 1px;">EPDEOR</b>
    <p style="font-size: 0.95rem; color: #666; margin-top: -5px; font-weight: 500;">Sistema de Arriendos</p>
  </div>

  <!-- Caja de Formulario -->
  <div class="card card-outline card-guindo" style="border-radius: 10px;">
    <div class="card-body login-card-body" style="border-radius: 10px;">

      <!-- FORMULARIO 1: USUARIO Y CONTRASEÑA -->
      <form id="FormLogin" onsubmit="event.preventDefault(); Login();">
        <p class="login-box-msg text-muted" style="font-size: 0.9rem;">Ingrese sus credenciales de acceso al sistema</p>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="txt_correo" placeholder="Usuario" required autocomplete="off" autofocus>
          <div class="input-group-append">
            <div class="input-group-text bg-white">
              <span class="fas fa-user text-guindo"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-4">
          <input type="password" class="form-control" name="txt_clave" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text bg-white">
              <span class="fas fa-lock text-guindo"></span>
            </div>
          </div>
        </div>
        
        <button type="submit" class="btn btn-guindo btn-block font-weight-bold py-2"><i class="fas fa-sign-in-alt mr-2"></i> INICIAR SESIÓN</button>
      </form>

      <!-- FORMULARIO 2: CÓDIGO OTP (Oculto inicialmente) -->
      <form id="FormOTP" onsubmit="event.preventDefault(); ValidarOTP();" style="display: none;">
        <p class="login-box-msg text-guindo font-weight-bold" style="font-size: 1rem;">Verificación de Seguridad</p>
        <p class="text-center text-muted" style="font-size: 0.85rem; line-height: 1.2;">Hemos enviado un código de 6 dígitos a su cuenta de WhatsApp.</p>
        
        <div class="input-group mb-4 mt-3">
          <input type="text" class="form-control text-center font-weight-bold" name="txt_otp" id="txt_otp" placeholder="• • • • • •" required autocomplete="off" maxlength="6" style="font-size: 1.8rem; letter-spacing: 8px;">
          <input type="hidden" name="id_usuario_tmp" id="id_usuario_tmp">
        </div>
        
        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2"><i class="fas fa-check-circle mr-2"></i> VERIFICAR CÓDIGO</button>
        <button type="button" class="btn btn-info btn-block mt-2 font-weight-bold py-2" onclick="ReenviarOTP()"><i class="fas fa-paper-plane mr-2"></i> Reenviar Código</button>
        <button type="button" class="btn btn-link btn-block text-muted mt-2" onclick="CancelarOTP()">Volver</button>
      </form>

    </div>
  </div>
</div>
<script src="<?php echo URL; ?>views/login/login.js"></script>
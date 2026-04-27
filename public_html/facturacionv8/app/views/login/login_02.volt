<style>
.forny-container {
    background-size: contain;
    display: block;
    align-items: center;
}

.forny-inner {
    display: block;
    height: 100vh;
    width: 100%;
}

.forny-two-pane {
    height: 100%;
    display: flex;
    flex-direction: row;
}

.forny-two-pane > div {
    flex: 1;
    background-color: white;
}

.forny-two-pane > div:first-child {
    display: flex;
    align-items: center;
}

.forny-two-pane > div:last-child {
    display: none;
    background: url(<?php echo $data_personalizacion["img_background_login"]; ?>) center bottom no-repeat
        #ffe0eb;
    background: url(<?php echo $data_personalizacion["img_background_login"]; ?>) center bottom no-repeat
       #ffe0eb;
}
.forny-form {
    padding: 10em 18em;
    background-color: #fff!important;
}
/* form */

::placeholder {
  font-size: 15px;
}
.input-group-addon:first-child {
    border: 0;
    background: #f5f7fa;
    color: #d8137f;
}
input.form-control {
    background: #f5f7fa;
    border-color: #f5f7fa;
}
@media (min-width: 768px) {
    .forny-container {
        background-color: hsla(216, 33%, 97%, 1);
        background-color: #f5f7fa;
    }

    .forny-two-pane > div:last-child {
        display: block;
    }
}
.g-recaptcha > div{
    margin: auto;
}
.btn-primary{
    background-color: #d8137f;
    border-color: #d8137f;
    color: #fff;
    border-radius: 20px;
}
.btn-primary:focus, .btn-primary.focus, .btn-primary:hover {
    background-color: #e63898!important;
    border-color: #e63898!important;
}
.btn-primary:active:hover, .btn-primary.active:hover, .open > .dropdown-toggle.btn-primary:hover, .btn-primary:active:focus, .btn-primary.active:focus, .open > .dropdown-toggle.btn-primary:focus, .btn-primary:active.focus, .btn-primary.active.focus, .open > .dropdown-toggle.btn-primary.focus {
    background-color: #e63898!important;
    border-color: #e63898!important;
}
.form-control:not(.border-bottom-1):not(.border-bottom-2):not(.border-bottom-3):focus {
    border-color: #d8137f;
    transition-timing-function: ease;
}
</style>
<div class="forny-container">
    <div class="forny-inner">
        <div class="forny-two-pane">
            <div>
                <div class="forny-form">
                    <div class="text-center forny-logo">
                        <img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo" width="250px" alt="">
                    </div>
                    <div class="text-center">
                        <h5 class="content-group">Iniciar Sesión</h5>
                        <p class="mb-10">Usa tus credenciales para acceder.</p>
                    </div>
                    <form  class="frm_login" action="/facturacionv8/login/session" method="post">
                        <div class="form-group">
                            <div class="form-group">
							
                                <div class="form-group input-group">
                                    <span class="input-group-addon input-eye" id="show-passwd" action="hide">	<i class="icon-envelop"></i></span>
									<input type="email" name="email" class="form-control input-login" placeholder="Email">
								</div>
							</div>
                        </div>
                        <div class="form-group password-field">
                            <div class="form-group">
								<div class="form-group input-group">
                                    <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
									<input type="password" name="password" id="contrasena" class="form-control input-login" placeholder="Contraseña">
								</div>
							</div>
                        </div>
    
    
                        <div class="row text-center">
                            <div class="col-md-12">
                                <?= $this->flashSession->output() ?>
                            </div>
                            <div class="col-md-12 text-center captcha" style="margin: auto;">
                                <div  class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="habilitar_login"></div>
                            </div>
                                <div class="col-md-12" style="margin-top: 15px;">
                                <div class="text-center sign-up">
                                    <?php 
                                    if(empty($data_empresa['url_politica_privacidad'])) {
                                    ?>
                                    <p class="ftz-17">Al Utilizar Nuestros Servicios Aceptas Nuestros <a href="/facturacionv8/terminoslegales/terminos_del_servicio" target="_blank">Términos y Condiciones y Política de Tratamiento de Datos.</a></p>
                                    <?php
                                    } else {
                                    ?>
                                    <p class="ftz-17">Al Utilizar Nuestros Servicios Aceptas Nuestros <a href="<?php echo $data_empresa['url_terminos_condiciones']; ?>" target="_blank">Términos y Condiciones</a> y <a href="<?php echo $data_empresa['url_politica_privacidad']; ?>" target="_blank">Nuestra Política de Privacidad</a></p>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-block btn-login legitRipple" disabled>Acceder al Sistema <i class="icon-circle-right2 position-right"></i></button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-center">
                                    <a class="ftz-17" href="/facturacionv8/login/recoverpassword">¿Olvidaste la Contraseña?</a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-center sign-up">
                                    <p class="ftz-17">¿No tienes cuenta? <a href="/facturacionv8/registro" target="_blank">Regístrate</a></p>
                                    <img src="https://arpsystem.com.pe/facturacionv8/img/verificacion.png" width="130px" class="img-fluid" alt="header-logo">
                                </div>
                            </div>
                        </div>
    
                        
                    </form>
                </div>
            </div>
            <!-- bg -->
            <div></div>
        </div>
    </div>
</div>
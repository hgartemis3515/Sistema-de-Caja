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
    background: url('<?php echo $data_personalizacion["img_background_register"]; ?>') center bottom no-repeat
        #ffe0eb;
    background: url('<?php echo $data_personalizacion["img_background_register"]; ?>') center bottom no-repeat
        #ffe0eb;
}
.forny-form {
    padding: 10em 12em;
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
@media (max-width: 360px) {
    .forny-two-pane {
        padding-top: 15em;
    }
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
.form-control:not(.border-bottom-1):not(.border-bottom-2):not(.border-bottom-3):focus {
    border-color: #d8137f;
    transition-timing-function: ease;
}
.btn-primary, .bg-primary{
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
                        <h5 class="content-group">Regístrate gratis</h5>
                        <p class="mb-10">Usa tus credenciales para unerte a nosotros.</p>
                    </div>
                    <form class="frm_singup" name="frm_singup" id="frm_singup" action="#" method="post">
                        <div class="margin_top_head">
                          
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-envelop"></i></span>
                                        <input type="email" name="email_login" class="form-control input-login" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-lock2"></i></span>
                                            <input type="password" name="password_login" id="contrasena_register" class="input-login form-control" placeholder="Contraseña">
                                            <span class="input-group-addon input-eye btn bg-primary" id="show-passwd2" action="hide"><i class="icon-eye-blocked"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-briefcase"></i></span>
                                        <input type="text" class="form-control input-control form-control-sm" name="ruc" id="ruc" placeholder="R.U.C">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-user"></i></span>
                                        <input type="text" class="form-control input-control form-control-sm" name="nombre_usuario" id="nombre_usuario" placeholder="Escribe Tu Nombre">
                                    </div>
                                </div>	
                                <div class="col-md-12">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-phone2"></i></span>
                                        <input type="text" class="form-control input-control form-control-sm" name="telefono" id="telefono" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label  class="label-form">
                                            <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                                            Ubigeo
                                        </label>
                                        <select class="js-example-basic-single" name="ubigeo" id="ubigeo">
                                            <option value="150131">15 Lima - 01 Lima  - 31 San Isidro</option>
                                            <?php
                                            /*
                                            foreach($lista_ubigeo as $ubigeo) {
                                                $texto_select = $ubigeo->departamento.' - '.$ubigeo->provincia.' - '.$ubigeo->distrito;
                                                echo "<option value='".$ubigeo->codigo_ubigeo."'>".$texto_select."</option>";
                                            }
                                            */
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 text-xs-center" style="margin-top: 10px; margin-bottom: 10px;text-align: center;">
                                    <div style="margin: 0 auto;" class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="enableBtn"></div>
                                </div>	
                            </div>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary btn-block btn_guardaruser legitRipple" disabled>Registrar <i class="icon-circle-right2 position-right"></i></button>
                            </div>
                            <div class="text-center sign-up">
                                <p class="ftz-17">¿Ya tienes cuenta? <a href="/facturacionv8/login"  target="_blank" class="btn_login">Inicia sesión</a></p>
                            </div>
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
                    </form>
                </div>
            </div>
            <!-- bg -->
            <div></div>
        </div>
    </div>
</div>
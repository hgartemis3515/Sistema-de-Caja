<style>
/** Login 28 start **/
a{
    color: #39daff;
}
p a{
    color: #39daff!important;
}
body{
    background: #151a22;
}
.login-28 {
    top: 0;
    width: 100%;
    bottom: 0;
    min-height: 100vh;
    z-index: 999;
    opacity: 1;
    position: relative;
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 0;
    
}

.login-28 .container{
    max-width: 1300px;
}

.login-28 h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: 'Jost', sans-serif;
}

.login-28 .login-inner-form {
    color: #272323;
    text-align: center;
}

.login-28 .col-pad-0 {
    padding: 0;
}

.login-28 .login-inner-form .details p {
    color: #403838;
    font-weight: 400;
    font-size: 16px;
}

.login-28 .login-inner-form .details img {
    height: 50px;
    margin-bottom: 15px;
}

.login-28 .login-inner-form .details p a {
    margin-left: 3px;
    color: #403838;
}

.login-28 .login-inner-form .details p {
    margin-bottom: 0;
}

.login-28 .login-inner-form .details {
    padding: 50px 0 50px 100px;
}
.g-recaptcha > div {
        margin: auto;
    }
.login-28 .bg-img {
    background-size: cover;
    width: 400px;
    padding: 50px;
    background: #39daff;
    margin: 30px 0;
    z-index: 999;
    position: absolute;
    top: 50%;
    right: 0;
    transform: translate(0%, -50%);
}

.login-28 .login-box-12 {
    max-width: 100%;
    background: rgba(0, 0, 0, 0.04) url(<?php echo $data_personalizacion["img_background_register"]; ?>) top left repeat;
    background-size: cover;
    top: 0;
    bottom: 0;
    opacity: 1;
    text-align: center;
    margin-right: 100px;
}

.login-28 .none-2 {
    display: none;
}

.login-28 .login-inner-form h3 {
    margin: 0 0 30px;
    font-size: 25px;
    font-weight: 400;
    color: #313131;
}

.login-28 .login-inner-form .form-group {
    margin-bottom: 25px;
}

.login-28 .login-inner-form .input-text {
    outline: none;
    width: 100%;
    padding: 10px 20px;
    font-size: 16px;
    outline: 0;
    font-weight: 500;
    color: #717171;
    height: 55px;
    border-radius: 3px;
    border: 1px solid #dbdbdb;
}

.login-28 .login-inner-form .btn-md {
    cursor: pointer;
    height: 55px;
    color: #fff;
    padding: 13px 50px 12px 50px;
    font-size: 17px;
    font-weight: 400;
    font-family: 'Jost', sans-serif;
    border-radius: 3px;
}

.login-28 .bg-img .social-list li {
    display: inline-block;
    font-size: 16px;
}

.login-28 .bg-img .logo {
    height: 40px;
    margin-bottom: 20px;
}

.login-28 .bg-img p {
    font-size: 15px;
    color: #fff;
    margin-bottom: 25px;
}

.login-28 .bg-img h3{
    color: #fff;
    margin-bottom: 20px;
}

.login-28 .bg-img .btn-sm {
    padding: 6px 20px 6px 20px;
    font-size: 13px;
}

.login-28 .bg-img .social-list {
    padding: 0;
    margin: 0;
}

.login-28 .bg-img .social-list li a {
    font-size: 17px;
    color: #fff;
    border-radius: 3px;
    display: inline-block;
    width: 45px;
    height: 45px;
    line-height: 45px;
    text-align: center;
    background: #2ec7ea;
}

.login-28 .bg-img .social-list li a:hover {
    color: #2ec7ea;
    background: #fff;
}

.login-28 .login-inner-form input[type=checkbox], input[type=radio] {
    margin-right: 3px;
}

.login-28 .login-inner-form button:focus {
    outline: none;
    outline: 0 auto -webkit-focus-ring-color;
}

.login-28 .login-inner-form .btn-theme.focus, .btn-theme:focus {
    box-shadow: none;
}

.login-28 .login-inner-form .btn-theme {
    background: #39daff;
    border: none;
    color: #fff;
}

.login-28 .login-inner-form .btn-theme:hover {
    background: #30cef3;
    box-shadow: 0 0 35px rgba(0, 0, 0, 0.1);
}

.login-28 .login-inner-form .terms {
    margin-left: 3px;
}

.login-28 .login-inner-form .checkbox {
    margin-bottom: 25px;
    font-size: 16px;
}

.login-28 .login-inner-form .form-check {
    float: left;
    margin-bottom: 0;
}

.login-28 .login-inner-form .form-check a {
    color: #717171;
    float: right;
}

.login-28 .login-inner-form .form-check-input {
    position: absolute;
    margin-left: 0;
}

.login-28 .login-inner-form .form-check label::before {
    content: "";
    display: inline-block;
    position: absolute;
    width: 17px;
    height: 17px;
    margin-left: -25px;
    border: 1px solid #c5c3c3;
    border-radius: 3px;
    background-color: #fff;
    top: 3px;
}

.login-28 .login-inner-form .form-check-label {
    padding-left: 25px;
    margin-bottom: 0;
    font-size: 16px;
    color: #403838;
}

.login-28 .login-inner-form .checkbox-theme input[type="checkbox"]:checked + label::before {
    background-color: #ff574d;
    border-color: #ff574d;
}

.login-28 .login-inner-form input[type=checkbox]:checked + label:before {
    font-weight: 300;
    color: #f3f3f3;
    line-height: 15px;
    font-size: 14px;
    content: "\2713";
}

.login-28 .login-inner-form input[type=checkbox], input[type=radio] {
    margin-top: 4px;
}

.login-28 .login-inner-form .checkbox a {
    font-size: 16px;
    color: #403838;
    float: right;
}

/** MEDIA **/
@media (max-width: 992px) {
    .login-28 .none-992 {
        display: none;
    }

    .login-28 .pad-0 {
        padding: 0;
    }

    .login-28 .login-box-12 {
        margin: 0 auto;
        max-width: 600px;
    }

    .login-28 .login-inner-form .details {
        padding: 60px;
    }
}

@media (min-width: 800px) {
    .login-28 .login-inner-form .details {
        padding: 50px 30px;
    }
    .container {
        width: 1000px;
    }
}

@media (min-width: 360px) {
    .login-28{
        display: initial;
    }
    .container {
        padding: 10em 1em;
    }
    
    .login-28 .login-inner-form .details {
        padding: 70px 20px;
    }
}
.form-control:not(.border-bottom-1):not(.border-bottom-2):not(.border-bottom-3):focus {
    border-color: #39daff;
    transition-timing-function: ease;
}
</style>
<div class="login-28">
    <div class="container">
        <div class="col-md-12 pad-0">
            <div class="row login-box-12">
                <div class="col-lg-7 col-sm-8 col-pad-0 align-self-center col-pad-0 align-self-center">
                    <div class="login-inner-form">
                        <div class="details">
                            <a href="/facturacionv8/login/login_07">
                                <img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo" alt="">
                            </a>
                            <h3 class="content-group">Regístrate gratis</h3>
                            <form class="frm_singup" name="frm_singup" id="frm_singup" action="#" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control input-login form-control-sm" name="email_login" id="email_login" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="password" name="password_login" id="contrasena_register" class="input-login form-control" placeholder="Contraseña">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control input-control form-control-sm" name="ruc" id="ruc" placeholder="R.U.C">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control input-control form-control-sm" name="nombre_usuario" id="nombre_usuario" placeholder="Escribe Tu Nombre">
                                        </div>
                                    </div>	
                                    <div class="col-md-12">
                                        <div class="form-group">
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
                                    <div class="col-md-12 text-xs-center" style="margin-top: 10px; margin-bottom: 10px;">
                                        <div style="margin: 0 auto;" class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="enableBtn"></div>
                                    </div>	
                                </div>
                                <div class="form-group text-center">
                                    <button type="button" class="btn-theme btn-block btn-md  btn-login btn_guardaruser legitRipple" disabled>Registrar <i class="icon-circle-right2 position-right"></i></button>
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
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12 col-pad-0 bg-img align-self-center none-992">
                    <h3>Welcome</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type</p>
                    <ul class="social-list clearfix">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
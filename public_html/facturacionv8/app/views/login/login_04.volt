<!-- ID: 04 -->
<style>
.align-self-center {
    -ms-flex-item-align: center !important;
    align-self: center !important;
}
/** Login 5 start **/
.login-5 {
    min-height: 100vh;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 25px 0;
}

.login-5 h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: 'Jost', sans-serif;
}

.login-5 .login-box {
    background: #fff;
    border-radius: 20px;
    margin: 0 auto;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    background: rgba(0, 0, 0, 0.04) url(<?php echo $data_personalizacion["img_background_login"]; ?>) top left repeat;
    background-size: cover;
    display: flex;
}

.login-5 .form-section {
    text-align: center;
    padding: 70px 70px;
    background: #fff;
    border-radius: 10px 0 0 10px;
}

.login-5-bg{
    background: #f7f7f7;
}

.login-5 .pad-0{
    padding: 0;
}

.login-5 .info{
    padding: 30px;
    text-align: center;
    border: solid 5px rgb(255 255 255 / 20%);
    max-width: 300px;
    margin: 0 auto;
}

.login-5 .info h3{
    font-size: 18px;
    text-transform: uppercase;
    margin-bottom: 20px;
    font-weight: 500;
    color: #fff;
    font-family: 'Jost', sans-serif;
}

.login-5 .form-section p{
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 500;
    color: #717171;
}

.login-5 .form-section p a{
    font-weight: 500;
    color: #717171;
}

.login-5 .form-section ul{
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
}

.login-5 .form-section .social-list li {
    display: inline-block;
    margin-bottom: 5px;
}

.login-5 .logo-2 img{
    height: 50px;

}

.login-5 .form-section .thembo{
    margin-left: 4px;
}

.login-5 .form-section h3 {
    text-align: center;
    margin: 0 0 25px;
    font-size: 25px;
    font-weight: 400;
    font-family: 'Jost', sans-serif;
    color: #313131;
}
.login-5 .form-section .input-text {
    padding: 10px 25px;
    font-size: 16px;
    outline: none;
    height: 50px;
    background: rgba(23, 23, 23, 0.72);
    color: #616161;
    border-radius: 50px;
    font-weight: 500;
    border: 1px solid transparent;
    background: #fff;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.login-5 .form-section .form-group {
    margin-bottom: 25px;
}
/*
.login-5 .form-section .form-box {
    float: left;
    width: 100%;
    text-align: left;
    position: relative;
}
*/
.form-group {
    margin-bottom: 20px;
    position: initial;
}
.login-5 .form-section .form-box input {
    float: left;
    width: 100%;
    margin-bottom: 1em;
}

.login-5 .form-section .input-text {
    padding: 10px 25px;
    font-size: 16px;
    outline: none;
    height: 50px;
    background: rgba(23, 23, 23, 0.72);
    color: #616161;
    border-radius: 50px;
    font-weight: 500;
    border: 1px solid transparent;
    background: #fff;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.login-5 .form-section .checkbox .terms{
    margin-left: 3px;
}

.login-5 .form-section .btn-md {
    cursor: pointer;
    padding: 13px 50px 12px 50px;
    font-size: 17px;
    font-weight: 400;
    font-family: 'Jost', sans-serif;
    border-radius: 50px;
}

.login-5 .form-section input[type=checkbox], input[type=radio] {
    margin-right: 3px;
}

.login-5 .form-section button:focus {
    outline: none;
    outline: 0 auto -webkit-focus-ring-color;
}

.login-5 .form-section .btn-theme.focus, .btn-theme:focus {
    box-shadow: none;
}

.login-5 .form-section .btn-theme {
    background: #ff2f2f;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    border: none;
    color: #fff;
}

.login-5 .form-section .btn-theme:hover {
    background: #ec2727;
}

.login-5 .none-2{
    display: none;
}


.login-5 .form-section .terms{
    margin-left: 3px;
}

.login-5 .btn-section {
    margin-bottom: 25px;
    display: inline-block;
    background: #fff;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.login-5 .btn-section .btn-1 {
    border-radius: 0;
    border-right: solid 2px #e6e6e6;
}

.login-5 .btn-section .link-btn {
    font-size: 16px;
    float: left;
    background: #fff;
    font-weight: 400;
    text-align: center;
    text-decoration: none;
    text-decoration: blink;
    line-height: 35px;
    width: 110px;
    color: #505050;
    font-family: 'Jost', sans-serif;
}

.login-5 .btn-section .link-btn:hover{
    color: #ff2f2f;
}

.login-5 .btn-section .active-bg {
    color: #ff2f2f;
}

.login-5 .btn-section .btn-2 {
    border-radius: 0;
}

.login-5 .form-section .checkbox {
    font-size: 15px;
}

.login-5 .form-section .form-check{
    float: left;
    margin-bottom: 0;
}

.login-5 .form-section .form-check a {
    color: #717171;
    float: right;
}

.login-5 .form-section .form-check-input {
    position: absolute;
    margin-left: 0;
}

.login-5 .form-section .form-check label::before {
    content: "";
    display: inline-block;
    position: absolute;
    width: 18px;
    height: 18px;
    top: 2px;
    margin-left: -25px;
    border: 1px solid #c5c3c3;
    border-radius: 3px;
    background-color: #fff;
}

.login-5 .form-section .form-check-label {
    padding-left: 25px;
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 500;
    color: #717171;
}
.form-control:not(.border-bottom-1):not(.border-bottom-2):not(.border-bottom-3):focus {
    border-color: #ff2f2f;
    transition-timing-function: ease;
}
.login-5 .form-section .checkbox-theme input[type="checkbox"]:checked + label::before {
    background-color: #ff2f2f;
    border-color: #ff2f2f;
}

.login-5 .form-section input[type=checkbox]:checked + label:before {
    font-weight: 300;
    color: #f3f3f3;
    line-height: 15px;
    font-size: 14px;
    content: "\2713";
}

.login-5 .form-section input[type=checkbox], input[type=radio] {
    margin-top: 4px;
}

.login-5 .form-section a.forgot-password {
    font-size: 16px;
    color: #616161;
    float: right;
    line-height: 50px;
}

.login-5 .social-list a {
    width: 45px;
    height: 45px;
    line-height: 45px;
    text-align: center;
    display: inline-block;
    font-size: 19px;
    margin: 2px;
    border-radius: 5%;
    background: #fff;
    box-shadow: 0 0 35px rgba(0, 0, 0, 0.1);
    -webkit-transition: all 0.8s;
    transition: all 0.8s;
}

.login-5 .login-box:hover .social-list a {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
}

.login-5 .social-list a:hover{
    color: #fff;
}
.g-recaptcha > div {
    margin: auto;
}
/** Social media **/
.login-5 .facebook-bg{
    color: #4867aa;
}

.login-5 .facebook-bg:hover {
    background: #4867aa;
}

.login-5 .twitter-bg {
    color: #33CCFF;
}

.login-5 .twitter-bg:hover {
    background: #33CCFF;
}
.login-5 .tiktok-bg:hover {
    background: #a3a3a3;
}
.login-5 .google-bg {
    color: #db4437;
}
.login-5 .google-bg:hover {
    background: #db4437;
}
.login-5 .instagram-bg {
    color: #b22fa3;
    transition: all 1s;
}

.login-5 .instagram-bg:hover {
    -webkit-appearance: none;
    background: -webkit-gradient(to right, #b22fa3 0%, #e74b4c 50%, #f2cf6f)!important;
    background: linear-gradient(to right, #b22fa3 0%, #e74b4c 50%, #f2cf6f)!important;
    color: #fff;
    transition: all 1s;
}

@keyframes gradient {
    0% {
      background-position: 0% 80%;
    }
    100% {
      background-position: 100%;
    }
  }
  @-webkit-keyframes fadeInDown{
      0%
    {
        opacity:0;
        -webkit-transform:translate3d(0,-10%,0);
        transform:translate3d(0,-10%,0)}
        to{
            opacity:1;
            -webkit-transform:none;transform:none}
        }
    @keyframes fadeInDown{
        0%{
            opacity:0;-webkit-transform:translate3d(0,-10%,0);
        transform:translate3d(0,-10%,0)
    }
        to{
            opacity:1;-webkit-transform:none;transform:none
        }
    }
    .fadeInDown{
        -webkit-animation-name:fadeInDown;
        animation-name:fadeInDown
}


.login-5 .linkedin-bg {
    color: #2392e0;
}

.login-5 .linkedin-bg:hover {
    background: #1c82ca;
}
.btn.disabled, .btn[disabled], fieldset[disabled] .btn {
    opacity: 0.4!important;
    
}
@media (max-width: 992px) {
    .login-5 .form-section {
        width: 100%;
    }
    
    .none-992{
        display: none!important;
    }

    .login-5 .login-box {
        max-width: 500px;
        margin: 0 auto;
        padding: 0;
    }
}

@media (max-width: 768px) {
    .login-5 .form-section{
        padding: 50px 30px;
    }
}
</style>
<div class="login-5">
    <div class="container">
        <div class="row login-box">
            <div class="col-lg-6 align-self-center pad-0">
                <div class="form-section align-self-center">
                    <h3>Inicia Sesión</h3>
                    <div class="btn-section clearfix">
                        <a href="login-5.html" class="link-btn active btn-1 active-bg">Login</a>
                        <a href="register-5.html" class="link-btn btn-2 default-bg">Register</a>
                    </div>
                    <div class="clearfix"></div>
                    <form  class="frm_login" action="/facturacionv8/login/session" method="post">
                        <div class="login-form">
                            <div class="row form_content">
                              
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control input-login input-text" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="password" name="password" id="contrasena" class="form-control input-login input-text" placeholder="Contraseña">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <?= $this->flashSession->output() ?>
                                </div>
                                <div class="col-md-12 text-center captcha">
                                    <div style="margin: 0 auto;" class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="habilitar_login"></div>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;">
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn-md btn-theme float-left btn-login legitRipple" disabled>Acceder al Sistema <i class="icon-circle-right2 position-right"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <a class="ftz-17" href="/facturacionv8/login/recoverpassword">¿Olvidaste la Contraseña?</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="text-center sign-up">
                                        ¿No tienes cuenta? <a href="/facturacionv8/registro" target="_blank">Regístrate</a>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 15px;">
                                    <div class="text-center sign-up">
                                        <?php 
                                        if(empty($data_empresa['url_politica_privacidad'])) {
                                        ?>
                                        Al Utilizar Nuestros Servicios Aceptas Nuestros <a class="ftz-17" href="/facturacionv8/terminoslegales/terminos_del_servicio" target="_blank">Términos y Condiciones así como nuestra Política de Tratamiento de Datos.</a>
                                        <?php
                                        } else {
                                        ?>
                                        Al Utilizar Nuestros Servicios Aceptas Nuestros <a class="ftz-17" href="<?php echo $data_empresa['url_terminos_condiciones']; ?>" target="_blank">Términos y Condiciones</a> así como <a href="<?php echo $data_empresa['url_politica_privacidad']; ?>" target="_blank">Nuestra Política de Privacidad</a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 bg-color-15 align-self-center pad-0 none-992 bg-img">
                <div class="info clearfix">
                    <div class="logo-2">
                        <a href="login-5.html">
                            <img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo"  alt="">
                        </a>
                    </div>
                    <h3 style="display:none;">Bienvenido a <?php echo $data_empresa['url_domain']; ?></h3>
                    <div class="social-list">                    
 
                        <?php 
                           
                        if(isset($patrocinador->url_facebook) && !empty($patrocinador->url_facebook)) { 
                            echo ' <a href="'.$patrocinador->url_facebook.'" target="_blank" class="facebook-bg">
                            <i class="fa fa-facebook"></i>
                            </a>';
                         } 
                         if(isset($patrocinador->url_youtube) && !empty($patrocinador->url_youtube)) { 
                             echo '  <a href="'.$patrocinador->url_youtube.'" target="_blank" class="google-bg">
                                <i class="fa fa-youtube"></i>
                            </a>';
                         } 
                         if(isset($patrocinador->url_twitter) && !empty($patrocinador->url_twitter)) { 
                             echo '<a href="'.$patrocinador->url_twitter.'" target="_blank" class="twitter-bg">
                            <i class="fa fa-twitter"></i>
                            </a>
                          ';
                         } 
                          if(isset($patrocinador->url_instagram) && !empty($patrocinador->url_instagram)) { 
                            echo ' <a href="'.$patrocinador->url_instagram.'" target="_blank" class="instagram-bg">
                                <i class="fa fa-instagram"></i>
                            </a>';
                           
                         } 
                         if(isset($patrocinador->url_tiktok) && !empty($patrocinador->url_tiktok)) { 
                            echo ' <a href="'.$patrocinador->url_tiktok.'" target="_blank" class="tiktok-bg">
                                <img src="/facturacionv8/img/icons/tik-tok-color.png" width="15px" alt="">
                            </a>';
                            
                         } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
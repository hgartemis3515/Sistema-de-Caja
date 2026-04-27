<style>
body{
    background-color: #0d1a2e;
}
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
    margin: 0 auto;
    box-shadow: 0 0.925rem 5.5rem 0 rgb(0 0 0 / 80%);
    background: rgba(0, 0, 0, 0.04) url(<?php echo $data_personalizacion["img_background_register"]; ?>) top left repeat;
    background-size: cover;
    display: flex;
    position: relative;
    z-index: 1;
}
.login-5 .login-box::after {
    background: linear-gradient(135deg,#ed6db1,#425be4);
    opacity: 0.85;
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: -1;
}
.login-5 .form-section {
    padding: 70px 70px;
    background: #0d1a2e;
    color: #fff;
}

.login-5-bg{
    background: #0d1a2e;
}

.login-5 .pad-0{
    padding: 0;
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
    color: #fff;
}
.login-5 .form-section .input-text {
    background-color: transparent;
    border: none;
    border-bottom: 0.0625rem solid #253143;
    border-radius: 0;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.6;
    color: #ffffff;
    padding: 0.75rem 1rem 0.75rem 2rem;
    height: 3.225rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}

.login-5 .form-section .form-group {
    margin-bottom: 25px;
}
.form-group:focus-within label {
color: #425be4;
transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}
.form-group {
    margin-bottom: 20px;
    position: initial;
}
.login-5 .form-section .form-box input {
    float: left;
    width: 100%;
    margin-bottom: 1em;
}
::placeholder {
    font-size: 15px;
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

.login-5 .google-bg {
    color: #db4437;
}

.login-5 .google-bg:hover {
    background: #db4437;
}

.login-5 .linkedin-bg {
    color: #2392e0;
}

.login-5 .linkedin-bg:hover {
    background: #1c82ca;
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
.btn.bg-indigo{
    display: block;
    width: 100%;
    min-width: 9.25rem;
    font-size: 0.875rem;
    line-height: 1.2;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    padding: 0.875rem 0.875rem;
    border-radius: 0.25rem;
    color: #ffffff;
    background-color: #425be4;
    border: 1px solid #425be4;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}
.social .btn {
border-color: #3d4757;
color: #c5c8cd;
background-color: transparent;
border-radius: 20em;
}

.nm-btn {
min-width: 9.25rem;
font-weight: 600;
font-size: 0.875rem;
line-height: 1.2;
text-align: center;
vertical-align: middle;
user-select: none;
padding: 1.5em;
border-radius: 0.25rem;
color: #ffffff;
background-color: #425be4;
border: 1px solid #425be4;
transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}
/* FACEBOOK */
.btn-facebook:focus {
border-color: #3d4757 !important;
color: #c5c8cd !important;
background-color: transparent !important;
}

.btn-facebook:hover {
color: #fff !important;
background-color: #3b5998 !important;
border-color: #3b5998 !important;
}

.btn-facebook:active {
color: #fff !important;
background-color: #2d4373 !important;
border-color: #2d4373 !important;
}


/* GOOGLE */
.btn-google:focus {
border-color: #3d4757 !important;
color: #c5c8cd !important;
background-color: transparent !important;
}

.btn-google:hover {
color: #fff !important;
background-color: #e6162d !important;
border-color: #e6162d !important;
}

.btn-google:active {
color: #fff !important;
background-color: #cf1026 !important;
border-color: #cf1026 !important;
}
.mt-5{
margin-top: 2em!important;
}  
/* Section bg */
.overlay{
display: flex;
flex-direction: column;
align-items: center;
justify-content: center;
}
.hero-item {
min-width: 80%;
text-align: left;
}

.hero-item-1 {
flex-grow: 1;
display: flex;
flex-direction: column;
justify-content: center;
}

.hero-item h2 {
color: #ffffff;
font-size: 3rem;
font-weight: 600;
}

.hero-item img {
height: 50px;
margin-top: 3rem;
}

ul {
justify-content: left; 
width: 80%; 
list-style: none; 
flex-wrap: wrap; 
display: flex; 
padding: 0; 
margin: 0; 
text-align: left; 
color: #ffffff; 
font-size: 1rem; 
font-weight: 600;
margin-bottom: 3rem;
margin-top: 3rem;
}


li {
flex-basis: 0%;	
flex-shrink: 1;	
margin: 2px;
margin-right: 3rem;
position: relative;
}

li > a, li > a:hover {
color: #ffffff; 
}

li > a::after {
color: #ffffff; 
content: "";
position: absolute;
top: 100%;
    height: 2px;
    width: 100%;
    left: 0%;
background-color: transparent;
transition: all 0.15s ease-in-out;
}

li:hover > a::after {
content: "";
position: absolute;
top: 100%;
    height: 2px;
    width: 100%;
    left: 0%;
    background-color:#ffffff;
}

h2 {
font-size: 1.5rem;
font-weight: 600;
margin-top: 0;
}

h2.large {
font-size: 1.75rem;
margin-bottom: 0.5rem;
font-weight: 600;
}

p {
margin-top: 0;
font-size: 0.875rem;
font-weight: 400;
margin-bottom: 0rem;
}

div.header {
margin-bottom: 3rem;
}

p.h2 {
font-size: 1.25rem;
margin-bottom: 0.5rem;
font-weight: 600;
text-align: left;
color: #ffffff;
}

.header p:last-child {
color: #8f959f;
}

</style>
<div class="login-5">
    <div class="container">
        <div class="row login-box">
            <div class="col-lg-6 bg-color-15 align-self-center pad-0 none-992 bg-img">
                <div class="info clearfix">
                    <div class="overlay">
                        <div class="hero-item">
                            <a href="../../index.html" aria-label="Nimoy">
                                <img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo"  alt="">
                            </a>
                        </div>
                        <div class="hero-item hero-item-1">
                            <h2>Go all the way.</h2>
                            <h2>Don't give up. Ever.</h2>
                            <h2>It's that simple.</h2>
                        </div>	
                        <ul class="hero-item">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Contacts</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 align-self-center pad-0">
                <div class="form-section align-self-center">
                    <h3>Regístrate gratis</h3>
                    <form class="frm_singup" name="frm_singup" id="frm_singup" action="#" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form">
                                        Email
                                    </label>
                                    
                                    <input type="email" class="form-control input-login input-text form-control-sm" name="email_login" id="email_login" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form">
                                        Contraseña
                                    </label>
                                    <input type="password" name="password_login" id="contrasena_register" class="input-login input-text form-control" placeholder="Contraseña">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form">
                                        R.U.C de Tu Empresa
                                    </label>
                                    <input type="text" class="form-control  input-text" name="ruc" id="ruc" placeholder="R.U.C">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form"><i class="icon-user mr-2"></i> 
                                        Aquí Tu Nombre
                                    </label>
                                    <input type="text" class="form-control  input-text" name="nombre_usuario" id="nombre_usuario" placeholder="Escribe Tu Nombre">
                                </div>
                            </div>	
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label  class="label-form">
                                        Escribe tu Número de Teléfono
                                    </label>
                                    <input type="text" class="form-control  input-text" name="telefono" id="telefono" placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="col-md-6" style="display: none;">
                                <div class="form-group">
                                    <label  class="label-form">
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
                            <button type="button" class="btn bg-indigo btn-login btn_guardaruser legitRipple" disabled>Registrar <i class="icon-circle-right2 position-right"></i></button>
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
    </div>
</div>
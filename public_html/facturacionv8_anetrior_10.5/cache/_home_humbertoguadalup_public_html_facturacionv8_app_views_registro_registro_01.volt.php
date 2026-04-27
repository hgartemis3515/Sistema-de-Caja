<style>
a{
    color: #3F51B5;
}

h1{
    padding: 0;
    margin: 0;
}
html, body {
    height: 100%;
    margin: 0;
    
}
body{

    height: 100%;
    max-width: 100%;
    background-image: url(<?php echo $data_personalizacion["img_background_register"]; ?>);
    background-position: center center;
    background-repeat: no-repeat; 
    background-attachment: fixed; 
    background-size: cover; 
    width: 100%; 
    height: 100%;  

    color: #000;
}
    /*
Buttons
======================*/
.btn.bg-indigo {
    -webkit-appearance: none;
    background: -webkit-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
    background: linear-gradient(to right, #7880f0 0%, #b4b9ff 50%, #3f51b5);
    background-size: 500%;
    border: none;
    border-radius: 5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    color: #fff;
    cursor: pointer;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    outline: none;
    -webkit-tap-highlight-color: transparent;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.btn.bg-indigo:hover{
    animation-name: gradient;
    -webkit-animation-name: gradient;
    animation-duration: 2s;
    -webkit-animation-duration: s;
    animation-iteration-count: 1;
    -webkit-animation-iteration-count: 1;
    animation-fill-mode: forwards;
    -webkit-animation-fill-mode: forwards;
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
.form-control {
    border-radius: 15px;
    border: 1px solid #ddd;
}
.small, small {
    font-size: 14px;
    font-weight: 400;
}
.img-logo{
    margin-bottom: 2em;

}
.img-logo img{
    max-width: 100%;
    height: auto;
}

.input-eye{
    background: transparent;
    color: #3F51B5;
    border: 1px solid #ddd;
    border-bottom-left-radius: 0px!important;
    border-top-left-radius: 0px!important;
    border-bottom-right-radius: 15px!important;
    border-top-right-radius: 15px!important;
}
.lead{
    color: #fff;
}
.login-form{
    z-index: 2;
}
.logo{
    width: 250px
}
.panel {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 50px 0 50px 0;
}

.page-content {
    position: relative;
}
.page-content::before {
    position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        width: 100%;
        margin: 0 auto;
}
.shape-bottom img.bottom-shape {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    margin: 0 auto;
}
.text-xs-center {
    text-align: center;
}

.g-recaptcha {
    display: inline-block;
}
.text-main-welcome{
    padding: 1em;
}
@media(min-width: 900px){
    .text-main-welcome{
        margin-top: 4em;
    }
    .content{
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: auto;
    }
    .login-form{
        width: 500px;
    }
}
@media(min-width: 700px) and (max-width: 1025px){
    .login-form{
        width: 500px;
    }
    .content{
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: auto;
    }
}
@media(min-width: 1500px){
    .text-main-welcome{
        margin-top: 10em;
    }
    .login-form{
        width: 500px;
    }
}
</style>
<div class="page-container">
    <div class="page-content">
        <div class="content-wrapper">
            <div class="text-center text-main-welcome">
                <div class="img-logo" style="<?php if($data_empresa['url_domain'] != 'facturalaya.com'){ echo 'display: none;'; } ?>">
                    <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/hero-logo2.png"  class="logo-info" width="500px" alt="">
                </div>
                <h1 class="text-white">¡Bienvenido a <?php echo ucwords($data_empresa['url_domain']); ?>!</h1>
                <img src="/facturacionv8/img/sub.png"  class="subo"  alt="">
                <p class="lead">
                    ¡Inicia Hoy Mismo con la Facturación Electrónica y <br>Sin Tediosos Contratos que te Amarren!
                </p>
            </div>
            <div class="content">
                <form class="frm_singup" name="frm_singup" id="frm_singup" action="#" method="post">
                    <div class="panel panel-body login-form">
                        <div class="text-center">
                            <img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo" alt="">
                            <h5 class="content-group">Regístrate gratis</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-envelop mr-2"></i>
                                        Email
                                    </label>
                                    
                                    <input type="email" class="form-control input-login form-control-sm" name="email_login" id="email_login" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-lock2 mr-2"></i> 
                                        Contraseña
                                    </label>
                                    <div class="form-group input-group">
                                        <input type="password" name="password_login" id="contrasena_register" class="input-login form-control" placeholder="Contraseña">
                                        <span class="input-group-addon input-eye btn bg-indigo" id="show-passwd2" action="hide"><i class="icon-eye-blocked"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-briefcase mr-2"></i> 
                                        R.U.C de Tu Empresa
                                    </label>
                                    <input type="text" class="form-control input-control form-control-sm" name="ruc" id="ruc" placeholder="R.U.C">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-user mr-2"></i> 
                                        Aquí Tu Nombre
                                    </label>
                                    <input type="text" class="form-control input-control form-control-sm" name="nombre_usuario" id="nombre_usuario" placeholder="Escribe Tu Nombre">
                                </div>
                            </div>	
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label  class="label-form">
                                        <i class="icon-phone2 mr-2"></i> 
                                        Escribe tu Número de Teléfono
                                    </label>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="shape-bottom">
        <img src="/facturacionv8/img/hero-shape-bottom.svg" alt="shape" class="bottom-shape img-fluid">
    </div>
</div>
    
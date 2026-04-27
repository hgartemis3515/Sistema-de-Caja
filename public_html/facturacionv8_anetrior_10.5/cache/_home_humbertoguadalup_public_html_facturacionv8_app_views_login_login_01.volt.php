<style>
a{
    color: #3F51B5;
}


.page-container::before{ 
content: "";
    position: absolute;
    top: 0; 
    left: 0;
    width: 100%; 
    height: 100%;  
    /* opacity: .1;  */
    z-index: -1;
    background-image: url(<?php echo $data_personalizacion["img_background_login"]; ?>);
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed; 
    background-size: cover;
}

.input-login,
.input-control{
    border: 0;
    border-bottom: 1.3px solid #ddd;
}
.input-eye{
    background: transparent;
    color: #3F51B5;
    border: none;
}
.logo{
    width: 250px
}
.panel {
        border-radius: 2rem;
        border: 0px;
        box-shadow: rgba(33,33,33,.08) 0 4px 24px 5px;
}
.page-container {
    width: 100%;
    display: table;
    table-layout: fixed;
    position: relative;
}

.login-form{
    border-radius: 2rem;
    border: 0px;
    box-shadow: rgba(33,33,33,.08) 0 4px;
    margin-bottom: 20px;
    color: #333333;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid transparent;
}
.g-recaptcha {
    display: inline-block;
}
@media(max-width: 600px){
    .content:first-child {
    padding: 7em 2.3em;
}
    .g-recaptcha {
    transform:scale(0.78);
    -webkit-transform:scale(0.78);
    transform-origin: ;-webkit-transform-origin: ;
    }
}
@media only screen and (min-width: 600px) {
    .content{
        margin-top:4em;
    }
    .login-form{
        width: 450px;
    }
    .content{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
}
@media only screen and (min-width: 768px) {
    .login-form{
        width: 400px;
    }
}



.text-center {
    text-align: center;
}
.captcha{
    display: flex;
    justify-content: center;
    align-items: center;
    margin: .5em 0;
}


</style>
<div class="page-container">
    <div class="content">
        <form  class="frm_login" action="/facturacionv8/login/session" method="post">
            <div class="login-form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo" alt="">
                            <h5 class="content-group">Iniciar Sesión</h5>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="label-form">
                                <i class="icon-envelop mr-2"></i>
                                Email
                            </label>
                            <input type="email" name="email" class="form-control input-login" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="label-form">
                                <i class="icon-lock2 mr-2"></i> 
                                Contraseña
                            </label>
                            <div class="form-group input-group">
                                <input type="password" name="password" id="contrasena" class="form-control input-login" placeholder="contrasena">
                                <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?= $this->flashSession->output() ?>
                    </div>
                    <div class="col-md-12 text-center captcha">
                        <div style="margin: 0 auto;" class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="habilitar_login"></div>
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
                            <button type="submit" class="btn bg-indigo btn-login legitRipple" disabled>Acceder al Sistema <i class="icon-circle-right2 position-right"></i></button>
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
                    <!-- <div class="col-md-12">
                        <div class="text-center sign-up">
                            <p class="ftz-17">Al Utilizar Nuestros Servicios Aceptas Nuestros <a href="/facturacionv8/terminoslegales/terminos_del_servicio" target="_blank">Términos y Condiciones y Política de Tratamiento de Datos.</a></p>
                        </div>
                    </div> -->
                </div>
            </form>
        </div>
    </div>
</div>
<style>
a{
    color: #0052ea;
}
.page-container {
    width: 100%;
    display: flex;
    table-layout: fixed;
    position: relative;
    justify-content: center;
    align-self: center;
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
    background-image: url(https://nimoy.ceosdesigns.sk/template/v04/images/background.jpg);
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

.panel {
    border-radius: 2rem;
    border: 0px;
    box-shadow: rgba(33,33,33,.08) 0 4px 24px 5px;
}

.login-form{
    border-radius: 1rem;
    border: 0px;
    box-shadow: rgba(33,33,33,.08) 0 4px;
    margin-bottom: 20px;
    color: #333333;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid transparent;
    position: relative;
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
.btn.bg-indigo {
    -webkit-appearance: none;
    background: linear-gradient(90deg,#007bff,#0052ea);
    background-size: 500%;
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);
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
.head-title {
    background: linear-gradient(90deg,#007bff,#0052ea);
    background-size: 500%;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    outline: none;
    -webkit-tap-highlight-color: transparent;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border-radius: 2rem;
    box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);
    color: #fff;
    position: absolute;
    width: 90%;
    top: -5em;
    padding: 10px 20px;
}
.form_content{
    padding-top: 4em;
}
.rc-anchor-light.rc-anchor-normal {
    border: 0!important;
}
.input-eye {
    background: transparent;
    color: #0052ea;
    border: none;
}
.form-control:not(.border-bottom-1):not(.border-bottom-2):not(.border-bottom-3):focus {
    border-color: #0052ea;
    transition-timing-function: ease;
}
    /* Social */
    
    .social-buttons,
.social-buttons li {
    display: flex;
    padding: 0;
    margin: 0;
}

.social-buttons {
    width: 100%;
    list-style: none;
    flex-wrap: wrap;
    margin: 1.5rem 0rem 0rem;
    justify-content: center;
}

.social-buttons li {
    flex-basis: 20%;
    flex-shrink: 0;
    margin: 2px; 
}

.social-buttons li:first-child {
    margin-left: 0px; 
}

.social-buttons li:last-child {
    margin-right: 0px; 
}

.social-buttons a {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    padding: 0.75rem 1rem;
    font-weight: 500;
    text-decoration: none;
    color: #fff;
    border-radius: 0.3125rem;
    border: 1px solid rgba(255,255,255, 0);
    transition: 0.4s;
}

.social-buttons a:hover{
    color: #fff;
    border: 1px solid rgba(255,255,255, 0.25);
}

</style>
<div class="page-container">
    <div class="content">
        <form class="frm_recover_password" action="/facturacionv8/login/recoverpassword" method="get" id="frm_recover_password" <?php if($accion == 'recover') { echo ""; } else {echo 'style="display: none;"'; } ?>>
            <div class="login-form">
                <div class="head-title">
                    <div class="text-center">
                        <img src="<?php echo $data_empresa['logo_img_291']; ?>" class="logo" width="220px" alt="">
                        
                        <ul class="social-buttons">
                            <li class="nm-hvr">
                                <a href="http://google.com/">
                                    <i class="fa fa-google"></i>
                                </a>
                            </li>
                            <li class="nm-hvr">
                                <a href="https://twitter.com/">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li class="nm-hvr">
                                <a href="https://www.facebook.com/">
                                    <i class="fa fa-facebook"></i>	
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row form_content">
                    <div class="col-md-12 text-center">
                        <h5 class="content-group">Recuperar Contraseña</h5>
                    </div>
                    <div class="text-center text-main-welcome" <?php if($accion == 'email_enviado') { echo ""; } else {echo 'style="display: none;"'; } ?>>
                        <p class="lead">
                            Hemos Enviado el Enlace de Recuperación del Password a tu Correo Electrónico.
                        </p>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-group input-group">
                                <span class="input-group-addon input-eye" id="show-passwd" action="hide"><i class="icon-envelop"></i></span>
                                <input type="email" name="email" class="form-control input-login" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?= $this->flashSession->output() ?>
                    </div>
                    <div class="col-md-12 text-center captcha">
                        <div style="margin: 0 auto;" class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="habilitar_login"></div>
                    </div>
                   
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button type="submit" class="btn bg-indigo btn_cambiar_password legitRipple" disabled>Recuperar Contraseña <i class="icon-circle-right2 position-right"></i></button>
                        </div>
                    </div>
                  
                    <div class="col-md-12">
                        <div class="text-center sign-up">
                            <p class="ftz-17">¿No tienes cuenta? <a href="/facturacionv8/registro" target="_blank">Regístrate</a></p>
                        </div>
                    </div>
                  
                </div>
            </div>
        </form>
    </div>
</div>
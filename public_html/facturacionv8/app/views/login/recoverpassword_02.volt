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
    background: url('https://arasari.studio/wp-content/projects/forny/templates/img/bg-04.svg') center bottom no-repeat
        #ffe0eb;
    background: url('https://arasari.studio/wp-content/projects/forny/templates/img/bg-04.svg') center bottom no-repeat
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
.lead{
    font-size: 14px;
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
                        <h5 class="content-group">Recuperar contraseña</h5>
                        <div class="text-center text-main-welcome" <?php if($accion == 'recover') { echo ""; } else {echo 'style="display: none;"'; } ?>>
                            <p class="mb-10 lead">Escribe tu correo electrónico y enviaremos un correo con un enlace de recuperació a tu email</p>
                        </div>
                        <div class="text-center text-main-welcome" <?php if($accion == 'email_enviado') { echo ""; } else {echo 'style="display: none;"'; } ?>>
                            <p class="lead">
                                Hemos Enviado el Enlace de Recuperación del Password a tu Correo Electrónico.
                            </p>
                        </div>
                    </div>
                    <form class="frm_recover_password" action="/facturacionv8/login/recoverpassword" method="get" id="frm_recover_password" <?php if($accion == 'recover') { echo ""; } else {echo 'style="display: none;"'; } ?>>
                        <div class="form-group">
                            <div class="form-group">
                            
                                <div class="form-group input-group">
                                    <span class="input-group-addon input-eye" id="show-passwd" action="hide">	<i class="icon-envelop"></i></span>
                                    <input type="email" name="email" class="form-control input-login" placeholder="Email">
                                </div>
                            </div>
                        </div>
    
                        <div class="row text-center">
                            <div class="col-md-12">
                                <?= $this->flashSession->output() ?>
                            </div>
                            <div class="col-md-12 text-center captcha mb-5" style="margin: auto;">
                                <div  class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="habilitar_login"></div>
                            </div>
                               
                            <div class="col-md-12 mt-5">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-block btn_cambiar_password legitRipple" disabled>Recuperar Contraseña <i class="icon-circle-right2 position-right"></i></button>
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
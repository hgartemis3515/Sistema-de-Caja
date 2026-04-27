<style>

body{
    font-size: 16px;
}
.main-content {
    min-height: 100vh;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    background-image: url("https://nimoy.ceosdesigns.sk/template/v05/images/background.jpg");

    -webkit-box-orient: vertical !important;
    -webkit-box-direction: normal !important;
    flex-direction: column !important;
}

.main-container {
    margin-bottom: auto !important;
    margin-top: auto !important;

    padding-bottom: 5rem !important;
    padding-top: 5rem !important;
}

    
.d-flex{
    display: flex!important;
}
.btn-block {
    width: 100%;
}

.form-group {
    margin-bottom: 1rem;
}

.nm-mlr-1 {
    margin-left: 3rem !important;
    margin-right: 3rem !important;
}

.nm-tc {
    text-align: center !important;
}

.nm-aic {
    -webkit-box-align: center !important;
    align-items: center !important;
}

.nm-jcb {
    -webkit-box-pack: justify !important;
    justify-content: space-between !important;
}

.nm-mb-0 {
    margin-bottom: 0rem !important;
}

.nm-mb-1 {
    margin-bottom: 1.5rem !important;
}

.nm-mt-1 {
    margin-top: 1.5rem !important;
}

.divider {
    position: relative;
}

.divider::before {
    content: "";
    position: absolute;
    top: 50%;
    display: block;
    width: 100%;
    height: 0;
    background: #ffffff;
    border-top: 1px solid #eeeeff;
    transform: translateY(calc(-50% + 1px));
}

.divider-content {
    position: relative;
    display: inline-block;
    font-weight: 600;
    font-size: 0.875rem;
    color: #97a4af;
    background-color: #fff;
    padding: 0 0.9375rem;
}

.card {
    box-shadow: 0 0.125rem 1.25rem 0 rgba(153, 155, 168, 0.12);
    position: relative;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    min-width: 0;
    background-color: #ffffff;
    background-clip: border-box;
    border: 0 solid transparent;
    border-radius: 0.125rem;
}

.card-content {
    padding: 1.5rem !important;

    -webkit-box-flex: 1;
    flex: 1 1 auto;
}

/*****************************/
/* 04. ELEMENTS              */
/*****************************/

/*****************************/
/*  04.00 TEXT-RELATED       */
/*****************************/

.nm-ft-b {
    font-weight: 600;
    font-size: 0.875rem;
}


/*****************************/
/*  04.02 FORM INPUTS        */
/*****************************/

.form-control {
    display: block;
    width: 100%;
    height: auto;
    padding: 1.0625rem 1rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.2;
    color: #1e2022;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid #eeeeff;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    background-color: #ffffff;
    border-color: #007bff;
    outline: 0;
    box-shadow: none;
}

.form-control::-webkit-input-placeholder {
    color: #97a4af;
    opacity: 1;
    }

.form-control::-moz-placeholder{
    color: #97a4af;
    opacity: 1;
}

.form-control:-ms-input-placeholder{
    color: #97a4af;
    opacity: 1;
}

.form-control::placeholder{
    color: #97a4af;
    opacity: 1;
}

.nm-control {
    position: relative;
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5rem;
}

.nm-control-input {
    position: absolute;
    z-index: -1;
    opacity: 0;
}

.nm-control-label {
    font-weight: 400;
    color: #1e2022;
    position: relative;
    margin-bottom: 0;
    vertical-align: top;
}
.list {
    display: flex;
}
.icon {
    width: 40px;
    height: 40px;
    font-size: 1.125rem;
    display: inline-flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    color: #007bff !important;
    margin-right: 1rem !important;
    border-radius: 50% !important;
    background-color: #fff !important;
}

.content {
    -webkit-box-flex: 1;
    flex: 1;
    margin-top: 0;
    padding: 0px!important;
}

.content > p {
    font-weight: 600;
    line-height: 2;
}

.subtitle {
    margin-bottom: 2rem;
    font-weight: 600;
    line-height: 2;
    color: #007bff;
}

/*****************************/
/*  04.03 FORM CHECK         */
/*****************************/

.nm-checkbox .nm-control-label::before {
    border-radius: 0.125rem;
}
.nm-control-label::before, .nm-file-label {
    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}
.nm-control-label::before {
    position: absolute;
    top: 0.25rem;
    left: -1.5rem;
    display: block;
    width: 1rem;
    height: 1rem;
    pointer-events: none;
    content: "";
    background-color: #eeeeff;
    border: #eeeeff solid 0;
}

.nm-control-label::after {
    position: absolute;
    top: 0.25rem;
    left: -1.5rem;
    display: block;
    width: 1rem;
    height: 1rem;
    content: "";
    background: no-repeat 50% / 50% 50%;
}

.nm-control-input:checked ~ .nm-control-label::before {
    color: #ffffff;
    border-color: #007bff;
    background-color: #007bff;
}

.nm-checkbox .nm-control-input:checked ~ .nm-control-label::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e");
}

.nm-control-label::after {
    position: absolute;
    top: 0.25rem;
    left: -1.5rem;
    display: block;
    width: 1rem;
    height: 1rem;
    content: "";
    background: no-repeat 50% / 50% 50%;
}

/*****************************/
/*  04.04 BUTTON             */
/*****************************/

.nm-btn {
    min-width: 9.25rem;
    font-weight: 600;
    font-size: 12px;
    line-height: 1.2;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    padding: 0.875rem 0.875rem;
    border-radius: 0.25rem;
    color: #ffffff;
    background-color: #007bff;
    border: 1px solid #007bff;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}

.nm-btn:hover {
    background-color: #0052ea;
    border: 1px solid #0052ea;
}

.nm-btn:focus,.nm-btn:active {
    box-shadow: none;
}

.btn-primary:not(:disabled):not(.disabled):active:focus {
    box-shadow: none;
}
.fa:before {
    font-size: 16px;
}
/*****************************/
/*  04.05 SOCIAL             */
/*****************************/

.social {
    display: flex;
    flex-wrap: wrap;
}

a.sb {
    color: #fff !important;
}

/* FACEBOOK */
.btn-facebook {
    color: #fff;
    background-color: #3b5998 !important;
    border-color: #3b5998 !important;
}

.btn-facebook:hover {
    color: #fff;
    background-color: #2d4373 !important;
    border-color: #293e6a !important;
}

/* TWITTER */ 
.btn-twitter {
    color: #fff;
    background-color: #66c3ff !important;
    border-color: #66c3ff !important;
}

.btn-twitter:hover {
    color: #fff;
    background-color: #33afff !important;
    border-color: #26aaff !important;
}

/* GOOGLE */ 
.btn-google {
    color: #fff;
    background-color: #e6162d !important;
    border-color: #e6162d !important;
}

.btn-google:hover {
    color: #fff;
    background-color: #cf1026 !important;
    border-color: #cf1026 !important;
}

.g-recaptcha > div {
        margin: auto;
    }

/*****************************/
/*  05. MEDIA QUERIES        */
/*****************************/

@media (min-width: 576px){		
    .card-content {
        padding: 2rem !important;
    }
}

@media (min-width: 768px){
    .card-content {
        padding: 2rem !important;
    }

    .nm-mb-md-1 {
        margin-bottom: 0rem !important;
    }
    .col-md-6 {
        width: 50%;
        float: left;
    }
}

@media (min-width: 992px){
    .card-content {
        padding: 2rem !important;
    }
    .offset-lg-1 {
        padding-left: 5.3333333333%;
    }
    
    .col-lg-6 {
        width: 50%;
        float: left;
    }
}

@media (min-width: 1200px){
    .card-content {
        padding: 3rem !important;
    }
}
.lead{
    font-size: 15px;
}
.mt-5{
    margin-top: 2em!important;
}
</style>
<div class="d-flex main-content">
    <div class="container main-container">
        <div class="row nm-aic">
            <div class="col-lg-6 col-md-6  nm-mb-1 nm-mb-md-1">
                <div class="card">
                    <div class="card-content">
                        <div class="text-center">
                            <img src="<?php echo $data_empresa['logo_img_461']; ?>" class="logo" width="220px"  alt="">
                        </div>
                        <h2 class="nm-tc nm-mb-1">Recuperar contraseña</h2>
                        <div class="text-center text-main-welcome" <?php if($accion == 'recover') { echo ""; } else {echo 'style="display: none;"'; } ?>>
                            <p class="mb-10 lead">Escribe tu correo electrónico y enviaremos un correo con un enlace de recuperació a tu email</p>
                        </div>
                        <div class="text-center text-main-welcome" <?php if($accion == 'email_enviado') { echo ""; } else {echo 'style="display: none;"'; } ?>>
                            <p class="lead">
                                Hemos Enviado el Enlace de Recuperación del Password a tu Correo Electrónico.
                            </p>
                        </div>
                        <form class="frm_recover_password" action="/facturacionv8/login/recoverpassword" method="get" id="frm_recover_password" <?php if($accion == 'recover') { echo ""; } else {echo 'style="display: none;"'; } ?>>
                            <div class="form-group">
                                <label for="inputEmail">Email</label>
                                <input type="email" name="email" class="form-control input-login input-text" placeholder="Email">
                            </div>	
                           
                            <div class="form-group">
                                <?= $this->flashSession->output() ?>
                            </div>
                            <div class="col-md-12 text-center captcha mb-5">
                                <div style="margin: 0 auto;" class="g-recaptcha" data-sitekey="<?php echo $data_empresa['captcha_key_public']; ?>" data-callback="habilitar_login"></div>
                            </div>
                          
                            <button type="submit" class="btn btn-block btn-primary mt-5 btn-login btn_cambiar_password  text-uppercase nm-btn" disabled>Recuperar contraseña</button>

                            <div class="row social nm-mb-1 mt-5">
                                <div class="col-lg-6 mb-2 mb-lg-0">
                                    <a href="#" class="btn btn-block text-uppercase nm-btn btn-facebook">Facebook</a>
                                </div>

                                <div class="col-lg-6">
                                    <a href="#" class="btn btn-block text-uppercase nm-btn btn-twitter">Twitter</a>
                                </div>
                            </div>

                            <p class="text-center mb-0">
                                Don’t have an account?
                                <a class="nm-ft-b" href="./signup.html">Sign Up</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6  offset-lg-1">
                <h2 class="large">Become the best you can be and have fun</h2>
                <p class="subtitle">Join the group of more than 300 clients that love us</p>
                <ul class="list-unstyled mb-11">
                    <li>
                        <div class="list nm-aic">
                            <div class="icon">
                                <i class="fa fa-briefcase"></i>
                            </div>

                            <div class="content">
                                <p>Something about businesses that makes it special and different from everything else</p>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="list nm-aic">
                            <div class="icon">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <div class="content">
                                <p>There is this saying about the business and entrepreneurship, but who remembers it anyway</p>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="list nm-aic">
                            <div class="icon">
                                <i class="fa fa-trophy"></i>
                            </div>

                            <div class="content">
                                <p>If only there was a template for all your needs wait a second there is and it is called this</p>
                            </div>
                        </div>
                    </li>
                </ul>

                <button type="submit" class="btn btn-primary text-uppercase nm-btn">Learn more</button>
            </div>
        </div>
    </div>
</div>
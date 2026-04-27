<style>
    a {
    color: #3F51B5;
}
    .full-screen {
        min-height: 100vh;
        position: relative;
        width: 100%;
        z-index: 1;
    }
    .gradient-overlay {
        position: relative;
        width: 100%;
    }
    /*.gradient-overlay:before {
        position: absolute;
        content: '';
        background-image: linear-gradient(to left, rgba(50, 100, 245, 0.90), rgba(74, 84, 232, 0.88), rgba(91, 66, 219, 0.85), rgba(104, 44, 203, 0.88), rgba(114, 2, 187, 0.90));
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }*/
    .ptb-100 {
        padding: 100px 0;
    }
    .shape-bottom img.bottom-shape {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        width: 100%;
        margin: 0 auto;
    }
    .img-fluid {
        max-width: 100%;
        height: auto;
    }
    .login-signup-card {
        position: relative;
        z-index: 2;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .card {
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
    .input-group-addon {
        border-radius: 15px;
    }
    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1.25rem;
    }
    .card-footer:last-child {
        border-radius: 0 0 calc(.25rem - 1px) calc(.25rem - 1px);
    }
    @media (min-width: 768px){
    .pl-md-5, .px-md-5 {
        padding-left: 3rem!important;
    }
    }
    .pt-5, .py-5 {
        padding-top: 3rem!important;
    }
    .border-top {
        border-top: 1px solid #dee2e6!important;
    }
    .bg-transparent {
        background-color: transparent!important;
    }
    .card-footer {
        padding: .75rem 1.25rem;
        background-color: rgba(0,0,0,.03);
        border-top: 1px solid rgba(0,0,0,.125);
    }
    .display-responsive{
            display: none!important;
    }
    .input-eye{
        border: 1px solid #ddd;
        border-bottom-left-radius: 0px!important;
        border-top-left-radius: 0px!important;
        border-bottom-right-radius: 15px!important;
        border-top-right-radius: 15px!important;
    }
    .logo-info{
        width: 350px;
    }
    .hero-content-left {
        text-align: center;
    }
    @media (min-width: 576px){
        .display-responsive{
            display: none!important;
    }
    
    }
    @media only screen and (min-width: 1200px) {
        .content{
            margin-top: 4em;
        }
    
        .content{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .display-responsive{
            display: inherit!important;
        }
        .padding-top-10em{
            padding-top: 10em
        }
        .logo-info{
            width: 450px;
        }
        .full-screen {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .hero-content-left {
            text-align: left;
        }
    }
    
    @media (min-width: 600px) and (max-width: 1024px)
    {
        .display-responsive{
            display: none!important;
        }
        .login-signup-card{
            width: 65%;
            display: block;
            margin: auto;
        }
        .hero-content-left {
            text-align: center;
        }
        .logo-info{
            width: 350px;
        }
        
    }
    
    @media only screen and (max-width: 1170px) and (min-width: 900px){
        .login-form{
            width: 100%;
        }
        .logo-info{
            width: 350px;
        }
        .display-responsive{
            display: inherit!important;
        }
        .login-signup-card{
            width: 100%;
            display: block;
            margin: auto;
        }
        .hero-content-left {
            text-align: left;
        }
    
    }
    
    .form-control {
        border-radius: 15px;
        padding: 1.5em 1em;
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
    
    .quote p {
        color: #666;
        font-style: italic;
        padding-left: 20px;
        margin-top: 25px;
        margin-bottom: 20px;
    }
    .quote.primary-theme p { 
        border-left: 3px solid #fff; color: #fff; 
        position: relative;
        margin-left: 4em;
    }
    .quote.primary-theme p::before {
        content: "\eb49";
        font-family: 'icomoon';
        font-size: 35px;
        position: absolute;
        color: #fff;
        left: -1.5em;
        top: 0;
        font-style: initial;
    }
    
    .quote-avatar {
        display: inline-block;
        margin: 0 auto;
        float: left;
    }
    .quote-avatar img {
        width: 70px;
        height: 70px;
        -webkit-border-radius: 100%;
        -moz-border-radius: 100%;
        -o-border-radius: 100%;
        border-radius: 100%;
        margin-left: 4em;
    }
    
    .quote-author {
        display: inline-block;
        padding: 0 0 0 15px;
        text-align: left;
        position: relative;
        top: 13px;
    }  
    
    /*------------------------------------------*/
    /*    Quote Autor
    /*------------------------------------------*/ 
        
    .quote-author h5 {
        margin-bottom: 1px;
        font-size: 18px;
        font-weight: 700;
    }
    
    .quote-author span {
        font-size: 12px;
        font-weight: 300;
        display: block;
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
    .small, small {
        font-size: 14px;
        font-weight: 400;
    }
    .carousel-control {
        display: none;
    }
    .owl-carousel .owl-item img {
        display: block;
        width: inherit;
    }
    .owl-nav .owl-prev span, .owl-nav .owl-next span {
        border: 1px solid #fff;
        padding: 0px 15px;
        font-size: 30px;
        line-height: 70px;
    }
    .owl-nav .owl-prev span:hover, .owl-nav .owl-next span:hover {
        background-color: #fff;
        color: #3f51b5;
        transition: .5s all;
    }
    .owl-nav {
        text-align: center;
    }
    
	</style>
<div class="page-container">
    <div class="hero-section ptb-100 gradient-overlay full-screen" style="background: url('https://arpsystem.com.pe/facturacionv8/img/hero-6.jpg')no-repeat center center / cover">
        <div class="container">
        
            <div class="row align-items-center justify-content-between pt-5 pt-sm-5 pt-md-5 pt-lg-0">
                <div class="col-md-7 col-lg-6 col-sm-12">
                    <div class="hero-content-left text-white">
                        <div class="img-logo">
                            <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/hero-logo2.png"  class="logo-info" alt="">
                        </div>
                        <h1 class="text-white">¡Bienvenido a FacturalaYa!</h1>
                        <p class="lead">
                            ¡Inicia Hoy Mismo con la Facturación Electrónica y Sin Tediosos Contratos que te Amarren!
    
                        </p>
                    
                        <!-- Testomonial -->
                        <div class="owl-carousel owl-theme testimonial_box display-responsive">
                            <div class="item">
                                <div class="quote primary-theme mt-20">
                                    <!-- Quote Text -->
                                    <p>"Nuestra misión es desarrollar soluciones integrales y únicas que ayuden a nuestros clientes a incrementar su nivel de Rentabilidad. ¡Queremos convertirnos en tus Aliados Estratégicos!"									   
                                    </p>																				<!-- Quote Avatar -->
                                    <div class="quote-avatar">
                                        <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/quote-avatar.jpg" alt="quote-avatar">
                                    </div>
                                    <!-- Quote Author -->
                                    <div class="quote-author">
                                        <h5 class="h5-xs">Alex Castañeda</h5>
                                        <span class="grey-color">CEO de facturalaya.com</span>
                                        <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/verificado_sunat.png" style="width:50px!important" class="img-fluid" alt="">
                                    </div>				
                                </div>
                            </div>
                            
                            <div class="item">
                                <div class="quote primary-theme mt-20">
                                    <!-- Quote Text -->
                                    <p>Lo que más me gusta de facturalaya.com es que la emisión de un comprobante electrónico es realmente sencillo, soy dueño de una librería, tengo 52 años y nunca he utilizado un sistema de facturación y en esta ocasión estoy feliz porque he logrado dominarlo sin problemas! ¡Gracias facturalaya.com!</p>																			<!-- Quote Avatar -->
                                    <div class="quote-avatar">
                                        <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/testimonios/isidro_llico_testimonio.jpg" alt="quote-avatar">
                                    </div>
                                    <div class="quote-author">
                                        <h5 class="h5-xs">Isidro Llico</h5>
                                    </div>			
                                </div>
                            </div>
                            <div class="item">
                                <div class="quote primary-theme mt-20">
                                    <!-- Quote Text -->
                                    <p>Lo que más me gusta de facturalaya.com es que la emisión de un comprobante electrónico es realmente sencillo, soy dueño de una librería, tengo 52 años y nunca he utilizado un sistema de facturación y en esta ocasión estoy feliz porque he logrado dominarlo sin problemas! ¡Gracias facturalaya.com!</p>			<!-- Quote Avatar -->
                                    <div class="quote-avatar">
                                        <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/testimonios/eber_aguilar_testimonio.jpg" alt="quote-avatar">
                                    </div>
                                    <div class="quote-author">
                                        <h5 class="h5-xs">Eber Aguilar</h5>
                                    </div>		
                                </div>
                            </div>
                            <div class="item">
                                <div class="quote primary-theme mt-20">
                                    <!-- Quote Text -->
                                    <p>Actualmente tengo un Restaurante de Comida Criolla y nos encanta porque con facturalaya.com tenemos la posibilidad de tener nuestro sitio web propio, es decir: tenemos un nuevo canal de ventas para atrater clientes potenciales. Hemos logrado llegar a más personas utilizando las características PREMIUM de facturalaya.com.</p>			<!-- Quote Avatar -->
                                    <div class="quote-avatar">
                                        <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/testimonios/gina_paola_testimonio.jpg" alt="quote-avatar">
                                    </div>
                                    <div class="quote-author">
                                        <h5 class="h5-xs">Paola Villanueva</h5>
                                    </div>		
                                </div>
                            </div>
                            <div class="item">
                                <div class="quote primary-theme mt-20">
                                    <!-- Quote Text -->
                                    <p>Lo que más me gusta de facturalaya.com es que la emisión de un comprobante electrónico es realmente sencillo, soy dueño de una librería, tengo 52 años y nunca he utilizado un sistema de facturación y en esta ocasión estoy feliz porque he logrado dominarlo sin problemas! ¡Gracias facturalaya.com!</p>			<!-- Quote Avatar -->
                                    <div class="quote-avatar">
                                        <img src="https://arpsystem.com.pe/gestion-plantillas/humbertoguadalup/1/images/testimonios/pedro_hidalgo_testimonio.jpg" alt="quote-avatar">
                                    </div>
                                    <div class="quote-author">
                                        <h5 class="h5-xs">Pedro Hidalgo</h5>
                                    </div>		
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-lg-5 col-sm-12">
                    <div class="card login-signup-card shadow-lg mb-0">
                        <div class="card-body px-md-5 py-5">
                            <div class="text-center">
                                <img src="<?php echo $data_empresa['logo_img_461']; ?>" width="300px" class="logo" alt="">
                                <h5 class="h3">Inicia sesión</h5>
                                <p class="text-muted mb-0">Introduce tus credenciales para continuar.</p>
                            </div>
                            <!--login form-->
                            <form  class="frm_login" action="/facturacionv8/login/session" method="post">
                                <div class="login-form">
                                    <div class="row">
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
                                                    <input type="password" name="password" id="contrasena" class="form-control input-login" placeholder="Contraseña">
                                                    <span class="input-group-addon input-eye btn bg-indigo" id="show-passwd" action="hide"><i class="icon-eye-blocked"></i></span>
                                                </div>
                                                <div class="text-left">
                                                    <a class="ftz-17" href="/facturacionv8/login/recoverpassword">¿Olvidaste la Contraseña?</a>
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
                                                <p class="ftz-17">Al Utilizar Nuestros Servicios Aceptas Nuestros <a href="/facturacionv8/terminoslegales/terminos_del_servicio" target="_blank">Términos y Condiciones y Política de Tratamiento de Datos.</a></p>
                                            </div>
                                        </div> 
                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn bg-indigo btn-login legitRipple" disabled>Acceder al Sistema <i class="icon-circle-right2 position-right"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="text-center sign-up">
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
                        <div class="card-footer bg-transparent border-top px-md-5 text-center"><small>¿No estás registrado?</small>
                            <a href="/facturacionv8/registro" class="small"> Crear cuenta</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape-bottom">
            <img src="/facturacionv8/img/hero-shape-bottom.svg" alt="shape" class="bottom-shape img-fluid">
        </div>
	</div>
</div>
<script>
	$('.owl-carousel').owlCarousel({
	loop:true,
	margin:10,
	nav:true,
	autoplay: true,
	autoplayTimeout: 7000,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:1
		},
		1000:{
			items:1
		}
	}
});
</script>
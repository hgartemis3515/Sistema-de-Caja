<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/es_LA/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution="setup_tool"
  page_id="740404332826448"
  logged_in_greeting="Tienes Dudas?. Recuerda que puedes utilizar una tarjeta de crédito o débito..."
  logged_out_greeting="Tienes Dudas?. Recuerda que puedes utilizar una tarjeta de crédito o débito...">
</div>

<body class="pace-done">
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <div class="head-web">
                <h1 style="max-width: 1000px; margin: 0 auto;"><span class="titulo">Estás a un Paso...  </span> de descargar todo el código fuente en PHP de nuestro facturador electrónico SUNAT</h1>
            </div> 
            <div class="cuerpoweb">
                <div class="cuerpoweb-wrap">
                    <div class="cuerpoweb-contenido">
                        <div class="cuerpoweb-titulo">
                            <img src="/facturacionv8/public/css/images/choices.png" />
							<h2 class="modal-title">Oferta Por Tiempo Limitado</h2>
                            <p style="display:none;">
                                Solo por tiempo limitado obtendrás el código fuente más todos los bonos por tan solo $ 297 dólares... esta promoción finaliza en: 2 días, 3 horas, y 45 minutos...
                            </p>
						</div>
                        <div class="cuerpoweb-plan">
                            <div class="cuerpoweb-planes" style="width: 350px !important;">
                                <div class="cuerpoweb-radio planreco" style="width: 100% !important;">
                                    <div class="burburja-reco">
                                        <img src="/facturacionv8/public/css/images/flecha-izquierda.png" />
                                        <p><big>40%</big><br />Descuento</p>
                                    </div>
                                    <label>
                                        <input type="radio" name="opcion_precio"  class="radioweb" value="297" checked>
                                        <span class="botonradio"></span>
                                        <p>Hoy
                                            <big>(recomendado)</big>
                                            <br />
                                            <span class="text_opcion_first">
                                                <small>US$</small> 297
                                                <small> </small>
                                            </span>
                                            <br />
                                            <span class="text-muted content-group-sm precio_anterior">Antes <span class="strikethrough">US$ 497</span></span>
                                        </p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="cuerpoweb-titulo">
                            <img src="/facturacionv8/public/css/images/id-card.png" />
							<h2 class="modal-title">Datos para tu cuenta</h2>
                            <p>
                                Escribe tus datos personales para crear tu cuenta en el sistema...
                            </p>                            
						</div>
                        <div class="cuerpoweb-plan">
                            <form class="datos-usurario" id="form_cuentapersonal">
                                <div class="sec-form">
                                    <input class="inputMaterial" name="txt_cuenta_nombre" id="txt_cuenta_nombre" type="text" required>                                          
                                    <label>Nombre</label>
                                    <i class="fa fa-user"></i> 
                                </div>
                                <div class="sec-form">
                                    <input type="text" name="txt_cuenta_apellido" id="txt_cuenta_apellido" class="inputMaterial" required>     
                                    <label>Apellido</label>
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="sec-form">
                                    <input type="text" name="txt_cuenta_telefono" id="txt_cuenta_telefono" class="inputMaterial" required>     
                                    <label>Teléfono</label>
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="sec-form">
                                    <input type="text" name="txt_cuenta_email" id="txt_cuenta_email" class="inputMaterial" required>  
                                    <label>Email</label>
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="sec-form">                                    
                                    <input type="text" name="txt_cuenta_password" id="txt_cuenta_password" class="inputMaterial" required>   
                                    <label>Password</label>
                                    <i class="fa fa-eye"></i>
                                </div>
                                <div class="sec-form">                                    
                                    <div class="editor-selectdos">
                                        <select id="txt_cuenta_pais" name="txt_cuenta_pais" class="select inputMaterial" tabindex="-1" aria-hidden="true" required>
                                            <option value="FL" selected="true">Perú</option>
                                        </select>
                                        <small id="txt_paisusuario-error2" class="" style="display: none; z-index: 999 !important; left: 10px !important;">Pais</small>                   
                                        <label>País</label>
                                    </div>                                    
                                </div>
                            </form>
                        </div>
                        <div class="cuerpoweb-titulo">
                            <img src="/facturacionv8/public/css/images/payment.png" />
							<h2 class="modal-title">Información de pago</h2>
                            <p style="display:none;">
                                Te pedimos los datos pago para que no pierdas el acceso al servicio en el caso de que desees seguir. No te cobraremos nada hasta el {{fecha_payout}}. Si no quieres que se te cargue nada debes cancelar antes de esa fecha.
                            </p>                            
						</div>
                        <div class="row">
                            <div class="cuerpoweb-planes">
                                <div class="cuerpoweb-radio">
                                    <label>
                                        <input type="radio" name="opcion_metodo_pago" class="radioweb" value="paypal">
                                        <span class="botonradio"></span>
                                        <p style="margin-top: 0px !important;">Paypal</p>
                                    </label>
                                </div>
                                <div class="cuerpoweb-radio planreco">
                                    <label>
                                        <input type="radio" name="opcion_metodo_pago" class="radioweb" value="tarjeta" checked="true">
                                        <span class="botonradio"></span>
                                        <p style="margin-top: 0px !important;">Tarjeta
                                            <big>(recomendado)</big>
                                        </p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- contenedor tarjeta de crédito o débito -->
                        <div class="cuerpoweb-plan cuerpoplanfin contenedor_tarjeta">
                            <div class="globalContent">
                                <main>
                                    <section class="container-lg">
                                        <!--payform_nayari 1-->
                                        <div class="cell payform_nayari payformnayari">
                                            <form>
                                                <fieldset style="border: none;">
                                                    <div class="row" style="border: solid 1px rgba(0,0,0,0.2);">
                                                        <label for="payformnayari-name" data-tid="elements_payforms.form.name_label">Nombre</label>
                                                        <input id="payformnayari-name" data-tid="elements_payforms.form.name_placeholder" type="text" placeholder="Nombre en Tarjeta" required>
                                                    </div>
                                                </fieldset>
                                                <fieldset>
                                                    <div class="row">
                                                        <div id="payformnayari-card"></div>
                                                    </div>
                                                </fieldset>
                                                <div class="row" style="display:none;">
                                                    <ul class="listapreboton">
                                                        <li>Entiendo que mi suscripción hará los cobros automáticos cada mes.</li>
                                                        <li>Entiendo que puedo suspender mi suscripción cuando quiera.</li>
                                                    </ul>
                                                </div>
                                                <button type="submit" data-tid="elements_payforms.form.pay_button">Enviar $297</button>
                                                <div class="error" role="alert">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                                        <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z" />
                                                        <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z" />
                                                    </svg>
                                                    <span class="message"></span></div>
                                            </form>
                                            <div class="success">
                                                <div class="icon">
                                                    <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink">
                                                        <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none" />
                                                        <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none" />
                                                    </svg>
                                                </div>
                                                <h3 class="title" data-tid="elements_payforms.success.title">Pago realizado</h3>
                                                <p class="message"><span data-tid="elements_payforms.success.message">Genial!! y Bienvenido <strong id="success_message_nombre"></strong>, hemos enviado un mensaje a tu email con tus datos de acceso, también puedes acceder haciendo <a target="_blank" href="/facturacionv8/login/">click aquí!</a></span></p>
                                                <a class="reset" href="#">
                                                    <svg width="32px" height="32px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink">
                                                        <path fill="#000000" d="M15,7.05492878 C10.5000495,7.55237307 7,11.3674463 7,16 C7,20.9705627 11.0294373,25 16,25 C20.9705627,25 25,20.9705627 25,16 C25,15.3627484 24.4834055,14.8461538 23.8461538,14.8461538 C23.2089022,14.8461538 22.6923077,15.3627484 22.6923077,16 C22.6923077,19.6960595 19.6960595,22.6923077 16,22.6923077 C12.3039405,22.6923077 9.30769231,19.6960595 9.30769231,16 C9.30769231,12.3039405 12.3039405,9.30769231 16,9.30769231 L16,12.0841673 C16,12.1800431 16.0275652,12.2738974 16.0794108,12.354546 C16.2287368,12.5868311 16.5380938,12.6540826 16.7703788,12.5047565 L22.3457501,8.92058924 L22.3457501,8.92058924 C22.4060014,8.88185624 22.4572275,8.83063012 22.4959605,8.7703788 C22.6452866,8.53809377 22.5780351,8.22873685 22.3457501,8.07941076 L22.3457501,8.07941076 L16.7703788,4.49524351 C16.6897301,4.44339794 16.5958758,4.41583275 16.5,4.41583275 C16.2238576,4.41583275 16,4.63969037 16,4.91583275 L16,7 L15,7 L15,7.05492878 Z M16,32 C7.163444,32 0,24.836556 0,16 C0,7.163444 7.163444,0 16,0 C24.836556,0 32,7.163444 32,16 C32,24.836556 24.836556,32 16,32 Z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="caption">
                                                <span data-tid="elements_payforms.caption.no_charge" class="no-charge">
                                                    <img style="height: 25px;" src="/facturacionv8/public/css/images/cards.png" />
                                                </span>
                                                <a class="source" href="https://github.com/stripe/elements-payforms/#payform_nayari-1">
                                                    <img style="height: 40px;" src="/facturacionv8/public/css/images/SSL.png" />
                                                    <p>Transacciones seguras a 128 bits</p>
                                                </a>
                                            </div>
                                        </div>
                                        
                                    </section>

                                    <style>
                                    .github-corner:hover .octo-arm {
                                        animation: octocat-wave 560ms ease-in-out
                                    }

                                    @keyframes octocat-wave {
                                        0%,
                                        100% {
                                            transform: rotate(0)
                                        }
                                        20%,
                                        60% {
                                            transform: rotate(-25deg)
                                        }
                                        40%,
                                        80% {
                                            transform: rotate(10deg)
                                        }
                                    }

                                    @media (max-width:500px) {
                                        .github-corner:hover .octo-arm {
                                            animation: none
                                        }
                                        .github-corner .octo-arm {
                                            animation: octocat-wave 560ms ease-in-out
                                        }
                                    }
                                    </style>
                                </main>
                            </div>

                        </div>
                        <!-- /contenedor tarjeta de crédito o débito -->

                        <!-- Contenedor paypal -->
                        <div class="row contenedor_paypal" style="display:none;">
                            <div class="col-md-12" style="padding: 30px;">
                                <div class="alert alert-info alert-styled-left alert-bordered" style="max-width: 696px; margin: 0 auto;">
									<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
									<span class="text-semibold">Importante!</span> Luego de enviar el dinero via Paypal, debes enviarme un email a: aquino.alex@gmail.com con tu nombre, email, dirección y teléfono para crear tu cuenta en el sistema y puedas descargar el código fuente y todos los bonos...
							    </div>

                                <p style="padding-top: 20px;"><a target="_blank" href="https://paypal.me/AlexCastanedaAquino" class="btn btn-primary btn-labeled btn-xlg"><b><i class="fa fa-paypal"></i></b> Enviar $297 via Paypal</a></p>
                            </div>
                        </div>
                        <!-- /Contenedor Paypal -->

                        <div class="cuerpoweb-plan cuerpoplanfin" style="display: none;">
                            <form class="datos-usurario datusercard">                                
                                <div class="sec-form">
                                    <input class="inputMaterial" id="card_number" data-stripe="number" type="text" required>                                          
                                    <label>Número de tarjeta</label>
                                    <i class="fa fa-credit-card-alt"></i> 
                                </div>
                                <div class="sec-form">                                    
                                    <input type="text" id="card_name" class="inputMaterial" required>     
                                    <label>Titular de la tarjeta</label>
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="sec-form sec-anio">                                    
                                    <div class="editor-selectdos">
                                        <select id="card_month" class="select inputMaterial" tabindex="-1" aria-hidden="true" data-stripe="exp_month" required>        
                                            <option value="CT">Enero</option>
                                            <option value="FL">Febrero</option>
                                            <option value="MA">Marzo</option>
                                            <option value="WV">Abril</option>
                                            <option value="WV">Junio</option>
                                            <option value="WV">Julio</option>
                                            <option value="WV">Agosto</option>
                                        </select>                             
                                        <label>Mes de Caducidad</label>                                            
                                    </div>                            
                                </div>
                                <div class="sec-form sec-anio">                                    
                                    <div class="editor-selectdos">
                                        <select id="card_year" class="select inputMaterial" data-stripe="exp_year" tabindex="-1" aria-hidden="true" required>        
                                            <option value="CT">2107</option>
                                            <option value="FL">2018</option>
                                            <option value="MA">2019</option>
                                            <option value="WV">2020</option>
                                        </select>                             
                                        <label>Año de Caducidad</label>                                            
                                    </div>                            
                                </div>
                                <div class="sec-form sec-ccv">                                    
                                    <input type="text" id="card_ccv" class="inputMaterial" data-stripe="cvc" required>     
                                    <label>CCV</label>
                                    <i class="fa fa-credit-card-alt"></i>
                                </div>
                                <div style="width:100%;float:left;">
                                    <div class="cardicon">
                                        <img src="/facturacionv8/public/css/images/cards.png" />
                                    </div>
                                    <div class="cardicon sslicon">                                            
                                        <p>Transacciones seguras a 128 bits</p>
                                        <img src="/facturacionv8/public/css/images/SSL.png" />
                                    </div>
                                </div>
                                <ul class="listapreboton">
                                    <li>Entiendo que mi suscripción hará los cobros automáticos cada mes.</li>
                                    <li>Entiendo que puedo suspender mi suscripción cuando quiera.</li>
                                </ul>
                                <div class="sec-boton">
                                    <input type="submit" class="botonsubmit" value="Unirse a red nayari" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="politicasweb">
                <div class="wrappoliticas">
                    <p>Al hacer click en "Enviar" estarás aceptando nuestra <a href="#">Política de Privacidad y Condiciones legales</a>. Transacciones seguras encriptadas a 128 bits.<img src="/facturacionv8/public/css/images/powered-by-stripe.png" /></p>
                </div>
            </div>
            <div class="footerweb">
                <div class="wrpafooterweb">
                    <div class="sellos">
                        <img src="/facturacionv8/public/css/images/Norton_av_logo.png" />
                        <img src="/facturacionv8/public/css/images/ggssl-site-seal-v1-dark.png" />
                    </div>
                    <div class="listafoo">
                        <ul>
                            <li><a href="#">Política de privacidad</a></li>
                            <li><a href="#">Política de envío y entrega</a></li>
                            <li><a href="#">Statement policies procedures</a></li>
                            <li><a href="#">Aviso de cancelación</a></li>
                            <li><a href="#">Términos y condiciones</a></li>
                            <li><a href="#">Política de Spam</a></li>
                        </ul>
                    </div>
                    <div class="redesfooweb">
                        <p><i class="fa fa-map-marker"></i>Cajamarca, Perú.</p>
                        <ul class="redesfoonayariweb">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
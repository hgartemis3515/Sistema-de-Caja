<style>
    .alert[class*=alert-styled-]:after {
    content: '\ef36'!important;
    font-family: 'icomoon';
    color: #fff;
    width: 44px;
    left: -44px;
    text-align: center;
    position: absolute;
    top: 50%;
    margin-top: -8px;
    font-size: 16px;
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.color-indigo{
    color: #3F51B5;
}

.img-fluid {
    width: 100%;
}

.mt-5{
    margin-top: 1em!important;
}
.number-item {
    background: linear-gradient(
45deg, rgba(63,81,181,1) 0%, rgba(120,128,240,1) 100%);
    color: #FFF;
    padding: 5px 10px;
    border-radius: 20px;
    margin: 10px 10px 10px 0px;
    font-weight: bold;
    width: 32px;
    height: 32px;
    text-align: center;
    font-size: 13px;
}
.position-relative{
    position: relative;
}
.position-absolute{
    position: absolute;
}
.play-position-center img{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px   
      
}

@media only screen and (max-width: 1200px) and (min-width: 1025px){
    .img-fluid {
        width: 100%;
        height: 100%;
    }
    .footer-panel {
        border-top: 1px solid #efeeee;
    }
  
}
@media(min-width: 1200px){
    /*.content-text-title {
    position: absolute;
    bottom: 8%;
    }*/
    .height-lg {
        height: 320px;
    }
    
    .img-fluid {
        width: 100%;
        height: 100%;
    }
    .footer-panel {
    position: absolute;
        bottom: 10px;
        width: 85%;
        border-top: 1px solid #efeeee;
        padding-top: 3px;
    }
}

.number_content {
    position: absolute;
    top: -20px;
    left: 0;
}
.panel-body {
    padding: 10px;
}
.service-media-bx{
    border: 1px solid #fff;
    transition: all .5s;
}
.service-media-bx:hover{
    border: 1px solid  <?php echo $data_personalizacion["color_fondo_1_rgb"]; ?>;
}
.content-text-title h6 {
    font-size: 13px;
    text-transform: initial;
}

.modal-header-bg{
	position: relative;
}
.modal-header-bg::after {
    content: ' ';
    position: absolute;
    top: -7em;
    left: 0;
    width: 100%;
    height: 400px;
    background-image: url(/facturacionv8/img/10.png);
    background-repeat: no-repeat;
}
</style>
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Centro de entrenamientoo</span></h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="/facturacionv8/documentoelectronico/index/03/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/boleta.svg" style="width: 25px;"/><span>Boleta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/01/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/factura.svg" style="width: 25px;"/><span>Factura</span></a>
				<a href="/facturacionv8/documentoelectronico/index/77/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/nota_venta.svg" style="width: 25px;"/><span>Nota de Venta</span></a>
				<a href="/facturacionv8/documentoelectronico/index/88/nuevo" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/cotizacion.svg" style="width: 25px;"/><span>Cotización</span></a>
				<a href="/facturacionv8/dashboard" class="btn btn-link btn-float has-text"><img src="/facturacionv8/img/dashboard.svg" style="width: 25px;"/><span>Dashboard</span></a>
			</div>
		</div>
	</div>
</div>

<div class="content">
    
    <div class="row" style="max-width: 1120px;margin: 0 auto;">
        <div id="overlay" class="overlay"></div>
        <div class="col-lg-12">
            <div class="alert bg-primary alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">Bienvenido al Centro de Entrenamiento!</span> Aquí puedes descagar el manual de usuario de nuestro sistema: <a href="/files_pdf/guia_usuario_facturala_ya.pdf" target="_blank"  class="alert-link">Descargar manual</a>
            </div>
        </div>
        
		<!-- <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5 overylay_descrip">
			<div class="panel border-top-indigo service-media-bx"   style="max-width: 1120px;margin: 0 auto;">
				<div class="panel-body height-lg">
					<div class="content-video">
                        <a class="modal-video mb-3" href="https://www.youtube.com/watch?v=-ll5gfZrr0A">
							<div class="position-relative">
								<img src="/facturacionv8/img/video_tutorial/aspecto_general.jpg" class="position-relative img-fluid" alt="">
								<div class="play-position-center">
									<img src="/facturacionv8/img/play_3.png">
								</div>
							</div>
                        </a>
                        <div class="content-text-title">
                            <h6 class="font-weight-bold text-uppercase color-indigo">Bienvenida! - Aspectos Generales</h6>
                            
                        </div>
                        <div class="footer-panel">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                    <a href="javascript:void(0)" class="btn_descripcion" data-id="1" data-attr="En esta serie de vídeos se explicará: cómo ingresar al sistema cómo registrar tus sucursales en el sistema, registrar tus primeros usuarios en el sistema, registrar tus primeros productos y emitir sus primeros comprobantes electrónicos.">Descargar PDF</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                      <a href="/facturacionv8/files_recursos/guia_videos/pdf/1_aspecto_naturales.pdf"><i class="icon-file-pdf text-danger"></i></a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">1</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
					<div class="content-video">
                        <a class="modal-video mb-3" href="https://www.youtube.com/watch?v=NUwpSMFIRyQ">
							<div class="position-relative">
								<img src="/facturacionv8/img/video_tutorial/editar_sucursales.jpg" class="position-relative img-fluid" alt="">
								<div class="play-position-center">
									<img src="/facturacionv8/img/play_3.png">
								</div>
							</div>
                        </a>
                        <div class="content-text-title">
                            <h6 class="font-weight-bold text-uppercase color-indigo">
                                Cómo Crear o Editar Sucursales 
                            </h6>
                        </div>
                        <div class="footer-panel">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                    <a href="javascript:void(0)" class="btn_descripcion" data-id="2" data-attr="En esta sección se agregarán las sucursales asociados a tu empresa.  A diferencia de las otras gestiones, está dividida en tres partes: Un formulario para registrar, la configuración de comprobantes y los diseños de reportes, personalizados, para tus emisiones de documentos electrónicos. Cabe resaltar que una vez de configurar las series y números de tus comprobantes, y guardar la nueva sucursal, ya no podrás modificar estos datos. 
                                    A su vez, las series de números no se repiten entre las sucursales. ¿Cómo así? </br>
                                    EJEMPLO:</br>
                                    Si Sucursal 01, emite comprobantes con serie F001, B001, FC01, FD01, BC01, BD01, T001. Entonces, Sucural 02 tendrá que ser F002, B002, FC02, FD02, BC02, BD02, T002.</br>
                                    Es importante tener en cuenta que para la pestaña de Serie/Correlativos ciertas características impuestas por el SUNAT: </br>
                                    La numeración de los comprobantes de pago y notas de crédito y débito electrónicas, se distinguen de la numeración aquellos emitidos en formato impreso por imprenta autorizada, por la estructura de la numeración:</br> 
                                    1.	La serie es alfanumérica de cuatro dígitos comenzando con la letra F.</br>
                                    2.	La numeración es correlativa, de hasta ocho posiciones, empezando por el número 1 y es independiente a la numeración de la factura física.</br>
                                    ">Descargar PDF</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                      <a href="/facturacionv8/files_recursos/guia_videos/pdf/2_agregar_sucursal.pdf"><i class="icon-file-pdf text-danger"></i></a>
                                </div>
                            </div> 
                        </div>
                    </div>

                </div>
                <div class="number_content">
                    <h6 class="number-item">2</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    
					<div class="content-video">
                        <a class="modal-video mb-3" href="https://www.youtube.com/watch?v=vA6g_ftjUBc">
							<div class="position-relative">
								<img src="/facturacionv8/img/video_tutorial/editar_usuarios.jpg" class="position-relative img-fluid" alt="">
								<div class="play-position-center">
									<img src="/facturacionv8/img/play_3.png">
								</div>
							</div>
                        </a>
                        <div class="content-text-title">
                            <h6 class="font-weight-bold text-uppercase color-indigo">
                             Cómo Crear o Editar Usuarios 
                            </h6>
                        </div>
                       
                    </div>
                    <div class="footer-panel">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <a href="javascript:void(0)" class="btn_descripcion" data-id="3" data-attr="Procedimiento de cómo crear y guardar usuarios. Los usuarios son aquellos que tienen acceso al sistema para generar comprobantes. En pocas palabras, colaboradores de los puntos de venta de tu empresa. Se dividen en 2 roles: Colaboradores (Vendedores) y Administradores. ¿En qué se diferencian? Nada más en los permisos de acceso al sistema. Aquí aprenderás a colocarlos.">Descargar PDF</a>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                  <a href="/facturacionv8/files_recursos/guia_videos/pdf/3_agregar_usuarios.pdf"><i class="icon-file-pdf text-danger"></i></a>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="number_content">
                    <h6 class="number-item">3</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://www.youtube.com/watch?v=MsUARAqAz5M">
                            <div class="position-relative">
                                <img src="/facturacionv8/img//video_tutorial/1.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        </a>
                        <div class="content-text-title">
                            <h6 class="font-weight-bold text-uppercase color-indigo">
                                Cómo Crear o Agregar Productos
                            </h6>
                            
                        </div>
                        <div class="footer-panel">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                    <a href="javascript:void(0)" class="btn_descripcion" data-id="4" data-attr="Explicación detallada de cómo subir un producto, editarlo y su administración dentro del sistema">Descargar PDF</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                    <a href="/facturacionv8/files_recursos/guia_videos/pdf/4_registrar_producto.pdf"><i class="icon-file-pdf text-danger"></i></a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">4</h6>
                </div>
            </div>
        </div>
       
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://www.youtube.com/watch?v=YXyy-f-i9yQ">
                            <div class="position-relative">
                                <img src="/facturacionv8/img//video_tutorial/factura.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        </a>
                        <div class="content-text-title">
                            <h6 class="font-weight-bold text-uppercase color-indigo">
                              Cómo Emitir una Factura de Venta Electrónica
                            </h6>
                        </div>
                        <div class="footer-panel">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                    <a href="javascript:void(0)" class="btn_descripcion" data-id="3" data-attr="Paso a paso te daremos una explicación de cómo generar una Factura Electrónica">Descargar PDF</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                    <a href="/facturacionv8/files_recursos/guia_videos/pdf/5_emitir Factura.pdf"><i class="icon-file-pdf text-danger"></i></a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">5</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://www.youtube.com/watch?v=dSYB0ywAhkg">
                            <div class="position-relative">
                                <img src="/facturacionv8/img//video_tutorial/boleta.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        </a>
                        <div class="content-text-title">
                            <h6 class="font-weight-bold text-uppercase color-indigo">
                              Cómo Emitir una Boleta de Venta Electrónica
                            </h6>
                        </div>
                        <div class="footer-panel">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                    <a href="javascript:void(0)" class="btn_descripcion" data-id="5" data-attr="Paso a paso te daremos una explicación de cómo generar una boleta Electrónica">Descargar PDF</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                      <a href="/facturacionv8/files_recursos/guia_videos/pdf/6_emitir Boleta.pdf"><i class="icon-file-pdf text-danger"></i></a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">6</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://www.youtube.com/watch?v=5gUtcSffoJ8">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_1.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        </a>
                        <div class="content-text-title">
                            <h6 class="font-weight-bold text-uppercase color-indigo">
                              Pantalla de Inicio - Aspectos Generales - Importante
                            </h6>  
                        </div>
                        <div class="footer-panel">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                    <a href="javascript:void(0)" class="btn_descripcion" data-id="3" data-attr="En la pantalla de Dashboard existen muchas opciones que podrás aprovechar para la globalización de los datos de tus comprobantes. Aprende como aprovecharlo al máximo">Descargar PDF</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                    <a href="/facturacionv8/files_recursos/guia_videos/pdf/7_pantalla de Inicio.pdf"><i class="icon-file-pdf text-danger"></i></a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">7</h6>
                </div>
            </div>
        </div> -->
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/q4TF_JBhJQ4">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_1.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Configurar por Primera Vez la Cuenta del Cliente y Cómo Registrar una Sucursal
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/1_configurar_empresa_sucursal.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">1</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/UWKN0o2ZkoE">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_2.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Agregar productos, importar productos, hacer traslados e ingresos de productos a Almacén
                                </h6>
                            
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/2_gestion_productos.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">2</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/1tEJkGVrQs4">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_3.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Configurar las Condiciones de Pago
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/3_condiciones_pago.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">3</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/auI7vzZFqhM">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_4.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Registrar las Cuentas Bancarias y Cuenta de Detracción
                                </h6> 
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/4_cuentas_bancarias.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">4</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/SZvlOm-jt_I">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_5.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Cambiar mi Usuario y Contraseña en el Sistema
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/5_cambiar_usuario_contraseña.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">5</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/HzImJWnbhd0">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_6.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Emitir una Boleta sin Ingresar los Datos del Cliente
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/6_boleta_sin_datos_cliente.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">6</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/aUpHAI602tM">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_7.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Boleta en Soles y Dólares
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/7_crear_boleta.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">7</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/B7BaqE1UudI">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_8.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Emitir una Factura en Soles y Dólares
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/8_crear_factura.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">8</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/vcKgpLVetdA">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_9.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Emitir una Factura con Detracción Tanto en Soles como en Dólares
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/9_factura_con_detraccion.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">9</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/VLNOOD_2Cmk">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_10.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Ver el Último Precio Utilizado en la Venta de un Producto para un Cliente Antiguo
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/10_ultimo_precio_utilizado.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">10</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/9wmiJZsqm-s">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_11.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Factura de Exportación
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/11_boleta_exportacion.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">11</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/IOutokmg2CM">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_12.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Factura con Retención
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/12_factura_retencion.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">12</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/KqBrfukv80I">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_13.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                      
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Factura al Crédito a una sola cuota, varias cuotas y detracción
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/13_factura_con_cuotas_a_credito.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">13</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/GvSyNVk_7H8">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_14.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Agrega el Número de Orden a una Factura, Boleta, Nota de Venta u Otro Documento
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/14_orden_de_compra_cpe.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">14</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/80Ek1L73chU">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_15.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Agregar el Número de Placa a una Factura, Boleta, Nota de Ventre entre Otros
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/15_numero_placa_cpe.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">15</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/n4HhFhY9mBc">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_16.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Factura, Boleta o Otro Documento con Fecha Anterior
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/16_cpe_fecha_anterior.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">16</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/eJVm_g6kID8">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_17.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                      
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Referencias o Agregar una o varias Guías de Remisión a una Factura
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/17_guia_remision_en_factura.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">17</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/C6mIJvvzOkI">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_18.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title mb-5">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Asignar Ventas a Un Vendedor en Específico
                                </h6>
                                <div style="margin-bottom: 40px!important;"></div>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/18_agregar_ventas_a_vendendor.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">18</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/G5lv3f_BH-I">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_19.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Nota de Venta
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/19_nota_de_venta.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">19</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/NdGGUc6GZ90">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_20.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Cotización
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/20_crear_cotizacion.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">20</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/4Wm_fyoMZ0I">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_21.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                        
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Nota de Crédito para una Factura y Boleta
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/21_crear_nota_credito.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">21</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/LhAlc8GTrp4">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_22.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Nota de Débito
                                </h6>
                                <div style="margin-bottom: 40px!important;"></div>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/22_crear_nota_debito.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">22</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/tDFU1F1JfJw">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_23.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Crear una Guía de Remisión
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/23_como crear una guia de remision.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">23</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/WZNgx7ZzxwQ">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_24.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Importar Documentos Electrónicos Facturas y Boletas desde un Excel
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/24_importacion_cpe_en_excel.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">24</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/K-DOxKoJnnU">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_25.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Transformar, Copiar, Editar y Duplicar Documentos Electrónicos
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/25_duplicar_documentos_cpe.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">25</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/UI37zS64iqU">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_26.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Enviar un Comprobante Electrónico Vía WhatsApp
                                </h6>
                                <div style="margin-bottom: 40px!important;"></div>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/26_enviar_documentos_whatsapp.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">26</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/ufaLaCEAi68">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_27.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Enviar un Comprobante Electrónico Vía Email
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/27_enviar_documentos_email.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">27</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-5">
            <div class="panel border-top-indigo service-media-bx" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body height-lg">
                    <div class="content-video">
                        <a class="modal-video mb-3" href="https://youtu.be/9IWlrOwkX_A">
                            <div class="position-relative">
                                <img src="/facturacionv8/files_recursos/guia_videos/preview/plantilla_video_guia_28.jpg" class="position-relative img-fluid" alt="">
                                <div class="play-position-center">
                                    <img src="/facturacionv8/img/play_3.png">
                                </div>
                            </div>
                       
                            <div class="content-text-title">
                                <h6 class="font-weight-bold text-uppercase color-indigo">
                                    Cómo Utilizar el Módulo de Caja Chica
                                </h6>
                            </div>
                        </a>
                        <div class="footer-panel">
                            <a href="/facturacionv8/files_recursos/guia_videos/pdf/28_caja_chica.pdf" target="_blank">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        Descargar PDF
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6  text-right">
                                        <i class="icon-file-pdf text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="number_content">
                    <h6 class="number-item">28</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<script  type="text/javascript">
    jQuery(function(){
    jQuery(".modal-video").YouTubePopUp();
    });
   
</script>
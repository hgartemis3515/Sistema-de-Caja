<style>
    #bg_solido, #bg_degradado{
        width: 100%;
        height: 40px;
    }
    button.bg-indigo{
        border: none!important;
        background-size: 500%!important;
        -webkit-appearance: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
        color: #fff;
        cursor: pointer;
        border-radius: 5rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        outline: none;
        -webkit-tap-highlight-color: transparent;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        padding: 7px 12px;
        font-size: 13px;
        line-height: 1.5384616;
        text-transform: uppercase;
        font-weight: 500;
    }
    .bg-indigo{
        border: none!important;
        background-size: 500%;
        -webkit-appearance: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
        color: #fff;
        cursor: pointer;
        border-radius: 5rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        outline: none;
        -webkit-tap-highlight-color: transparent;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        padding: 7px 12px;
        font-size: 13px;
        line-height: 1.5384616;
        text-transform: uppercase;
        font-weight: 500;
    }
    .bg-indigo i {
    color: #fff!important;
    -webkit-text-fill-color: initial!important;
}
 
.navigation li a>i.new_style_icon, .label-form i.new_style_icon, legend i.new_style_icon  {
    -webkit-text-fill-color: initial;
}
.navbar.bg-indigo {
    border-radius: 0!important;
    padding: 0;
    box-shadow: 0;
}
.zero-contenedor {
    width: 100%;
    background-color: #f5f2f0;
    border: 1px solid #E4E4E4;
    /*overflow-x: auto;*/
    padding: 8px;
    height: 180px;
}
.mb-5{
    margin-bottom: 2em!important;
}
input[type=color] {
    height: 70px!important;
    width: 80px!important;
}
@media(min-width: 300px) and (max-width: 1000px){
    .col-lg-1 {
        width: 50%;
        float: left;
    }
    .zero-contenedor {
        overflow-y: auto;
    }
}
@media(max-width: 1024px){
    .col-lg-1 {
        width: 50%;
        float: left;
    }
    .zero-contenedor {
        overflow-y: auto;
    }
}
</style>
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Personalización de colores</span></h4>
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
	<div class="panel panel-flat  border-top-indigo">
        <div class="panel-body">
            <form name="frm_color" id="frm_color" action="">
                <input type="hidden" name="idopcion" id="idopcion">
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="content-group">
                            <legend class="text-bold">
                                <span class="text-uppercase">Selecciona tu diseño personalizado</span> 
                            </legend>
                            <p>Aquí podrás elegir un nuevo diseño para tu login o registro al sistema, seleccionando las plantillas disponibles. En caso que desees volver al diseño anterior, elija Default.</p>
                        </fieldset>
                        <label for="">Login</label>
                    </div>
                    <div class="col-lg-12 mb-5">
                        <div class="zero-contenedor">   
                            <div class="row content_item_login">
                                <?php                                    
                                foreach ($lista_disenios_login as $login) {
                                   echo ' 
                                   <div class="col-lg-1 col-md-6"> 
                                       <div class="thumbnail thumbnail-plantilla">
                                           <div class="thumb">
                                               <a  href="'.$login->preview.'" data-popup="lightbox"> 
                                                   <img id="boleta_preview_a4" src="'.$login->preview.'" alt=""> 
                                                   <span class="zoom-image">  
                                                       <i class="fa fa-eye"></i> 
                                                    </span>
                                                </a> 
                                            </div> 
                                        </div> 
                                        <div class="radio">  
                                            <label id="radiobuttonset"> 
                                                <input type="radio" value="'.$login->id_diseno.'" name="id_plantilla_login" class="id_plantilla_login control-primary">'.$login->nombre.'
                                            </label> 
                                        </div>  
                                    </div>';
                                }
                                ?>
                            </div>             
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label for="">Registro</label>
                    </div>
                    <div class="col-lg-12 mb-5">
                        <div class="zero-contenedor">   
                            <div class="row content_item_registro">
                                <?php                                    
                                foreach ($lista_disenios_registro as $registro) {
                                   echo ' 
                                   <div class="col-lg-1  col-md-6"> 
                                       <div class="thumbnail thumbnail-plantilla">
                                           <div class="thumb">
                                               <a  href="'.$registro->preview.'" data-popup="lightbox"> 
                                                   <img id="boleta_preview_a4" src="'.$registro->preview.'" alt=""> 
                                                   <span class="zoom-image">  
                                                       <i class="fa fa-eye"></i> 
                                                    </span>
                                                </a> 
                                            </div> 
                                        </div> 
                                        <div class="radio">  
                                            <label> 
                                                <input type="radio" value="'.$registro->id_diseno.'" name="id_plantilla_registro" class="id_plantilla_registro control-success">'.$registro->nombre.'
                                            </label> 
                                        </div>  
                                    </div>';
                                }
                                ?>
                            </div>             
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <fieldset class="content-group">
                            <legend class="text-bold">
                                <span class="text-uppercase">Selecciona tu color personalizado</span> 
                            </legend>
                            <p>Podrás elegir colores para personalizar tu sistema ya sea en sólido y degradado</p>
                        </fieldset>
                    </div>
                    <div class="col-lg-6">
                        <label>Color Sólido</label>
                        <div class="radio">
                            <label>
                                <input type="radio" value="color_solido" name="radio_color" id="radio_solido" class="tipo_color control-primary">
                                Sólido
                            </label>
                        </div>
                        <div class="content-solid"></div>
                    </div>
                    <div class="col-lg-6">
                        <label for="">Color Degradado</label>
                        <div class="radio">
                            <label>
                                <input type="radio" value="color_degradado" name="radio_color" id="radio_degradado" class="tipo_color control-success">
                                Degradado
                            </label>
                        </div>
                        <div class="content-degradado"></div>
                        
                    </div>
                    <div class="col-lg-12 text-center mt-5" id="btn_content_save">
                        <hr>
                        <button class="btn bg-indigo legitRipple btn_save mxy-20 mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar cambios</button>
                        <button class="btn bg-indigo legitRipple btn_deshacer_cambios mxy-20 mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Deshacer Cambios</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
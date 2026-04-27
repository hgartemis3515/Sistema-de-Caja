<style>
/* tab style */
.nav_new_style {
    text-align: center;
}
.nav-tabs.nav_new_style.nav-tabs-solid>.active>a, .nav-tabs.nav_new_style.nav-tabs-solid>.active>a:focus, .nav-tabs.nav_new_style.nav-tabs-solid>.active>a:hover {
    margin: 0 10px;
    border-radius: 5rem!important;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
}
.nav-tabs.nav_new_style.nav-tabs-solid>li >a, .nav-tabs.nav_new_style.nav-tabs-solid>li >a:focus, .nav-tabs.nav_new_style.nav-tabs-solid> li >a:hover {
	background-color: #fff;
    border: 1px solid #ddd!important;
    border-radius: 5rem!important;
	box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
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
.nav-tabs.nav-tabs-solid.nav_new_style {
    background-color: transparent;
}

#box-tag .form-control{
    border: 0px solid #ddd;
}
#box-tag  .tokenfield .token {
    border-radius: 20px;
    margin: 6px 0 0 6px;
}
.content_lista_de_etiquetas{
     display: inline-block;
}
#content_lista_de_etiquetas {
    padding: 20px;
    background: rgba(228, 228, 228, 0.507);
    border: 2px dashed rgb(228, 228, 228);
    margin-bottom: 10px;
}
#content_lista_de_etiquetas .tag_etiqueta_individual{
    margin-right: 10px;
}
.tag_etiqueta_individual {
    position: relative;
    display: inline-block;
    padding: 8px 0px;
    cursor: pointer;
}
.tag_etiqueta_individual span {
    border-radius: 20px;
    margin: 0px;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 6px 6px;
    padding-right: 25px;
    font-size: 10.5px;
    line-height: 1.6666667;
}

.tag_etiqueta_individual .close{
    font-size: 18px;
    cursor: pointer;
    position: absolute;
    color: inherit;
    right: 8px;
    line-height: 1;
    color: #000;
}
.tag_etiqueta_individual span:hover .close a{
    color: #fff!important;
}
.border-primary-600 {
    border-color: #7880f0!important;
}
.radio{
    display: inline-block;
}

.mt-2 {
    margin-top: 10px;
}
#btn_modal_importar_cpe{
    padding-left: 40px;
    margin-bottom: 10px;
}
@media (max-width: 768px){
	.nav-tabs:before {
    	content: ' ';
	}
	.nav-tabs {
    	border: 0;
	}
	.nav-tabs>li {
        text-align: center;
		display: inline-block;
	}
    #btn_modal_importar_cpe{
        padding-left: 40px;
    }
   
}
/* New style */
.add-item {
    margin: 0;
    /* height: 35px; */
    min-width: 60px;
    background-color: #fff;
    border: 1px solid #d1d1d1;
    color: #6f6f6f!important;
    border-radius: 100px;
    font-size: 10px;
    font-weight: 500;
    line-height: 20px;
    display: inline-block;
    cursor: pointer;
    transition: all .5s;
}
.add-item:hover {
    border-color: #b1b1b1;
    background-color: #b1b1b1;
    color: #fff!important;
}
.box-cpe-tag {
    border: 1px dashed #ccc;
    background: #f9f7f7;
    padding: 5px;
    margin-left: 1em;
    transition: all .5s;
}
.box-cpe-tag:hover {
    border: 1px dashed #4caf50;
}
.title-tag{
    transition: all .5s;
}
.box-cpe-tag:hover .title-tag{
    color: #4caf50;
    
}
.icon-box-content {
    position: absolute;
    left: -10px;
    top: 50%;
    transform: translate(0%, -70%);
    color: #4caf50;
}

.icon-play4:before {
    font-size: 15px;
}

.tag-content span{
    margin-right: 6px;
}
.tag-content span.label-primary{
    box-shadow: none!important;
}
#tbl_lista_documentos td, #tbl_lista_notas_venta td, #tbl_lista_cotizaciones td, #tbl_lista_guiasremision td{
    position: relative;
    -webkit-animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    -webkit-transition: 0.6s;
    transition: 0.6s;
}
</style>
<!-- modal etiquetas -->
<div class="modal fade" id="vm_asignar_etiqueta">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">            
			<div class="modal-header modal-header-bg">
				<button type="button" id="cerrar_vm_asignar_etiqueta" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="content_vm_asignar_etiqueta">
				<div class="tabbable margin-top-20">
					<ul class="nav nav-tabs nav-tabs-solid border-0 nav_new_style">
						<li class="nav-item active mt-2"><a href="#lista_doc" class="nav-link active" data-toggle="tab">Asignación</a></li>
						<?php 
                        if($rol == 'admin') {
                        ?>
                        <li class="nav-item mt-2"><a href="#detalle_doc" class="nav-link active" data-toggle="tab">Registrar Etiquetas</a></li>
                        <?php
                        }
                        ?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="lista_doc">
                            <input type="hidden" value="" id="vm_etiqueta_id_contribuyente" />
                            <input type="hidden" value="" id="vm_etiqueta_tipo_documento" />
                            <input type="hidden" value="" id="vm_etiqueta_serie_documento" />
                            <input type="hidden" value="" id="vm_etiqueta_correlativo" />
                            <div class="row" id="content_etiquetas_elegir">
                                
                            </div>
						</div>

						<div class="tab-pane" id="detalle_doc">


                            <div class="col-md-12">
                                <div class="form-group display-none" id="box-etiqueta-prospecto">
                                    <label class="label-form"><i class="fa fa-tag position-left"></i>Etiquetas</label>
                                    <input type="text" class="form-control" name="etiquetas" id="etiquetas" readonly>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-form"><i class="icon-droplet2 mr-2"></i>Color</label>
                                        <div class="display-block mr-1"></div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color_etiqueta" class="control_color_etiqueta control-primary" value="primary" id="color_etiqueta_primary" checked>
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color_etiqueta" class="control_color_etiqueta control-success" value="success" id="color_etiqueta_success">
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color_etiqueta" class="control_color_etiqueta control-info" value="info" id="color_etiqueta_info">
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="color_etiqueta" class="control_color_etiqueta control-danger" value="danger" id="color_etiqueta_danger">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 p-0">
                                    <div class="form-group">
                                        <label class="label-form"><i class="fa fa-tag position-left"></i>Agregar</label>
                                        <div class="input-group">
                                            <input type="text" name="txt_nombre_etiqueta" class="form-control" id="txt_nombre_etiqueta" placeholder="Nombre de la Etiqueta" required>
                                            <span class="input-group-btn">
                                                <button id="btn_agregar_etiqueta" class="btn btn-primary legitRipple" type="button">
                                                    Agregar etiqueta
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 p-0" id="add_modal_token">
                                    <div id="content_lista_de_etiquetas">
                                    </div>
                                </div>
                            </div>


                            
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!-- /modal etiquetas -->
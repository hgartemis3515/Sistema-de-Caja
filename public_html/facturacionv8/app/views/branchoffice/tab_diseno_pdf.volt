<style>
img {
	height: auto;
	max-width: 100%;
}
.content-plantilla, #content_plantilla_ticket, #content_plantilla_formato {
	padding: 10px;
	background: rgba(228, 228, 228, 0.507);
	border: 2px dashed rgb(228, 228, 228);
	margin-bottom: 10px;
	height: 370px;
	overflow: auto;
}
.content-2 {
	padding: 10px;
	background: rgba(228, 228, 228, 0.507);
	border: 2px dashed rgb(228, 228, 228);
	margin-bottom: 10px;
}
.content-3 {
	height: 430px;
	padding: 10px;
	background: rgba(228, 228, 228, 0.507);
	border: 2px dashed rgb(228, 228, 228);
	margin-bottom: 10px;
}
.arrow_left {
	position: absolute;
	left: -2em;
	top: 1em;
}
.display-none{
	display: none!important;
}
.fancybox-close {
	right: 20px;
}
.mt-2{
	margin-top: 2.5rem;
}
.mr-lg-2 {
	margin-right: 1em!important;
}
.float-left {
	float: left;
}
.float-right {
	float: right;
}

.modelo_plantila_pdf_a4 {
	padding: 4em 7em;
	-webkit-box-shadow: 0 5px 40px 0 rgba(0, 0, 0, 0.11);
	box-shadow: 0 5px 40px 0 rgba(0, 0, 0, 0.11);
	width: 1075px;
}

.p-0{
	padding: 0;
}
	/* === Tabla cabecera === */
	.color-blue{
	color: #006cae;
	}
	.bg-main{
	background: #006cae;
	color: #fff;
}
	.table-head   {
	font-size: 17px;
}
.table-head td, .table-head th {
	padding: 12px 60px;
	text-align: center;
}
	.table-head   {
	border-collapse: collapse;
	width: 100%;
}
.text-head p{
	line-height: 5px;
	font-size: 12px;
}
.border-top{
	border-top: 1px solid #000;
}
/* === Tabla Bordes === */
.bordered {
	border-collapse: separate !important;
	border-spacing: 0;
	border: solid #000 .5px;
	-moz-border-radius: 12px;
	-webkit-border-radius: 12px;
	border-radius: 12px;
}
.bordered th {
	border-top: none;
}
.bordered td:first-child, .bordered th:first-child {
	border-left: none;
}
.bordered th:first-child {
	-moz-border-radius: 12px 0 0 0;
	-webkit-border-radius: 12px 0 0 0;
	border-radius: 12px 0 0 0;
}
.bordered th:last-child {
	-moz-border-radius: 0 12px 0 0;
	-webkit-border-radius: 0 12px 0 0;
	border-radius: 0 12px 0 0;
}
.bordered th:only-child{
	-moz-border-radius: 12px 12px 0 0;
	-webkit-border-radius: 12px 12px 0 0;
	border-radius: 12px 12px 0 0;
}
.bordered tr:last-child td:first-child {
	-moz-border-radius: 0 0 0 12px;
	-webkit-border-radius: 0 0 0 12px;
	border-radius: 0 0 0 12px;
}
.bordered tr:last-child td:last-child {
	-moz-border-radius: 0 0 12px 0;
	-webkit-border-radius: 0 0 12px 0;
	border-radius: 0 0 12px 0;
} 
/* Table 02
============*/
.table_2{
	display: grid;
	grid-template-columns: repeat(4, 1fr);
}
.table_2 .item-table-2{
	border-left: 1px solid #000;
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	margin-top: 1.5em;
	padding: 8px;
}
.table_2 .item-table-2 p{
	text-align: center;
	font-size: 14px;
}
.table_2 .item-table-2 p:first-child {
	font-weight: 700;
}
.table_2 .item-table-2:first-child {
	border-radius: 12px 0px 0px 12px;
}
.table_2 .item-table-2:last-child {
	border-radius: 0px 12px 12px 0px;
	border-right: 1px solid #000;
}
/* Table 03
============*/
table{
		width: 100%;     
}
.table-3, .table-4{
	margin-top: 20px;
}
.table-3 td,.table-3 th, .table-4 td, .table-4 th{
	padding: 5px;
	border-bottom: 1px solid #000;
}
.table-3 .item-table-3{
	border-left: 1px solid #000;
	border-top: 1px solid #000;
	height: 40px;
	padding-top: 10px;
}
/*.border{
	border: 1px solid #000;
}*/
.borderradius-left-top {
	border-radius: 12px 0px 0px 0px;
}
.borderradius-right-top {
	border-radius: 0px 12px 0px 0px;
}
.borderradius-left-bottom {
	border-radius: 0px 0px 0px 12px;
}
.borderradius-right-bottom {
	border-radius: 0px 0px 12px 0px;
}
.border-right{
	border-right: 1px solid;
}
.border-bottom{
	border-bottom: 1px solid;
}
.spacing-lg {
	width: 60%;
}
.spacing-xm {
	width: 77%;
}
.flex-end{
	display: -ms-flexbox;
	display: flex;
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	justify-content: flex-end!important;
}
.sub-total{
	border-left: 1px solid #000;
	border-top: 1px solid #000;
	border-right: 1px solid #000;
	margin: 0;
	padding: 4px;
}
.table-4.bordered {
	border-collapse: separate !important;
	border-spacing: 0;
	border-top: solid #000 .5px;
	border-left: solid #000 .5px;
	border-right: solid #000 .5px;
	border-bottom: solid #000 .5px;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	/* border-radius: 6px; */
	border-top-left-radius: 6px;
	border-top-right-radius: 6px;
	border-bottom-right-radius: 0px!important;
	border-bottom-left-radius: 6px;
}
.table-4.bordered th:first-child {
	-moz-border-radius: 6px 0 0 0;
	-webkit-border-radius: 6px 0 0 0;
	border-radius: 6px 0 0 0;
}
.table-4.bordered th:last-child {
	-moz-border-radius: 0 6px 0 0;
	-webkit-border-radius: 0 6px 0 0;
	border-radius: 0 6px 0 0;
}
.table-4.bordered th:only-child{
	-moz-border-radius: 6px 6px 0 0;
	-webkit-border-radius: 6px 6px 0 0;
	border-radius: 6px 6px 0 0;
}
.table-4.bordered tr:last-child td:first-child {
	-moz-border-radius: 0 0 0 6px;
	-webkit-border-radius: 0 0 0 6px;
	border-radius: 0 0 0 6px;
}
.table-4.bordered tr:last-child td:last-child {
	-moz-border-radius: 0 0 0px 0;
	-webkit-border-radius: 0 0 6px 0;
	border-radius: 0 0 6px 0;
	border-bottom: solid #000 0px!important;
} 
.p-1 {
	padding: 0 20px;
}  
/* Table footer
================*/
.box-border{
	border: 1px solid #000;
	margin: 20px 0;
	padding: 10px;
}
.table-cuentas .col-lg-4{
	border-left: 1px solid #000;
	border-top: 1px solid #000;
} 
.table-cuentas .col-lg-12{
	border: 1px solid #000;
} 
.table-cuentas .col-lg-12 p{
	margin: 0;
} 
.row.table-cuentas{
	margin: 0;
} 
.box-border p{
	padding: 0px;
	margin: 0;
}     
/** Modelo 01
==================*/
.content-number-header {
	height: 100%;
	width: 100%;
	padding: 37px 20px;
}
.content-footer .title-cuentas{
	text-transform: uppercase;
	border-left: 1px solid #000;
	border-top: 1px solid #000;
	border-right: 1px solid #000;
	margin: 0;
}
.content-number-header p{
	font-size: 17px;
	line-height: 1;
}
.header-logo {
	display: grid;
	justify-content: center;
	align-self: center;
	align-content: center;
}
.header-box{
	height: 140px;
}
.header-box .col-lg-4{
	height: 100%;
}
.img-qr-01{
	margin-right: 15px
}
.table-cuentas-01{
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	text-align: center;
}
.table-cuentas-01 p{
	border-left: 1px solid #000;
	border-bottom: 1px solid #000;
	margin: 0;
	padding: 3px;
}
.box-cuentas p:first-child{
	border-top: 1px solid #000;
}
.table-cuentas-01 .box-cuentas:last-child{
	border-right: 1px solid #000;
}
.table_items div{
	border-left: 1px solid #000;
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	margin-top: 1.5em;
	text-align: center;
}
.table_items div > p:first-child{
	border-bottom: 1px solid #000;
	font-weight: 700;
}
.table_items div > p{
	padding: 5px;
}
.table_items{
	display: grid;
		grid-template-columns: repeat(5, 1fr);
}
.table_documents{
	display: grid;
		grid-template-columns: repeat(6, 1fr);
	
}
.table_documents{
	border-left: 1px solid #000;
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	margin-top: 1.5em;
}
.table_documents .hd-table{
	border-bottom: 1px solid #000;
	font-weight: 700;
	text-align: center;
}
.table_documents div > p{
	padding: 5px;
}

.price-final{
	grid-column: span 6;
}
.position-relative{
	position: relative;
}
.table-footer-01{
	margin-top: 1.5em;
	display: grid;
	grid-template-columns: 609px 1fr;
}
.item-resumen p{
	line-height: 1;
}
.text-observation {
	margin: 15px 20px;
}
/*.thumbnail {
	width: 150px;
}*/
.single-editable-box{
	border-radius: 5px;
	border: 1px solid rgba(0,0,0,.2);
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
	-webkit-box-shadow: 0 0 0 0 rgba(0,0,0,.15);
	box-shadow: 0 0 0 0 rgba(0,0,0,.15);
	-webkit-transition: all .3s ease;
	transition: all .3s ease;
	margin-top: 10px; 
	padding: 10px; 
	background: rgba(228, 228, 228, 0.507);
	border: 1px dashed rgba(0, 0, 0, 0.2); 
	margin-bottom: 10px;
}

.single-editable-box:focus {
	outline: -webkit-focus-ring-color auto 0px;
	-webkit-box-shadow: 0 3px 10px 0 rgba(0,0,0,.15);
	box-shadow: 0 3px 10px 0 rgba(0,0,0,.15);
	border-color: #3F51B5;
	background: #fff;
}
/* Modelo 03
=======================*/
.content-box-3{
	-webkit-box-shadow: 0 5px 40px 0 rgba(0, 0, 0, 0.11);
	box-shadow: 0 5px 40px 0 rgba(0, 0, 0, 0.11);
	padding: 1em;
}
.header_model_3{
	text-align: center;
}
.grid-single-3{
	display: grid;
	grid-template-columns: repeat(4, 1fr);
}
.grid-single-3 p:first-child{
	padding-bottom: .5em;
	border-bottom: .5px dashed #000; 
	margin-bottom: .5em;
}
.margin-bottom-2{
	margin-bottom: 2em;
}
.modelo_plantila_pdf_ticket {
	padding: 0 29em;
	width: 1075px;
}
.modelo_plantila_pdf_ticket p {
	line-height: 1.3;
	margin: 0;
}
/* 
 Style de new model
 ========================*/
 .content-group {
    margin-bottom: 0px!important;
}
.item_template {
    margin: auto;
}
.thumbnail-plantilla {
    width: 120px;
    margin: auto;
}
.thumbnail-plantilla-2 {
    width: 60px;
    margin: auto;
}
.modal-header .close {
    position: absolute;
    right: 20px;
    top: 30%;
    margin-top: 0;
}

</style>

<div class="row">
	<div class="col-lg-12">
		<fieldset class="content-group">
			<legend class="text-bold label-form">
				<i class="fa fa-file-text mr-2" aria-hidden="true"></i>
				<span class="text-uppercase">Agrega algún texto que desees que aparezca en la plantilla</span> 
			</legend>
		</fieldset>
		<div class="item-box">
			<label class="label-form"><i class="icon-pencil7 mr-2"></i>Header: Aparecerá en el header de la plantilla</label>
			<div class="input-group mb-2">
				<input type="text" value="<?php if($sucursal) { echo htmlspecialchars($sucursal->txt_pdf_a4_1); } ?>" name="txt_pdf_a4_1" id="txt_pdf_a4_1" placeholder="Header" class="form-control">
				<span class="input-group-btn">
					<button class="btn bg-indigo btn-icon legitRipple btn-preview-plantilla" type="button"  data-toggle="modal" data-target="#vm_preview_plantilla" data-id="pdf_header_content">
						<i class="fa fa-eye"></i>
					</button>
				</span>
			</div>
		</div>
	
		<div class="item-box">
			<label class="label-form"><i class="icon-pencil7 mr-2"></i>Debajo de los Items</label>
			<div class="input-group mb-2">
				<input type="text" value="<?php if($sucursal) { echo htmlspecialchars($sucursal->txt_pdf_a4_2); } ?>" name="txt_pdf_a4_2" id="txt_pdf_a4_2" placeholder="Debajo de los Items" class="form-control">
				<span class="input-group-btn">
					<button class="btn bg-indigo btn-icon legitRipple btn-preview-plantilla" type="button" data-toggle="modal" data-target="#vm_preview_plantilla" data-id="pdf_debajo"> 
						<i class="fa fa-eye"></i>
					</button>
				</span>
			</div>
		</div>
		
		<div class="item-box">
			<label class="label-form"><i class="icon-pencil7 mr-2"></i>Al final</label>
			<div class="input-group mb-2">
				<input type="text" name="txt_pdf_a4_3" value="<?php if($sucursal) { echo htmlspecialchars($sucursal->txt_pdf_a4_3); } ?>" id="txt_pdf_a4_3" placeholder="Al final de la plantilla" class="form-control">
				<span class="input-group-btn">
					<button class="btn bg-indigo btn-icon legitRipple btn-preview-plantilla" type="button" data-toggle="modal" data-target="#vm_preview_plantilla" data-id="pdf_final_content">
						<i class="fa fa-eye"></i>
					</button>
				</span>
			</div>
		</div>
	</div>

	<div class="col-lg-12">
		<fieldset class="content-group">
			<legend class="text-bold label-form">
				<i class="fa fa-file-text mr-2" aria-hidden="true"></i>
				<span class="text-uppercase">Elije una plantilla</span> 
			</legend>
		</fieldset>
	</div>
	<div class="col-lg-6">
		<div class="content-plantilla">
			<div class="row">
				<div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold label-form">
                            <i class="fa fa-file-text mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Boletas</span> 
                        </legend>
                    </fieldset>
                </div>
				<div class="col-xs-6 col-12 mb-5 item_template text-center">
					<label>Formato A4</label>
					<div class="thumbnail thumbnail-plantilla">
						<div class="thumb">
							<a id="boleta_urlimage_a4" href="<?php echo $plantillapdf_boleta_a4->preview; ?>" data-popup="lightbox">
								<img id="boleta_preview_a4" src="<?php echo $plantillapdf_boleta_a4->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_boleta_a4" class="btn btn-primary btn-sm mt-5" onclick="extraer_plantillas('03','a4', <?php echo $plantillapdf_boleta_a4->id_plantillapdf; ?>, 'BOLETAS - EN TAMAÑO A4')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_boleta_a4" id="id_plantillapdf_boleta_a4" value="<?php echo $plantillapdf_boleta_a4->id_plantillapdf; ?>" >
				</div>
				<div class="col-xs-6 col-12 mb-5 item_template text-center">
					<label>Ticket</label>
					<div class="thumbnail thumbnail-plantilla-2">
						<div class="thumb">
							<a id="boleta_urlimage_ticket"  href="<?php echo $plantillapdf_boleta_ticket->preview; ?>" data-popup="lightbox">
								<img id="boleta_preview_ticket"  src="<?php echo $plantillapdf_boleta_ticket->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_boleta_ticket" class="btn btn-success btn-sm mt-5" onclick="extraer_plantillas('03','ticket', <?php echo $plantillapdf_boleta_ticket->id_plantillapdf; ?>, 'BOLETAS - EN TAMAÑO TICKET')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_boleta_ticket" id="id_plantillapdf_boleta_ticket" value="<?php echo $plantillapdf_boleta_ticket->id_plantillapdf; ?>" >
				</div>
			</div>

			<div class="row">
				<div class="mt-5 col-xs-6 text-center">
					<p>¿Deseas Mostrar los Items con IGV?</p>
					<input name="boleta_mostrar_items_igv" id="boleta_mostrar_items_igv" type="checkbox" data-on-text="Si" data-off-text="No" class="switch opcion_items_igv" data-size="mini" <?php if(!isset($sucursal->boleta_mostrar_items_igv) || $sucursal->boleta_mostrar_items_igv == 'si'){ echo 'checked'; } ?> >
				</div>

				<div class="mt-5 col-xs-6 text-center">
					<p>¿Modifica Stock?</p>
					<input name="boleta_modifica_stock" id="boleta_modifica_stock" type="checkbox" data-on-text="Si" data-off-text="No" class="opcion_switch_modifica_stock" data-size="mini" <?php if(!isset($boleta_modifica_stock) || $boleta_modifica_stock == 'si' || $boleta_modifica_stock == ''){ echo 'checked'; } ?> >
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="content-plantilla">
			<div class="row">
				<div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold label-form">
                            <i class="fa fa-file-text mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Facturas</span> 
                        </legend>
                    </fieldset>
                </div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Formato A4</label>
					<div class="thumbnail thumbnail-plantilla">
						<div class="thumb">
							<a id="factura_urlimage_a4" href="<?php echo $plantillapdf_factura_a4->preview; ?>" data-popup="lightbox">
								<img id="factura_preview_a4" src="<?php echo $plantillapdf_factura_a4->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_factura_a4" class="btn btn-primary btn-sm mt-5" onclick="extraer_plantillas('01','a4', <?php echo $plantillapdf_factura_a4->id_plantillapdf; ?>, 'FACTURAS - EN TAMAÑO A4')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_factura_a4" id="id_plantillapdf_factura_a4" value="<?php echo $plantillapdf_factura_a4->id_plantillapdf; ?>" >
				</div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Ticket</label>
					<div class="thumbnail thumbnail-plantilla-2">
						<div class="thumb">
							<a id="factura_urlimage_ticket" href="<?php echo $plantillapdf_factura_ticket->preview; ?>" data-popup="lightbox">
								<img id="factura_preview_ticket" src="<?php echo $plantillapdf_factura_ticket->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_factura_ticket" class="btn btn-success btn-sm mt-5" onclick="extraer_plantillas('01','ticket', <?php echo $plantillapdf_factura_ticket->id_plantillapdf; ?>, 'FACTURAS - EN TAMAÑO TICKET')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_factura_ticket" id="id_plantillapdf_factura_ticket" value="<?php echo $plantillapdf_factura_ticket->id_plantillapdf; ?>" >
				</div>
			</div>

			<div class="row">
				<div class="mt-5 col-xs-6 text-center">
					<p>¿Deseas Mostrar los Items con IGV?</p>
					<input name="factura_mostrar_items_igv" id="factura_mostrar_items_igv" type="checkbox" data-on-text="Si" data-off-text="No" class="switch opcion_items_igv" data-size="mini" <?php if(!isset($sucursal->factura_mostrar_items_igv) || $sucursal->factura_mostrar_items_igv == 'si'){ echo 'checked'; } ?>>
				</div>

				<div class="mt-5 col-xs-6 text-center">
					<p>¿Modifica Stock?</p>
					<input name="factura_modifica_stock" id="factura_modifica_stock" type="checkbox" data-on-text="Si" data-off-text="No" class="opcion_switch_modifica_stock" data-size="mini" <?php if(!isset($factura_modifica_stock) || $factura_modifica_stock == 'si' || $factura_modifica_stock == ''){ echo 'checked'; } ?> >
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="content-plantilla">
			<div class="row">
				<div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold label-form">
                            <i class="fa fa-file-text mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Nota de crédito</span> 
                        </legend>
                    </fieldset>
                </div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Formato A4</label>
					<div class="thumbnail thumbnail-plantilla">
						<div class="thumb">
							<a id="notacredito_urlimage_a4" href="<?php echo $plantillapdf_notacredito_a4->preview; ?>" data-popup="lightbox">
								<img id="notacredito_preview_a4" src="<?php echo $plantillapdf_notacredito_a4->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_notacredito_a4" class="btn btn-primary btn-sm mt-5" onclick="extraer_plantillas('07','a4', <?php echo $plantillapdf_notacredito_a4->id_plantillapdf; ?>, 'NOTAS DE CRÉDITO - EN TAMAÑO A4')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_notacredito_a4" id="id_plantillapdf_notacredito_a4" value="<?php echo $plantillapdf_notacredito_a4->id_plantillapdf; ?>" >
				</div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Ticket</label>
					<div class="thumbnail thumbnail-plantilla-2">
						<div class="thumb">
							<a id="notacredito_urlimage_ticket" href="<?php echo $plantillapdf_notacredito_ticket->preview; ?>" data-popup="lightbox">
								<img id="notacredito_preview_ticket" src="<?php echo $plantillapdf_notacredito_ticket->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_notacredito_ticket" class="btn btn-success btn-sm mt-5" onclick="extraer_plantillas('07','ticket', <?php echo $plantillapdf_notacredito_ticket->id_plantillapdf; ?>, 'NOTAS DE CRÉDITO - EN TAMAÑO TICKET')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_notacredito_ticket" id="id_plantillapdf_notacredito_ticket" value="<?php echo $plantillapdf_notacredito_ticket->id_plantillapdf; ?>" >
				</div>
			</div>

			<div class="row">
				<div class="mt-5 col-xs-6 text-center">
					<p>¿Deseas Mostrar los Items con IGV?</p>
					<input name="notacredito_mostrar_items_igv" id="notacredito_mostrar_items_igv" type="checkbox" data-on-text="Si" data-off-text="No" class="switch opcion_items_igv" data-size="mini" <?php if(!isset($sucursal->notacredito_mostrar_items_igv) || $sucursal->notacredito_mostrar_items_igv == 'si'){ echo 'checked'; } ?>>
				</div>

				<div class="mt-5 col-xs-6 text-center">
					<p>¿Modifica Stock?</p>
					<input name="notacredito_modifica_stock" id="notacredito_modifica_stock" type="checkbox" data-on-text="Si" data-off-text="No" class="opcion_switch_modifica_stock" data-size="mini" <?php if(!isset($notacredito_modifica_stock) || $notacredito_modifica_stock == 'si' || $notacredito_modifica_stock == ''){ echo 'checked'; } ?> >
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="content-plantilla">
			<div class="row">
				<div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold label-form">
                            <i class="fa fa-file-text mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Nota de débito</span> 
                        </legend>
                    </fieldset>
                </div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Formato A4</label>
					<div class="thumbnail thumbnail-plantilla">
						<div class="thumb">
							<a id="notadebito_urlimage_a4" href="<?php echo $plantillapdf_notadebito_a4->preview; ?>" data-popup="lightbox">
								<img id="notadebito_preview_a4" src="<?php echo $plantillapdf_notadebito_a4->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_notadebito_a4" class="btn btn-primary btn-sm mt-5" onclick="extraer_plantillas('08','a4', <?php echo $plantillapdf_notadebito_a4->id_plantillapdf; ?>, 'NOTAS DE DÉBITO - EN TAMAÑO A4')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_notadebito_a4" id="id_plantillapdf_notadebito_a4" value="<?php echo $plantillapdf_notadebito_a4->id_plantillapdf; ?>" >
				</div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Ticket</label>
					<div class="thumbnail thumbnail-plantilla-2">
						<div class="thumb">
							<a id="notadebito_urlimage_ticket" href="<?php echo $plantillapdf_notadebito_ticket->preview; ?>" data-popup="lightbox">
								<img id="notadebito_preview_ticket" src="<?php echo $plantillapdf_notadebito_ticket->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_notadebito_ticket" class="btn btn-success btn-sm mt-5" onclick="extraer_plantillas('08','ticket', <?php echo $plantillapdf_notadebito_ticket->id_plantillapdf; ?>, 'NOTAS DE DÉBITO - EN TAMAÑO TICKET')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_notadebito_ticket" id="id_plantillapdf_notadebito_ticket" value="<?php echo $plantillapdf_notadebito_ticket->id_plantillapdf; ?>" >
				</div>
			</div>

			<div class="row">
				<div class="mt-5 col-xs-6 text-center">
					<p>¿Deseas Mostrar los Items con IGV?</p>
					<input name="notadebito_mostrar_items_igv" id="notadebito_mostrar_items_igv" type="checkbox" data-on-text="Si" data-off-text="No" class="switch opcion_items_igv" data-size="mini" <?php if(!isset($sucursal->notadebito_mostrar_items_igv) || $sucursal->notadebito_mostrar_items_igv == 'si'){ echo 'checked'; } ?>>
				</div>

				<div class="mt-5 col-xs-6 text-center">
					<p>¿Modifica Stock?</p>
					<input name="notadebito_modifica_stock" id="notadebito_modifica_stock" type="checkbox" data-on-text="Si" data-off-text="No" class="opcion_switch_modifica_stock" data-size="mini" <?php if(!isset($notadebito_modifica_stock) || $notadebito_modifica_stock == 'si' || $notadebito_modifica_stock == ''){ echo 'checked'; } ?> >
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="content-plantilla">
			<div class="row">
				<div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold label-form">
                            <i class="fa fa-file-text mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Cotizaciones</span> 
                        </legend>
                    </fieldset>
                </div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Formato A4</label>
					<div class="thumbnail thumbnail-plantilla">
						<div class="thumb">
							<a id="cotizacion_urlimage_a4" href="<?php echo $plantillapdf_cotizacion_a4->preview; ?>" data-popup="lightbox">
								<img id="cotizacion_preview_a4" src="<?php echo $plantillapdf_cotizacion_a4->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_cotizacion_a4" class="btn btn-primary btn-sm mt-5" onclick="extraer_plantillas('88','a4', <?php echo $plantillapdf_cotizacion_a4->id_plantillapdf; ?>, 'COTIZACIONES - EN TAMAÑO A4')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_cotizacion_a4" id="id_plantillapdf_cotizacion_a4" value="<?php echo $plantillapdf_cotizacion_a4->id_plantillapdf; ?>" >
				</div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Ticket</label>
					<div class="thumbnail thumbnail-plantilla-2">
						<div class="thumb">
							<a id="cotizacion_urlimage_ticket" href="<?php echo $plantillapdf_cotizacion_ticket->preview; ?>" data-popup="lightbox">
								<img id="cotizacion_preview_ticket" src="<?php echo $plantillapdf_cotizacion_ticket->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_cotizacion_ticket" class="btn btn-success btn-sm mt-5" onclick="extraer_plantillas('88','ticket', <?php echo $plantillapdf_cotizacion_ticket->id_plantillapdf; ?>, 'COTIZACIONES - EN TAMAÑO TICKET')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_cotizacion_ticket" id="id_plantillapdf_cotizacion_ticket" value="<?php echo $plantillapdf_cotizacion_ticket->id_plantillapdf; ?>" >
				</div>
			</div>

			<div class="row">
				<div class="mt-5 col-xs-6 text-center">
					<p>¿Deseas Mostrar los Items con IGV?</p>
					<input name="cotizacion_mostrar_items_igv" id="cotizacion_mostrar_items_igv" type="checkbox" data-on-text="Si" data-off-text="No" class="switch opcion_items_igv" data-size="mini" <?php if(!isset($sucursal->cotizacion_mostrar_items_igv) || $sucursal->cotizacion_mostrar_items_igv == 'si'){ echo 'checked'; } ?>>
				</div>

				<div class="mt-5 col-xs-6 text-center">
					<p>¿Modifica Stock?</p>
					<input name="cotizacion_modifica_stock" id="cotizacion_modifica_stock" type="checkbox" data-on-text="Si" data-off-text="No" class="opcion_switch_modifica_stock" data-size="mini" <?php if(isset($cotizacion_modifica_stock) && $cotizacion_modifica_stock == 'si'){ echo 'checked'; } ?> >
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="content-plantilla">
			<div class="row">
				<div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold label-form">
                            <i class="fa fa-file-text mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Guía de Remisión</span> 
                        </legend>
                    </fieldset>
                </div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Formato A4</label>
					<div class="thumbnail thumbnail-plantilla">
						<div class="thumb">
							<a id="guiaremision_urlimage_a4" href="<?php echo $plantillapdf_guiaremision_a4->preview; ?>" data-popup="lightbox">
								<img id="guiaremision_preview_a4" src="<?php echo $plantillapdf_guiaremision_a4->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_guiaremision_a4" class="btn btn-primary btn-sm mt-5" onclick="extraer_plantillas('09','a4', <?php echo $plantillapdf_guiaremision_a4->id_plantillapdf; ?>, 'GUÍA REMISIÓN - EN TAMAÑO A4')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_guiaremision_a4" id="id_plantillapdf_guiaremision_a4" value="<?php echo $plantillapdf_guiaremision_a4->id_plantillapdf; ?>" >
				</div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Ticket</label>
					<div class="thumbnail thumbnail-plantilla-2">
						<div class="thumb">
							<a id="guiaremision_urlimage_ticket" href="<?php echo $plantillapdf_guiaremision_ticket->preview; ?>" data-popup="lightbox">
								<img id="guiaremision_preview_ticket" src="<?php echo $plantillapdf_guiaremision_ticket->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_guiaremision_ticket" class="btn btn-success btn-sm mt-5" onclick="extraer_plantillas('09','ticket', <?php echo $plantillapdf_guiaremision_ticket->id_plantillapdf; ?>, 'GUÍA DE REMISIÓN - EN TAMAÑO TICKET')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_guiaremision_ticket" id="id_plantillapdf_guiaremision_ticket" value="<?php echo $plantillapdf_guiaremision_ticket->id_plantillapdf; ?>" >
				</div>
			</div>

			<div class="row">
				<div class="mt-5 col-xs-6 text-center">
					<p>¿Deseas Mostrar los Items con IGV?</p>
					<input name="guiaremision_mostrar_items_igv" id="guiaremision_mostrar_items_igv" type="checkbox" data-on-text="Si" data-off-text="No" class="switch opcion_items_igv" data-size="mini" <?php if(!isset($sucursal->guiaremision_mostrar_items_igv) || $sucursal->guiaremision_mostrar_items_igv == 'si'){ echo 'checked'; } ?>>
				</div>

				<div class="mt-5 col-xs-6 text-center">
					<p>¿Modifica Stock?</p>
					<input name="guiaremision_modifica_stock" id="guiaremision_modifica_stock" type="checkbox" data-on-text="Si" data-off-text="No" class="opcion_switch_modifica_stock" data-size="mini" <?php if(isset($guiaremision_modifica_stock) && $guiaremision_modifica_stock == 'si'){ echo 'checked'; } ?> >
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="content-plantilla">
			<div class="row">
				<div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold label-form">
                            <i class="fa fa-file-text mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Notas de Venta</span> 
                        </legend>
                    </fieldset>
                </div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Formato A4</label>
					<div class="thumbnail thumbnail-plantilla">
						<div class="thumb">
							<a id="notaventa_urlimage_a4" href="<?php echo $plantillapdf_notaventa_a4->preview; ?>" data-popup="lightbox">
								<img id="notaventa_preview_a4" src="<?php echo $plantillapdf_notaventa_a4->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_notaventa_a4" class="btn btn-primary btn-sm mt-5" onclick="extraer_plantillas('77','a4', <?php echo $plantillapdf_notaventa_a4->id_plantillapdf; ?>, 'NOTAS DE VENTA - EN TAMAÑO A4')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_notaventa_a4" id="id_plantillapdf_notaventa_a4" value="<?php echo $plantillapdf_notaventa_a4->id_plantillapdf; ?>" >
				</div>
				<div class="col-xs-6 mb-5 item_template text-center">
					<label>Ticket</label>
					<div class="thumbnail thumbnail-plantilla-2">
						<div class="thumb">
							<a id="notaventa_urlimage_ticket" href="<?php echo $plantillapdf_notaventa_ticket->preview; ?>" data-popup="lightbox">
								<img id="notaventa_preview_ticket" src="<?php echo $plantillapdf_notaventa_ticket->preview; ?>" alt="">
								<span class="zoom-image"><i class="fa fa-eye"></i></span>
							</a>
						</div>
					</div>
					<button type="button" id="btn_accion_notaventa_ticket" class="btn btn-success btn-sm mt-5" onclick="extraer_plantillas('77','ticket', <?php echo $plantillapdf_notaventa_ticket->id_plantillapdf; ?>, 'NOTAS DE VENTA - EN TAMAÑO TICKET')"><i class="fa fa-exchange mr-2"></i>Cambiar </button>
					<input type="hidden" name="id_plantillapdf_notaventa_ticket" id="id_plantillapdf_notaventa_ticket" value="<?php echo $plantillapdf_notaventa_ticket->id_plantillapdf; ?>" >
				</div>
			</div>

			<div class="row">
				<div class="mt-5 col-xs-6 text-center">
					<p>¿Deseas Mostrar los Items con IGV?</p>
					<input name="notaventa_mostrar_items_igv" id="notaventa_mostrar_items_igv" type="checkbox" data-on-text="Si" data-off-text="No" class="switch opcion_items_igv" data-size="mini" <?php if(!isset($sucursal->notaventa_mostrar_items_igv) || $sucursal->notaventa_mostrar_items_igv == 'si'){ echo 'checked'; } ?>>
				</div>

				<div class="mt-5 col-xs-6 text-center">
					<p>¿Modifica Stock?</p>
					<input name="notaventa_modifica_stock" id="notaventa_modifica_stock" type="checkbox" data-on-text="Si" data-off-text="No" class="opcion_switch_modifica_stock" data-size="mini" <?php if(!isset($notaventa_modifica_stock) || $notaventa_modifica_stock == 'si' || $notaventa_modifica_stock == ''){ echo 'checked'; } ?> >
				</div>
			</div>
		</div>
	</div>
</div>


<div class="col-lg-12" style="margin-top: 5em;">
	<div class="text-right">
		<button class="btn bg-indigo legitRipple btn_branchoffice" type="button">
			<i class="icon-floppy-disk mr-2"></i>
			Guardar plantilla
		</button>
	</div>
</div>	

<!-- Seleccion plantillas  modal -->
<div id="vm_seleccion_de_plantillas" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<fieldset class="content-group">
					<legend class="text-bold label-form">
						<i class="fa fa-file-text mr-2" aria-hidden="true"></i>
						<span class="text-uppercase">Selecciona una plantilla para: <strong id="vm_nombre_cpe"></strong></span> 
					</legend>
				</fieldset>
			</div>

			<div class="modal-body">
				<div class="tabbable" id="tab_formatos">
					<ul class="nav nav-tabs nav-tabs-highlight text-center">
						<li class="active"><a href="#tab_plantillas_normales" data-toggle="tab" class="tab_opcion"><i class="fa fa-file-o mr-2" aria-hidden="true"></i>Generales</a></li>
						<li><a href="#tab_plantillas_personalizadas" class="tab_opcion" data-toggle="tab"><i class="fa fa-paint-brush mr-2" aria-hidden="true"></i> Personalizadas</a></li>
					</ul>
				
					<div class="tab-content">
						<input type="hidden" name="vm_tipo_doc_electronico" id="vm_tipo_doc_electronico" value="">
						<input type="hidden" name="vm_tamanio_pdf" id="vm_tamanio_pdf" value="">
						<div class="tab-pane active" id="tab_plantillas_normales">
							<div class="row">
								<div class="col-lg-12">
									<div class="content-2">
										<div class="row" id="content_plantillas_normales">
											
										</div>
									</div>
								</div>
							</div>
						</div>
				
						<div class="tab-pane" id="tab_plantillas_personalizadas">
							<div class="row">
								<div class="col-lg-12">
									<div class="content-3">
										<div class="row" id="content_plantillas_personalizadas">
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="button" id="btn_guardar_plantilla" class="btn btn-primary">Seleccionar!</button>
			</div>
		</div>
	</div>
</div>
<!-- /Selección plantillas modal -->

<!-- Preview plantilla -->
<div id="vm_preview_plantilla" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<fieldset class="content-group">
					<legend class="text-bold label-form">
						<i class="fa fa-file-text mr-2" aria-hidden="true"></i>
						<span class="text-uppercase">Vista previa</span> 
					</legend>
				</fieldset>
			</div>

			<div class="modal-body">
				<img src="" alt="" id="img_preview_plantilla">
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="button" id="btn_guardar_plantilla" class="btn btn-primary">Seleccionar!</button>
			</div>
		</div>
	</div>
</div>
<!-- /Preview plantill modal -->

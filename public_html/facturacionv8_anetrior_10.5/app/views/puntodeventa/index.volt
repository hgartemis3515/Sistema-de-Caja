<style>
.btn-success, .btn-default {
    box-shadow: none;
}
.navigation .content {
    padding: 0 20px 40px 20px;
}
.content-productos {
    height: 100vh;
    overflow-y: auto;
}
.mt-2{
    margin-top: 1rem;
}
.mb-5 {
    margin-bottom: 5rem!important;
}
.mt-5{
    margin-top: 2rem!important;
}
.nav-sidebar .nav-item-divider {
	margin: .5rem 0;
	height: 1px;
}
.navigation > li ul li a {
	padding-left: 30px;
}
.icon-star-full2:hover{
    color: #7880f0;
}
.icon-star-full2.icon-click{
    color: #7880f0;
}
/* Corrección de select2 */
.select2-results__option[aria-selected=true] {
    background-color: #7880f0;
    color: #fff;
    border-bottom-right-radius: 13px;
    border-bottom-left-radius: 13px;
}
.select2-results > .select2-results__options {
    padding-bottom: 0px;
    max-height: 250px;
    overflow-y: auto;
}
/* Sidebar header */
.header-sidebar {
    text-align: center;
    margin: 0;
    background: #afbecf;
    position: relative;
    padding: .5em 0;
    color: #fff;
}
.header-sidebar h3{
    margin: 0;
}
.header-sidebar .invoice__border {
    width: 100%;
    height: 12px;
    display: block;
    background: url(/facturacionv8/img/play-arrow1.png) repeat 0 0;
    position: absolute;
    bottom: -11px;
}

.header-sidebar .options-buy {
    padding-top: .3em;
}
.header-sidebar .options-buy .dropdown-toggle i {
    background: rgba(255, 255, 255, 0.342);
    border-radius: 50%;
    padding: 7px;
}
.flex-moneda {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    margin-bottom: 1em;
}
/* Sidebar list product */
.content_datos_cliente{
    display: flex;
    justify-content: center;
    align-items: center;
}
.invoice_sub_totales table{
    width: 100%;
}
.invoice_sub_totales td {
    padding: .3rem .5rem;
    border-bottom: .1rem solid #e1e1e1;
}
.type_id.dni{
    display: inline;
}
.type_id {
    width: 80%;
    height: 14px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    margin: 0;
    padding: 0;
    display: inline-block;
}
.invoice-item__container {
    width: 100%;
    height: 400px;
    overflow-y: auto;
}
.invoice-item__product {
    display: grid;
    grid-template-columns: 200px 200px 1fr;
    border-bottom: 1px solid rgba(232,235,240,.6);
    background-color: #fff;
    -webkit-animation-duration: .5s;
    animation-duration: .5s;
    padding: 1rem 0 1rem 1rem;
}
.invoice-item__product:hover {
    background: rgba(232,235,240,.6);
    transition: all .3s;
}
.invoice-item__data{
    cursor: pointer;
}
.invoice-item__data p{
    margin: 0;
}
.item__dislike, .item__like {
    background: #fff;
    top: 10%;
    font-family: Roboto;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 14px;
    margin: 0;
    padding: .4rem 1rem;
    border-radius: 10% 0 0 10%;
    position: absolute;
    right: 10px;
    color: #e8ebf0;
}
.item__quantity {
    position: absolute;
    top: -1.2rem;
    right: -1.2rem;
    width: 3.8rem;
    height: 3.8rem;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: #fff;
    font-weight: 600;
    font-size: 14px;
    border-radius: 3rem;
}
.invoice_item_totales {
    text-align: center;
}
.invoice_item_totales .invoice_item_cantidad {
    display: inline-block;
    min-width: 40px;
    border: .2rem solid #7880f0;
    border-radius: 4.5rem;
    padding: 8px 4px;
    margin: 0 .5em;
}
.invoice_item_name {
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    font-size: 1.7rem;
    font-weight: 500;
}
.invoice-item__price {
    font-size: 15px;
}
.item_totales{
    font-size: 18px;
    align-self: center;
    text-align: right;
}
.item_totales .bin {
    height: 100%;
    margin-left: 1rem;
    background-color: #afbecf;
    border-radius: 4rem;
    padding: 8px 6px;
    color: #fff;
    font-size: 10px;
    cursor: pointer;
}
.invoice_item_totales button:first-child {
    padding: .5em 1.1em;
}
.invoice_item_totales button {
    padding: .5em 1em;
}
.pray-item_total {
    font-size: 17px;
}
.single-date-client {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    margin-top: 1em;
    border-radius: 15px;
    padding: 10px;
    border: 1px dashed #3F51B5;
}

/* Sidebar footer */

.invoice__footer {
    width: 100%;
    bottom: 0;
    background-color: rgb(232,235,240);
    position: absolute;
    bottom: 3em;
    padding: 1rem 2rem;
}
.invoice__cancel{
    background: #afbecf;
    position: absolute;
    width: 100%;
    bottom: 0;
    text-align: right;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 14px;
  
}

.invoice__cancel .bin {
    height: 100%;
    margin-left: 1rem;
    background-color: rgba(232,235,240,.4);
    border-radius: 4rem;
    padding:.5em;
}
.invoice__footer .invoice__totals {
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-size: 20px;
    margin-bottom: 1em;
}
.invoice__pay .bg-indigo{
    padding: 1em;
    margin-bottom: 2em;
}

.pay__left {
    float: left;
}
.pay__right {
    float: right;
}
.select_tipo_doc {
    padding: 0 1em;
}
.single-box-cancel{
    position: relative;
    padding: 1em;
}
.single-box-cancel .invoice__border {
    width: 100%;
    height: 12px;
    display: block;
    background: url(/facturacionv8/img/play-arrow.png) repeat 0 0;
    position: absolute;
    top: -11px;
    left: 0;
}

/* Thumbnail*/
.content-title-item {
    width: 170px;
    margin: auto;
}
.row {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
}
.thumb {
    width: 100%;
}
.thumbnail {
    border-width: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    display: block;
    margin: 3em auto auto auto;
    cursor: pointer;
    transition: all .8s;
    -moz-transition: all .8s;
    -webkit-transition: all .8s;
    -ms-transition: all .8s;
    -o-transition: all .8s;
    border-radius: 3px;
    margin-top: 2em;
    display: flex;
    align-items: flex-start;
    flex-wrap: wrap;
    width: 100%;
    height: 95%;
    position: relative;
}
/*
.thumbnail:hover {
    transform: translateY(-15px);
    -moz-transform: translateY(-15px);
    -webkit-transform: translateY(-15px);
    -ms-transform: translateY(-15px);
    -o-transform: translateY(-15px);
    transition: all .8s;
    -moz-transition: all .8s;
    -webkit-transition: all .8s;
    -ms-transition: all .8s;
    -o-transition: all .8s;
}
*/
.thumbnail:after {
  content: "";
  height: 4px;
  width: 0;
  background-color: #7880f0;
  position: absolute;
  bottom: 0;
  right: 0;
  z-index: 1;
  transform-origin: left;
  -moz-transform-origin: left;
  -ms-transform-origin: left;
  -webkit-transform-origin: left;
  -o-transform-origin: left;
  transition: all .8s;
  -moz-transition: all .8s;
  -webkit-transition: all .8s;
  -ms-transition: all .8s;
  -o-transition: all .8s;
  border-radius: 20px;
}
.thumbnail:hover:after {
  width: 100%;
  left: 0;
  right: auto;
  transform-origin: right;
  -moz-transform-origin: right;
  -ms-transform-origin: right;
  -webkit-transform-origin: right;
  -o-transform-origin: right;
}
.thumb:not(.thumb-rounded) img {
    border-radius: 3px;
    padding: 1em;
}
.thumbnail .item__name {
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
}
.thumbnail .item__price {
    height: 100%;
    overflow: hidden;
    color: #333;
    font-weight: 700;
    font-size: 18px;
}
/* Sidebar */
.category-content {
    position: initial;
    padding: 20px;
}
.sidebar-content {
    position: initial;
}
.sidebar-default {
    position: relative;
}
.sidebar-dark .nav-sidebar .nav-item-divider, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item-divider {
	background-color: rgba(255,255,255,.1);
}
.type_doc, .number_doc {
    padding: 0!important;
}
.cliente_numerodocumento{
    border-radius: 0;
}
@media only screen and (max-width: 760px) and (min-width: 300px){
    .content {
        padding: 15px 5px 15px 2.5em;
    }
    .content-title-item {
        width: 100px;
    }
    .btn-icon-bar, .nuevo-producto{
        display: none;
    }
    .thumbnail {
        margin: 1em 0;
        width: 100%;
        height: initial;
    }
    .thumb {
        width: 100%;
    }
    .thumb:not(.thumb-rounded) img {
        border-radius: 3px;
        padding: 5px;
        display: none;
    }
    .thumb img{
        display: none;
    }
    .page-content {
        display: table-row;
    }
    .sidebar {
        display: inherit;
        display: table-cell;
        vertical-align: top;
        width: 190px;
    }
   
    .invoice-item__product {
        display: grid;
        grid-template-columns: 1fr;
    }
    .item__dislike, .item__like{
        position: initial;
    }
    .header-sidebar .options-buy {
        position: initial;
        text-align: center;
        display: initial;
    }
    .invoice-item__data p {
        text-align: center;
    }
    .invoice-item__product div {
        margin-top: .5em;
    }
    .invoice__footer .invoice__totals {
        font-size: 14px;
    }
    .invoice_item_totales .invoice_item_cantidad {
        min-width: 2rem;
        padding: 5px 12px;
    }
   
    .pray-item_total{
        display: block;
        margin-bottom: 10px;
    }
    .item_totales {
        text-align: center;
    }
    .item_totales .bin {
        margin-left: 0; 
    }
    .input-select2 .select2-container--default .selection .select2-selection.select2-selection--single {
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
        border-bottom-right-radius: 15px;
        border-top-right-radius: 15px;
    }
    .cliente_numerodocumento{
        border-radius: 15px;
    }
    .page-container{
        min-height: 650px;
    }
    .sidebar .row {
        display: flex;
        justify-content: center;
    }
}
@media (min-width: 769px) {
    .sidebar {
        width: 550px;
    }
    .sidebar-xs .header-highlight .navbar-header .navbar-brand > img {
        display: none;
    }
    #navbar-logo{
        height: 39px!important;
        margin-top: 0px;
    }
}
@media (min-width: 600px) and (max-width: 1024px) {
    .content-title-item {
        width: 135px;
    }
    .page-content {
        display: table-row;
    }
    .sidebar {
        display: inherit;
        display: table-cell;
        vertical-align: top;
        width: 400px;
    }
    .invoice_item_totales .invoice_item_cantidad {
        display: inline-block;
        border: .2rem solid #7880f0;
        border-radius: 4.5rem;
        padding: 5px 12px;
        margin: auto;
        min-width: auto;
    }
    .col-sm-4 {
        width: 33.33333333%;
    }
    .col-sm-6 {
        width: 50%;
    }
    .col-sm-12 {
        width: 100%;
    }
    .thumbnail{
        max-width: 180px;
    }
}
@media only screen and (max-width: 1170px) and (min-width: 900px){
   
    .col-md-12 {
        width: 100%;
    }
    .content-title-item {
        width: 150px;
    }
    .invoice-item__container {
        width: 100%;
        height: 850px;
        overflow-y: auto;
    }
    .sidebar {
        width: 500px;
    }
}
@media(min-width: 2000px){
    .col-xl-2 {
        width: 16.66666667%;
    }
    .content-title-item {
        width: 90%;
        text-align: center;
        padding: 0 3em;
    }
   
}

/* LOADING */
.loading {
    /* position: absolute; */
    top: 50%;
    left: 50%;
}
.loading-bar {
    display: inline-block;
    width: 4px;
    height: 18px;
    border-radius: 4px;
    animation: loading 1s ease-in-out infinite;
}
.loading-bar:nth-child(1) {
    background-color: #3f51b5;
    animation-delay: 0;
}
.loading-bar:nth-child(2) {
    background-color: #2196f3;
    animation-delay: 0.09s;
}
.loading-bar:nth-child(3) {
    background-color: #4caf50;
    animation-delay: .18s;
}
.loading-bar:nth-child(4) {
    background-color: #00bcd4;
    animation-delay: .27s;
}

@keyframes loading {
    0% {
    transform: scale(1);
    }
    20% {
    transform: scale(1, 2.2);
    }
    40% {
    transform: scale(1);
    }
}
/* /LOADING */
</style>

<div class="navbar bg-indigo navbar-default header-highlight navbar-inverse" id="navbar-indigo">
    <div class="navbar-header">
        <a class="navbar-brand" href="/" ><img src="<?php echo $data_empresa['logo_img_291']; ?>" alt="" id="navbar-logo"></a>
        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>
    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
        <p class="navbar-text">
            <span class="label <?php if($tipo_envio_sunat != 'produccion'){ echo 'bg-success'; } else {echo 'bg-primary'; } ?>"><?php echo $tipo_envio_sunat; ?></span>
        </p>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle legitRipple drop-bell" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-bell2"></i>
                        <span class="visible-xs-inline-block position-right">Actividad</span>
                        <span class="status-mark danger-pulse  border-danger-700"></span>
                    
                    </a>

                    <div class="dropdown-menu dropdown-content">
                        <div class="dropdown-content-heading">
                            Actividad
                            <ul class="icons-list">
                                <li><a href="#"><i class="icon-menu7"></i></a></li>
                            </ul>
                        </div>

                        <ul class="media-list dropdown-content-body width-350">
                            <li class="media">
                                <div class="media-left">
                                    <a href="#" class="btn bg-success-400 btn-rounded btn-icon btn-xs legitRipple padding-right-left-1">
                                        <i class="fa fa-refresh fa-1x"></i>
                                    </a>
                                </div>

                                <div class="media-body">
                                    <a href="#schedule">Tienes 
                                        <span class="badge badge-danger badge-inline position-right docs_pendientes_envio"></span> 
                                        pendientes
                                    </a>
                                </div>
                            </li>

                            
                        </ul>
                    </div>
                </li>
                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?php if(!isset($user['url_image'])){echo '/facturacionv8/img/man_default.svg'; } else { echo $user['url_image']; } ?>" alt="">
                        <span><?php echo $user['nombre']?></span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="/facturacionv8/profile"><i class="icon-user-plus"></i> Mi perfil</a></li>
                        <li><a href="#"><i class="icon-coins"></i>Facturación</a></li>
                        <li><a href="#"><i class="icon-cog5"></i>Soporte</a></li>
                        <li class="divider"></li>
                        <li><a href="/facturacionv8/configcompany"><i class="icon-cog5"></i> Configurar empresa</a></li>
                        <li><a href="/facturacionv8/login/logout"><i class="icon-switch2"></i> Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>

            
        </div>
    </div>
</div>
<div class="page-container" id="contenedor_punto_venta">

    <input type="hidden" id="total_productos" name="total_productos" value="<?php echo $total_productos; ?>" >
    <input type="hidden" id="ids_mas_vendidos" name="ids_mas_vendidos" value="<?php echo implode(',', $ids_mas_vendidos); ?>">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->

        <div class="content content-productos mt-5">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <label><i class="icon-cart-add position-left"></i>Aquí puedes buscar y seleccionar tu producto/Servicio!</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn bg-indigo legitRipple" type="button">
                                <i class="icon-search4"></i>
                            </button>
                            <span  class="btn btn-default legitRipple btn-icon-bar"><i class="icon-barcode2"></i></span>
                        </span>
                        <input type="text" name="txt_busqueda_productos" id="txt_busqueda_productos" class="form-control" placeholder="Buscar productos">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vm_agregar_articulo">
                                <i class="icon-plus-circle2 mr-2"></i> <span class="nuevo-producto">Nuevo</span>
                              </button>
                         
                        </span>
                    </div>
                </div>

                <div class="row" id="contenedor_resultados" >
                    
                </div>
            </div>
        </div>
        <!-- /main content -->
        <!-- Main sidebar -->
        <div class="sidebar sidebar-main sidebar-default">
            <div class="sidebar-content">
                <div class="sidebar-category sidebar-category-visible">
                    <div class="category-content no-padding">
                        <div class="header-sidebar">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="select_tipo_doc">
                                        <select class="select2 select_tipo_doc_electronico" name="select_tipo_doc_electronico" id="select_tipo_doc_electronico">
                                            <option value="77" selected>Nota Venta</option>
                                            <option value="03">Boleta</option>
                                            <option value="01">Factura</option>
                                            <option value="88">Cotización</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h3>Ventas</h3>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="options-buy">
                                        <ul class="icons-list text-center">
                                            <li class="dropdown">
                                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">   
                                                    Opciones Avanz.
                                                    <i class="icon-menu3"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a onclick=""  href="javascript:void(0)">Moneda</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice__border"></div>
                        </div>
                        <ul class="navigation navigation-main navigation-accordion" id="sidebar_principal">
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-6 mt-2 type_doc">
										<div class="form-group input-select2">
											<label  class="label-form">
												<i class="icon-user mr-2"></i> 
												Tipo de Doc 
											</label>
                                            <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="cliente_tipo_docidentidad select2" name="cliente_tipo_docidentidad" id="cliente_tipo_docidentidad">
                                                <option value="0">Sin.Doc.</option>
                                                <option value="1">DNI</option>
                                                <option value="6">RUC</option>
                                                <option value="4">CarnetExtranj.</option>
                                            </select>
										</div>
									</div>
									<div class="col-lg-5 col-md-6 col-sm-6 mt-2 number_doc">
										<div class="form-group">
                                            <label  class="label-form">
												<i class="icon-user mr-2"></i>
												<span class="type_id" id="type_id">Documento de Identidad</span> 
											
											</label>
                                            <div class="input-group">
                                                <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cliente_numerodocumento" id="cliente_numerodocumento" placeholder="Número de documento Aquí!" class="form-control cliente_numerodocumento" required>
                                                <span class="input-group-btn">
                                                    <button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
                                                        <i class="icon-search4" id="icon_search_document"></i>
                                                        <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
									</div>
									<div class="col-lg-4 col-md-12 col-sm-12 mt-2">
										<div class="form-group">
											<label  class="label-form">
												<i class="icon-users2 mr-2"></i> 
												Nombre
											</label>
											<input type="text" class="form-control form-control-sm" name="cliente_nombre" id="cliente_nombre" placeholder="Nombre">
										</div>
                                    </div>

                                    <input type="hidden" name="cliente_direccion" id="cliente_direccion" class="cliente_direccion" >
                                    <select style="display: none;" title="Selecciona tu Código de Ubigeo" data-placeholder="Selecciona Tu Código de Ubigeo" class="select_codigoubigeo" name="select_codigoubigeo" id="select_codigoubigeo">
                                    </select>
                                    <input type="hidden" name="numero_celular" id="numero_celular" class="numero_celular" >
                                    <input type="hidden" name="cliente_email" id="" class="cliente_email" >
                                    <input type="hidden" name="cliente_api_foto" value="" id="cliente_api_foto" />
                                    <input type="hidden" name="cliente_api_fecha_nac" value="" id="cliente_api_fecha_nac" />
                                    <input type="hidden" name="cliente_api_sexo" value="" id="cliente_api_sexo" />

                                </div>
                                <div class="row content_datos_cliente" style="display: none;">
                                    <div class="col-lg-10 single-date-client">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><small><span class="font-weight-bold">Dirección:</span>  El paraíso, calle 12 entre 7 y 8</small></div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><small><span class="font-weight-bold">Ubigeo:</span> Lima - Lima - Los Olivos</small></div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><small><span class="font-weight-bold">Email:</span> isaacniamajano@gmail.com</small></div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><small><span class="font-weight-bold">Teléfono:</span> 956295282</small></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-item__container">
                                <div class="content__list-products" id="lista_productos_para_venta">
                                    
                                </div>
                            </div>
                        </ul>
                        <div class="invoice__footer">
                            <div class="invoice__totals">
                                <div class="invoice__total">Sub total</div>
                                <div class="text-right">S/150.00</div>
                                <div class="invoice__total">IGV (18%)</div>
                                <div class="text-right">S/9.72</div>
                            </div>
                            <div class="invoice__pay">
                                <button class="btn bg-indigo btn-lg btn-block">
                                    <span class="pay__left">Vender:</span>
                                    <span class="pay__right"> S/159.72</span>
                                </button>
                            </div>
                        </div>
                        <div class="invoice__cancel" class="button">
                            <div class="single-box-cancel">
                                <div class="invoice__border"></div>
                                <span class="cancel_actionn">Cancelar<span>
                                <span class="bin"> <i class="icon-bin ml-2"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /main sidebar -->
    </div>
    <!-- /page content -->
</div>
<!-- vm_agregar_articulo -->
<div id="vm_agregar_articulo" class="modal fade" tabindex="-1" aria-labelledby="agregar_articulo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Nombre producto</h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-barcode2 mr-2"></i> Código <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="nuevo_producto_codigo" id="nuevo_producto_codigo" class="form-control" placeholder="Código">
                                <span class="input-group-btn">
                                    <button class="btn bg-indigo legitRipple btn_generar_codigo" type="button">
                                        <i class="icon-rotate-ccw3 mr-2"></i> 
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-xs-6" id="content_unidad_medida">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-stairs-up position-left"></i> Unidad de Medida <span class="text-danger">*</span>
                            </label>
                            <select class="id_unidad_medida" name="id_unidad_medida" id="id_unidad_medida">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-xs-6" id="content_icbper">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-bag position-left"></i> ICBPER
                            </label>
                            <div class="checkbox checkbox-switch">
                                <label>
                                    <input name="opcion_afecto_icbper" id="opcion_afecto_icbper" type="checkbox" data-on-text="Si" data-off-text="No" class="switch" data-size="mini">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-6" id="content_precio_compra">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-stack position-left"></i> Precio Compra: 
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
                                <input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_precio_compra" id="nuevo_producto_precio_compra" placeholder="Precio de Compra">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-6 campos_no_editables">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-stack position-left"></i> Stock Inicial <span class="text-danger">*</span>
                            </label>
                            <input type="text" value="0" class="form-control form-control-sm new_prod_inputs_selected" name="nuevo_producto_stock" id="nuevo_producto_stock" placeholder="Stock Actual"> 
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-6" id="content_ganancia_maxima">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-percent position-left"></i> % Descuento:
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon font-weight-bold simbolo_descuento">% </span>
                                <input type="text"   name="" class="form-control ">
                            </div>
                        </div>
                    </div>	
                
                    <div class="col-lg-3 col-md-6 col-xs-6" id="content_precio_compra">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-stack position-left"></i> Sub total 
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon font-weight-bold simbolo_moneda_nuevoproducto">S/.</span>
                                <input type="text" value="0" class="form-control form-control-sm" name="" placeholder="Sub total">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="label-form">
                                <i class="icon-file-text position-left"></i> Detalle/Nota:
                            </label>
                            <textarea name="nota_detalle" type="input" class="nota_detalle form-control" placeholder="Nota"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                           <div class="invoice_sub_totales font-weight-bold">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-right">S/18.00</td>
                                    </tr>
                                    <tr>
                                        <td>Descuento</td>
                                        <td class="text-right">S/0.00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td title="Total (sin impuesto)">Total por item</td>
                                        <td class="text-right">S/18.00</td></tr>
                                    </tfoot>
                                </table>
                           </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
 /basic modal -->
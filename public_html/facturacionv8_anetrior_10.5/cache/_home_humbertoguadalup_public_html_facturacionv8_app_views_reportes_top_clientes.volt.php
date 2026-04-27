<style>

.btn-default{
    color: #333!important;
    background-color: #fff!important;
    background-image: none!important;
    border: 1px solid #ddd!important;
    font-weight: normal!important; 
    padding: 5px 12px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    right: 0px;
}
.overflow-auto{
    overflow: auto;

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
.multiselect-item label {
    display: block;
    margin: 0;
    height: 100%;
    cursor: pointer;
    padding: 8px 12px;
    padding-left: 42px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
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
.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
    border-top: 1px solid #ddd;
}

.table>thead>tr>th {
    border: 1px solid #ddd;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 12px 20px;
    line-height: 1.5384616;
    vertical-align: middle;
    border: 1px solid #ddd;
    text-align: center;
    text-transform: uppercase;
}
</style>
<div class="page-header">
    <div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reporte Top Clientes</span></h4>
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
    <div class="row">
        <div class="col-md-12 col-md-12">
            <div class="panel border-top-indigo" style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body" id="content_panel_topclientes">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                            <span class="text-uppercase">Filtros Avanzados</span> 
                        </legend>
                    </fieldset>
                    <form name="frm_reporte_top_clientes" id="frm_reporte_top_clientes" action="">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-users mr-2"></i>
                                        Vendedor
                                    </label>
                                    <select name="select_vendedor" id="select_vendedor" class="multiselect" multiple="multiple">
                                        <option value="0" selected>Todos</option>
                                        <?php
                                        foreach($lista_usuarios as $usuario) {
                                            echo '<option value="'.$usuario->idusuario.'">'.$usuario->nombre.' '.$usuario->apellido.' COD: '.$usuario->idusuario.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>	
                            </div>
        
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-profile position-left"></i>
                                        Sucursal
                                    </label>
                                    <select name="select_sucursal" id="select_sucursal" class="multiselect" multiple="multiple">
                                        <option value="0" selected>Todos</option>
                                        <?php
                                        foreach($lista_sucursales as $sucursal) {
                                            echo '<option value="'.$sucursal->idsucursal.'">'.$sucursal->nombre.' - '.$sucursal->direccion.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-calendar2 position-left"></i>
                                        Fecha inicio
                                    </label>
                                    <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha" name="fecha_inicio" id="fecha_inicio">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-calendar2 position-left"></i>
                                        Fecha fin
                                    </label>
                                    <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control form-control-sm control_fecha" name="fecha_fin" id="fecha_fin">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-users mr-2"></i>
                                        Top
                                    </label>
                                    <select class="select" name="numero_registros" id="numero_registros">
                                        <option selected value="100">Top 100 Clientes</option>
                                        <option value="10">Top 10 Clientes</option>
                                        <option value="20">Top 20 Clientes</option>
                                        <option value="30">Top 30 Clientes</option>
                                        <option value="50">Top 50 Clientes</option>
                                        <option value="200">Top 200 Clientes</option>
                                        <option value="500">Top 500 Clientes</option>
                                        <option value="1000">Top 1000 Clientes</option>

                                    </select>
                                </div>	
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-cash2 mr-2"></i>
                                        Moneda
                                    </label>
                                    <select name="id_cod_moneda" id="id_cod_moneda" data-placeholder="Selecciona una Moneda..." class="select_minimizado select_criterio_visualizacion id_cod_moneda">
                                    <option value="PEN" selected>Soles</option>
                                    <option value="USD">Dólares</option>
                                </select>
                                </div>	
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="icon-profile position-left"></i> 
                                        Tipo de comprobante
                                    </label>
                                    <select class="multiselect" multiple="multiple" name="select_tipo_comprobante" id="select_tipo_comprobante">
                                        <option value="" selected>Todos</option>
                                        <option value="03">BOLETAS</option>
                                        <option value="01">FACTURAS</option>
                                        <option value="07">NOTAS DE CRÉDITO</option>
                                        <option value="08">NOTAS DE DÉBITO</option>
                                        <option value="77">NOTAS DE VENTAS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label-form">
                                        <i class="fa fa-question-circle mr-2"></i>
                                        Estado
                                    </label>
                                    <select class="multiselect" multiple="multiple" name="select_estado" id="select_estado">
                                        <option value="">Todos</option>
                                        <option value="aceptado" selected>Aceptado</option>
                                        <option value="pendiente" selected>Pendiente</option>
                                        <option value="ticket" selected>Ticket</option>
                                    </select>
                                </div>	
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><i class="icon-file-text2 position-left"></i>Selecciona un Cliente:</label> 
                                    <select class="js-example-basic-single" name="idcliente" id="idcliente">
                                        <option selected value="">Escribe el Número de RUC o Razón Social</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="text-right">
                                    <button class="btn bg-indigo legitRipple btn_generar_reporte_topclientes" type="button">
                                        Generar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div style="margin-top: 25px;"></div>
            <div class="panel border-top-indigo"  style="max-width: 1120px;margin: 0 auto;">
                <div class="panel-body"  id="content_list_clientes">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <i class="icon-pencil5 mr-2" aria-hidden="true"></i>
                            <span class="font-weight-bold text-uppercase">Lista de Top Clientes</span>
                        </legend>
                    </fieldset>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table" id="tbl_list_clientes">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Clientes</th>
                                        <th colspan="5">Documentos</th>
                                        <th rowspan="2">Total</th>
                                        <th rowspan="2">Total_numero</th>
                                        <th rowspan="2">N° Docs</th>
                                        <th colspan="2">Promedio Diario</th>
                                    </tr>
                                    <tr>
                                        <td>F</td>
                                        <td>B</td>
                                        <td>NV</td>
                                        <td>ND</td>
                                        <td>NC</td>
                                        <td>Docs</td>
                                        <td>Monto</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal -->
     <div class="modal fade" id="info_top_client" tabindex="-1" aria-labelledby="info_top_clientLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="info_top_clientLabel">Información Detallada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="body_info_top_client">
                    <div class="content-input">
                        <div class="row">
                            <div class="col-lg-12">
                                <p>
                                    <span class="label-form"><i class="icon-vcard position-left"></i>
                                    Razón social/Nombre Completo: </span>
                                    <span class="text-uppercase font-weight-700" id="info_nombre_cliente">Isaacnia Majano.</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p>
                                    <span class="label-form"><i class="icon-user position-left"></i>
                                    <span id="vm_tipo_doc_identidad">Num Doc.</span></span>
                                    <span class="text-uppercase font-weight-700" id="info_serie_numero">F-001.</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p>
                                    <span class="label-form"><i class="icon-user position-left"></i>
                                    Código</span>
                                    <span class="text-uppercase font-weight-700" id="info_codigo_cliente">FGHFG288</span>
                                </p>
                            </div>
                            <div class="col-lg-12">
                                <p>
                                    <span class="label-form"><i class="icon-list mr-2"></i>
                                    Docs. Seleccionados</span>
                                    <span class="text-uppercase font-weight-700" id="info_docs_seleccionados">Factura, boleta.</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p>
                                    <span class="label-form"><i class="icon-calendar2 position-left"></i>
                                        Fecha inicio</span>
                                    <span class="text-uppercase font-weight-700" id="info_fecha_inicio">02/10/2020</span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p>
                                    <span class="label-form"> <i class="icon-calendar2 position-left"></i>
                                        Fecha fin</span>
                                    <span class="text-uppercase font-weight-700" id="info_fecha_fin">02/10/2020</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tabbable margin-top-20">
                        <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                            <li class="active"><a href="#lista_doc" data-toggle="tab">Lista de Documentos</a></li>
                            <li><a href="#detalle_doc" data-toggle="tab">Detalle Doc.</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="lista_doc">
                                <table id="tbl_lista_doc" class="table">
                                    <thead>
                                      <tr>
                                        <td>Sucursal</td>
                                        <td>Vendedor</td>
                                        <td>Fec.Registro</td>
                                        <td>Fec.Comprobante</td>
                                        <td>Fec.Vencimiento</td>
                                        <td>Tipo Doc.</td>
                                        <td>Serie</td>
                                        <td>Número</td>

                                        <td>C.Pago</td>
                                        <td>NroOperación</td>
                                        <td>F.Depósito</td>
                                        <td>IdBanco</td>
                                        <td>Nom.Banco</td>
                                        
                                        <td>Moneda</td>
                                        <td>T.Gravaas</td>
                                        <td>T.Inafecta</td>
                                        <td>T.Exoneradas</td>
                                        <td>T.Gratuitas</td>
                                        <td>T.Icbper</td>
                                        <td>T.IGV</td>
                                        <td>SubTotal</td>
                                        <td>Total</td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="detalle_doc">
                                <table id="tbl_detalle_doc" class="table">
                                    <thead>
                                      <tr>
                                        <td>Sucursal</td>
                                        <td>Vendedor</td>
                                        <td>Fec.Registro</td>
                                        <td>Fec.Comprobante</td>
                                        <td>Fec.Vencimiento</td>
                                        <td>Tipo Doc.</td>
                                        <td>Serie</td>
                                        <td>Número</td>

                                        <td>C.Pago</td>
                                        <td>NroOperación</td>
                                        <td>F.Depósito</td>
                                        <td>IdBanco</td>
                                        <td>Nom.Banco</td>
                                        
                                        <td>Moneda</td>
                                        <td>T.Gravaas</td>
                                        <td>T.Inafecta</td>
                                        <td>T.Exoneradas</td>
                                        <td>T.Gratuitas</td>
                                        <td>T.Icbper</td>
                                        <td>T.IGV</td>
                                        <td>SubTotal</td>
                                        <td>Total</td>
                                        

                                        <td>descripcion</td>
                                        <td>id_unidad_medida</td>
                                        <td>unidad_medida</td>

                                        <td>Cod Afectación</td>
                                        <td>cantidad</td>
                                        <td>precio</td>
                                        <td>sub_total</td>
                                        <td>igv</td>
                                        <td>icbper</td>
                                        <td>importe</td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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
    <div class="footer text-muted">
        © 2019. <a href="#">Facturación Electrónica</a> by <a href="#" target="_blank"><?php echo $data_empresa['nombre_empresa']; ?></a>
    </div>
</div>

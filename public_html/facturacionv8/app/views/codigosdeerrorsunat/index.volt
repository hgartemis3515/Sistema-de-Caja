<style>
a{
    color: #3F51B5;
}


.page-container::before{ 
content: "";
    position: absolute;
    top: 0; 
    left: 0;
    width: 100%; 
    height: 100%;  
    /* opacity: .1;  */
    z-index: -1;
    background-image: url(https://arpsystem.com.pe/facturacionv8/img/38.jpg);
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed; 
    background-size: cover;
}

.input-login,
.input-control{
    border: 0;
    border-bottom: 1.3px solid #ddd;
}
.input-eye{
    background: transparent;
    color: #3F51B5;
    border: none;
}

.panel {
        border-radius: 2rem;
        border: 0px;
        box-shadow: rgba(33,33,33,.08) 0 4px 24px 5px;
}
.page-container {
    width: 100%;
    display: table;
    table-layout: fixed;
    position: relative;
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
    opacity: .5;
}
.dataTables_filter {
    position: relative;
    display: block;
    float: left;
    margin: 0 0 10px 0px !important;
}
</style>
<!-- Page container -->
<div class="page-container" style="min-height:361.66666412353516px">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">        
            <div class="content">
                <div class="row mt-5">
                    <div class="col-lg-12 col-md-12 mt-5">

                        <div class="panel mt-5" style="max-width: 1120px;margin: 0 auto;">
                            <div class="panel-body" id="content_panel_errores">
                                <fieldset class="text-center">
                                    <legend class="text-bold">
                                        <img src="https://arpsystem.com.pe/facturacionv8/img/logo_facturalaya_pse_iso_500.png" width="400px" class="logo" alt="">
                                        <h5 class="p-0 m-0  mt-20" style="text-transform: initial;">Catálogo de Códigos de Error SUNAT - Sistema de Facturación Electrónica - SEE - OSE</h5>
                                        <p  style="text-transform: initial !important; font-weight: normal !important;">Cuando enviamos documentos electrónicos (facturas, boletas, notas de crédito, notas de débito, guías de remisión, etc) al WebService de SUNAT, esta realiza una serie de validaciones durante el proceso de recepción de los documentos electrónicos mencionados. Los xmls enviados deben cumplir con todas las validaciones según publicación de la Superintendencia Nacional de Aduanas y de Administración Tributaria (SUNAT) (<a href="https://cpe.sunat.gob.pe/node/88" target="_blank">ver Aquí</a>), en caso de no cumplirse genera un tipo de error. A continuación podrás ver cada uno de los errores que podría retornar:</p>
                                    </legend>
                                </fieldset>
                                <div>
                                    <table class="table datatable-basic" id="tbl_lista_errores">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Descripción</th>
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
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->




<div class="page-container">
    <div class="content">
        
    </div>
</div>
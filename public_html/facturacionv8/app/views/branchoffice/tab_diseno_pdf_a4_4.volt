

<link rel="stylesheet" href="/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
<style>
    [class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
        margin-left: 0;
    }
    .plantilla_4{
        position: relative;
        font-size: 14px!important;
    }
  
    .header-5 {
        border-bottom: 1px solid #007bff;
        text-align: center;
        margin-bottom: 10px;
    }
    p{
        margin: 0;
        padding: 0;
    }
    .table-main-head-4 td, .table-main-head-4 th, .tb_resumen_totales_4 td, .tb_resumen_totales_4 th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    .table-main-head-4 th, .tb_resumen_totales_4 th {
        text-align: center;
    }
    
    #columna1{
        margin-left: 0;
        padding-left: 0;
    }
    #ultima_columna{
        margin-right: 0;
        padding-right: 0;
        width: 210px;
    }
        #invoice-total {
        float: right;
    }
    .borde-primary {
        border-top: 1px solid #007bff;
    }
    .bg-theme-primary{
        background: #007bff;
    }
    .contenedor {
        margin: 10px auto;
        float: right;
        width: 100%;
        padding-top: 1em;
    }
    .col-3{
        width: 25%;
        float: left;
    }
    .col-4{
        width: 33.333333333333%;
        float: left;
    }
    .col-6 {
        width: 50%;
        float: left;
    }
    .col-7 {
        width: 55%;
        float: left;
    }
    .col-45 {
        width: 45%;
        float: left;
    }
    .float-right{
        float:right;
    }
    .float-left{
        float:left;
    }
    
    .masthead {
        display: block;
        height: auto;
    }
    .footer-table {
        float: left;
        height: 200px;
        margin: 10px;
        padding: 10px;
        width: 100px;
    }
    .footer-table p{ padding-bottom: 10px}
    .footer-table {
        text-align: right;
    }
    .single-date {
        float: left;
        height: 120px;
        padding: 0 10px;
        width: 33.333333333333%;
    }
    .table-main-head{
        width: 100%;
        border-collapse: collapse;
    }
    .tb_resumen_totales_4 td {
        padding: 8px 10px;
        font-weight: 700;
        border-top: none;
    }

    .resumen_totales_4 {
        height: 230px;
    }
    .border-top {
        border-top: 1px solid #dee2e6!important;
    }
    .pt-1, .py-1 {
        padding-top: .25rem!important;
    }
    .pt-2, .py-2 {
     padding-top: .5rem!important;
    }
    .mt-2, .my-2 {
        margin-top: .5rem!important;
    }
    .mt-3, .my-3 {
    margin-top: 1rem!important;
    }
    .mb-1, .my-1 {
        margin-bottom: .25rem!important;
    }
   
</style>
<div class="modelo_plantila_pdf_a4 plantilla_4">
    <div class="header-5 row">
        <div class="col-lg-6 text-left p-0">
            <h4 class="pt-2" style="font-size: 20px!important">
                Boleta de Venta Electrónica
            </h4>
        </div>
        <div class="col-lg-6 text-right">
            <h4 class="pt-2" style="font-size: 20px!important">B001 - 000001</h4>
        </div>
    </div>
   
    <div class="w-100">
        <div class="col-6">
            <h4 class="text-primary text-uppercase font-weight-700">TuEmpresa SRL</h4>
			<p>Jr. Alfonso Ugarte 3636</p>
			<p>Cajamarca, Cajamarca, Cajamarca</p>
			<p><i class="flaticon-call text-primary mr-2"></i>Telf.: 993222333</p>
			<p><i class="flaticon-envelope mr-2 text-primary"></i>tu.correo.electronico@gmail.com</p>
			<p><i class="flaticon-placeholder-1 mr-2 text-primary"></i>https://tuempresa.com</p>
        </div>
        <div class="col-6 text-center">
            <img style="width: 200px;" src="https://arpsystem.com.pe/facturacionv8/public/img/logo_rectangular_ejemplo.png" class="mb-1">
            <div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
            <h4 class="text-primary text-uppercase  mb-2 mt-4 text-center font-weight-700" style="font-size: 18px!important">R.U.C.  20600000001</h4>
        </div>
    </div>
    
    <div id="servicios" class="contenedor borde-primary">
        <div class="single-date" id="columna1">
           <p><span class="text-primary font-weight-bold">Razón social:</span> NOMBRE DE COMPAÑÍA</p>
           <p><span class="text-primary font-weight-bold">Fecha de Emisión: </span> 11-11-2020 / 11:04 AM</p>
           
        </div>			
        <article class="single-date">
            <p><span class="text-primary font-weight-bold">RUC:</span> 20600000001</p>
            <p><span class="text-primary font-weight-bold">Dirección:</span> Jr. Alfonso Ugarte 3636</p>
        </article>			
        <article class="single-date text-left">
            <p><span class="text-primary font-weight-bold">Tipo de modena:</span> Soles</p>
            <p><span class="text-primary font-weight-bold">Condición de pago:</span> Al Contado</p>
        </article>			
    </div>
    <section>
        <table class="table-main-head-4">
            <tbody>
                <tr class="bg-theme-primary text-white text-uppercase">
                    <th>Cant.</th>
                    <th width="500px">Descripción</th>
                    <th>Precio Unitario</th>
                    <th>UNID/MED </th>
                    <th>AFECT.IGV</th>
                    <th>Importe</th>
                </tr>
                <tr>
                    
                </tr><tr style="border: none !important;">
                    <td class="text-right">1</td>
                    <td class="cabecera_detalle_items" style="text-align: left;"><div style="width: 330px !important;">pan integral</div></td>
                    <td class="font-weight-700 text-right">S/ 0.1</td>
                    <td>KILOGRAMOS</td>
                    <td>Gravado</td>
                    <td class="text-right">S/ 0.10</td>
                </tr>
                
                <tr>
                    <td colspan="6" class="text-uppercase text-center">SON CERO  CON 10/100 SOLES</td>
                </tr>
            </tbody>
        </table>
        <div class="resumen_totales_4">
            <div class="col-7">
                <img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-3 mt-3 mb-3">
                <p class="mb-1 mt-3"><span class="text-primary font-weight-700">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
                <p><span class="text-primary font-weight-700">HASH: </span>  AEBQR0/vALgFCGdLduG7dwN5C0o=</p>
                <p><span class="text-primary font-weight-700">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
                <p class="mb-3">Representación Impresa de la Factura Electrónica</p>
                
                <p class="font-weight-700 text-theme-default">
                    Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
                </p>
            </div>
            <div class="col-5 float-right">
                <table class="tb_resumen_totales_4 float-right">
                    <tbody>
                        <tr>
                            <td class="text-primary text-right">Gravada:</td>
                            <td class="text-right">
                                <span class="simbolo_moneda">S/</span> 0.08
                            </td>
                        </tr>

                        <tr>
                            <td class="text-primary text-right">IGV (18.00%):</td>
                            <td class="text-right">
                                <span class="simbolo_moneda">S/</span> 0.02
                            </td>
                        </tr>

                        <tr>
                            <td class="text-primary text-right">Descuento Total:</td>
                            <td class="text-right">
                                <span class="simbolo_moneda">S/</span> 0.00
                            </td>
                        </tr>

                        <tr>
                            <td class="text-primary text-right">Total a Pagar:</td>
                            <td class="text-right">
                                <span class="simbolo_moneda">S/</span> 0.10
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <footer class="mt-3 pt-1 pb-5" style="border-top:  1px solid #dddddd;">
        <p class="text-primary font-weight-700 mb-1 mt-1 ml-3">Cuentas:</p>
        <div class="row">
            <div class="col-lg-4 pl-3 pb-3">
                <p class="tabla_cuentas_4"><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/logobcp.jpg" width="50" class="mr-2">
                    BCP - Cuenta de Ahorro</p>
                    <p class="tabla_cuentas_4"><span class="font-weight-700">Titular:</span> TUEMPRESA SRL</p>
                    <p class="tabla_cuentas_4"><span class="font-weight-700">N° cuenta:</span> 24596035269047 - <span class="font-weight-700">CCI:</span> 00224519603526904797</p>
            </div>
        </div>
    </footer>
</div>
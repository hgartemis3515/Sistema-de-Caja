


<link rel="stylesheet" href="/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<style>
    .plantilla_7 [class^="flaticon-"]:before, .plantilla_7 [class*=" flaticon-"]:before, .plantilla_7 [class^="flaticon-"]:after, .plantilla_7 [class*=" flaticon-"]:after {
        margin-left: 0;
    }
    .plantilla_7{
        font-family: "Montserrat", sans-serif;	
        font-size: 14px!important;
    }
    
    .plantilla_7 footer p{
        margin: 0;
        padding: 0;
        font-size: 14px;
    }
    .plantilla_7 table{
        width: 100%
    }
    .plantilla_7 table td, .plantilla_7 table  th {
        border-bottom: 1px solid #dddddd;
        text-align: left;
        padding: 10px 5px 10px 15px;
    }
    .plantilla_7 p{
        margin:0;
        padding: 0;
    }
    .table-main-head-7 tr:nth-child(even),  .tb_resumen_totales-7 tr:nth-child(even), .table-cuentas-7 tr:nth-child(even){
        background-color: #f0f0f0;
    }
    #invoice-total-7 {
        width: 260px;
        position: relative;
        font-weight: 700;
        float: right!important;
    }
    .border-bottom-black {
        border-bottom: 1px solid #000;
        position: absolute;
        bottom: em;
        right: 0;
        z-index: 2;
        width: 100%;
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
    .footer-table-7 {
        height: 200px;
        width: 100px;
    }
    .footer-table-7 p{ padding-bottom: 10px}
    
    .img-header-7 {
        padding-top: 2em;
    }
    .method-price-7, #invoice-total-7, .footer-table-7 {
        float: left;
        margin: 10px;
        padding: 10px;
        position: relative;
    }
    .method-price-7{
        /*height: 200px;*/
        width: 500px;
    }
    .method-price-7 p{
        padding: 0;
        margin: 0;
    }
    .invoice-number-7{
        position: absolute;
        top: 1em;
        right: 1em;
    }
    .invoice-date-7{
        position: absolute;
        bottom: -1.3em;
        right: 1em;
    }
    .masthead-7 {
        display: block;
        height: 50px;
        position:relative;
        margin-bottom: 2.5em;
        z-index: 1;
    }
    .masthead-7::before{
        content: " ";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 400px;
        background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/banner-verde.jpg);
        background-repeat: no-repeat;
        z-index: -1;
    }
    .text-theme-default-7{
        color: #77af18;
    }
    .text-theme-secundary{
        color: #333333;
    }
    .bg-theme-default-7{
        background: #77af18;
    }
    .table-cuentas-7 {
        float: right;
        font-size: 10px;
    }
    .table-cuentas-7 td, .table-cuentas-7 th {
        padding: 5px!important;
    }
    .empresa-info-7{
        height: 250px;
        margin-bottom: 1.5em;
    }
    .tb_resumen_totales-7 {
        width: auto;
    }
    .tb_resumen_totales-7 td {
        padding: 8px 10px 8px 30px;
        font-weight: 700;
    }
    .resumen_totales-7 {
        height: 150px;
    }
    .margin-top-5 {
        margin-top: 4em!important;
    }
</style>
<div class="modelo_plantila_pdf_a4 plantilla_7">
    <div class="masthead-7">
        <div class="">
            <img src="https://arpsystem.com.pe/facturacionv8/public/img/logo_rectangular_ejemplo.png" class="mb-3" style="width: 200px;" alt="">
        </div>
        <div class="invoice-number-7">
            <h5 class="text-white text-uppercase font-weight-700" style="font-size:18px!important;">Factura de Venta Electrónica: F001 - 000393</h5>
        </div>
    </div>
    <div class="empresa-info-7 w-100">
        <div class="col-6">
            <h5 class="text-theme-default-7 font-weight-700 text-uppercase mb-2 mt-3">TuEmpresa SRL</h5>
            <p>Jr. Alfonso Ugarte 3636</p>
            <p>Cajamarca, Cajamarca, Cajamarca</p>
            <p><i class="flaticon-call text-theme-default-7 mr-2"></i>Telf.: 993222333</p>
            <p><i class="flaticon-envelope mr-2 text-theme-default-7"></i>tu.correo.electronico@gmail.com</p>
            <p><i class="flaticon-placeholder-1 mr-2 text-theme-default-7"></i>https://tuempresa.com</p>
        </div>
        <div class="col-6">
            <div class="client-info">
                <p><span class="text-theme-default-7 font-weight-700">Razón social:</span>  AGRO TRANSPORTE CORAQUILLO HNOS E.I.R.L.</p>
                <p><span class="text-theme-default-7 font-weight-700">RUC:</span> 20569273103</p>
                <p><span class="text-theme-default-7 font-weight-700">Dirección: </span> MZA. 39 LOTE. 6 SAN JACINTO   ÁNCASH -  SANTA -  NEPEñA</p>
            </div>
            <hr>
            <p><span class="text-theme-default-7 font-weight-700">Fecha de Emisión:</span> 11-11-2020 / 11:04 AM</p>
            <p><span class="text-theme-default-7 font-weight-700">Condición de pago:</span> 
            Al Contado</p>
            <p><span class="text-theme-default-7 font-weight-700">Tipo de modena:</span> Soles.</p>
        </div>
    </div>

    <section class="table-content-7">
        <table class="table-main-head-7 mt-2">
            <tbody>
                <tr class="text-uppercase bg-theme-default-7  font-weight-700 text-white text-center">
                    <th>Cant.</th>
                    <th width="450px">Descripción</th>
                    <th width="150px">Precio</th>
                    <th>Unid/Med</th>
                    <th>Afect. IGV</th>
                    <th>Importe</th>
                </tr>
                <tr style="border: none !important;">
                    <td class="text-right">1</td>
                    <td class="cabecera_detalle_items" style="text-align: left;"><div style="width: 330px !important;">Café Colombiano 1kg</div></td>
                    <td  class="font-weight-700 text-right">S/ 5</td>
                    <td>KILOGRAMOS</td>
                    <td>Gravado</td>
                    <td class="text-right">S/ 5.00</td>
                </tr>
                <tr style="border: none !important;">
                    <td class="text-right">1</td>
                    <td class="cabecera_detalle_items" style="text-align: left;"><div style="width: 330px !important;">Producto 2</div></td>
                    <td  class="font-weight-700 text-right">S/ 5</td>
                    <td>KILOGRAMOS</td>
                    <td>Gravado</td>
                    <td class="text-right">S/ 5.00</td>
                </tr>
                <tr style="border: none !important;">
                    <td class="text-right">1</td>
                    <td class="cabecera_detalle_items" style="text-align: left;"><div style="width: 330px !important;">Producto 3</div></td>
                    <td  class="font-weight-700 text-right">S/ 5</td>
                    <td>KILOGRAMOS</td>
                    <td>Gravado</td>
                    <td class="text-right">S/ 5.00</td>
                </tr>
                <tr style="border: none !important;">
                    <td class="text-right">1</td>
                    <td class="cabecera_detalle_items" style="text-align: left;"><div style="width: 330px !important;">Producto 4</div></td>
                    <td  class="font-weight-700 text-right">S/ 5</td>
                    <td>KILOGRAMOS</td>
                    <td>Gravado</td>
                    <td class="text-right">S/ 5.00</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-uppercase text-center">SON CINCO  CON 00/100 SOLES</td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-price-footer-7">
            <div class="row">
                <div class="col-lg-6">
                    <div class="method-price-7">
                
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="resumen_totales-7">
                        <table class="tb_resumen_totales-7 float-right">
                            <tbody class="text-right">
                                <tr>
                                    <td class="text-theme-default-7">Gravada:</td>
                                    <td class="text-right">
                                        <span class="simbolo_moneda">S/</span> 4.24
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-theme-default-7">IGV (18.00%):</td>
                                    <td class="text-right">
                                        <span class="simbolo_moneda">S/</span> 0.76
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-theme-default-7">Descuento Total:</td>
                                    <td class="text-right">
                                        <span class="simbolo_moneda">S/</span> 0.00
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-theme-default-7">Total a Pagar:</td>
                                    <td class="text-right">
                                        <span class="simbolo_moneda">S/</span> 5.00
                                    </td>
                                </tr>
        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="margin-top-5 row">
        <div class="col-lg-7">
            <div class="single-footer-wight-7 box-main-7">
                <img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-2  mb-3">
                <p class="mb-1 mt-3"><span class="text-theme-default-7 font-weight-700">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
                <p><span class="text-theme-default-7 font-weight-700">HASH: </span>  7nxI2aSavib4X0ghH+k0P/mRiWY=</p>
                <p><span class="text-theme-default-7 font-weight-700">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
                <p class="mb-3">Representación Impresa de la Factura Electrónica</p>
                
                <p class="font-weight-700 text-theme-default-7">
                    Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
                </p>
            </div>
        </div>
        <div class="col-lg-5">
            <table class="table-cuentas-7">
                <tbody>
                    <tr class="bg-theme-default-7  text-white text-uppercase">
                        <th>Banco</th>
                        <th>Moneda</th>
                        <th>CTA CTE</th>
                        <th>CCI</th>
                    </tr>
                    <tr class="text-center">
                        <td><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/logobcp.jpg" width="50" class="mr-2 img-fluid">
                        </td>
                        <td>Cuenta Ahorro</td>
                        <td>24596035269047</td>
                        <td>00224519603526904797</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </footer>
</div>


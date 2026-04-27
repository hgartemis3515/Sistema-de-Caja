
<link rel="stylesheet" href="/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">

<style>
@import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap");
    .plantilla_5 {
        font-family: "Open Sans",Tahoma,Geneva,sans-serif;
        position: relative;
        font-size: 14px!important;
    }
    
    .plantilla_5 table {
        font-size: 14px!important;
    }
    ul {
        list-style-type: circle;
        margin-right: 0!important;
        padding: 0 0 0 10px;
    }
     .table-main-head-5 th,  .table-cuentas-5 th {
        font-weight: 700;
    }
    .table-main-head-5 td, .table-main-head-5 th, .table-cuentas-5 td, .table-cuentas-5 th {
        border-bottom: 1px solid #dddddd;
        padding: 10px 2px 10px 10px;
    }
    .table-main-head-5 tr:nth-child(even), .table-cuentas-5 tr:nth-child(even){
        background-color: #eee;
    }
    p{
        margin: 0;
        padding: 0;
    }
    .border-invoice-bottom{
        border-bottom: 1px solid #ddd;
    }
    .col-3{
        width: 25%!important;
        float: left;
    }
    .col-4{
        width: 33.333333333333%!important;
        float: left;
    }
    .col-6 {
        width: 50%!important;
        float: left;
    }
    .col-7 {
        width: 75%!important;
        float: left;
    }
    .col-8{
        width: 66.666667%;
    }
    .invoice-total-5 {
        width: 100%;
        height: 150px;
    }
    .display-inline{
        display: inline;
    }
    
    .footer-table-5 {
        float: right;
        margin: 10px;
        padding: 0 10px;
        width: 250px;
        text-align: left;
    }
    .footer-table-5 p{ padding-bottom: 10px}
    .header-main-5{
        width: 100%;
        height: 300px;
    }
    .item-media {
        text-align: left;
        font-size: 12px;
    }
    .item-media i {
        float: left;
        padding: 9px 3px;
        border-radius: 20px;
        border: 1px solid #ddd;
        text-align: center;
        margin-right: 10px
    }
    .header-main-5 .col-lg-3 [class^="flaticon-"]:before, .header-main-5 .col.header-main-5 .col-lg-3 [class*=" flaticon-"]:after {
        margin: 00px;
        text-align: center;
        font-size: 15px;
        color: #007bff;
    }
    .letter-spacing-1{
        letter-spacing: 2;
    }
    .masthead-5 {
        position: relative;
        border-top: 1px dashed #007bff;
        padding-top: 2em;
    }
    .masthead-5 .img-header{
        position: absolute;
        top: 0;
        right: 0;
    }
    .single-header1, .single-header2, .single-date-5, .info-total-5, .date-price-5, .single-footer-wight, .social-media{
        float: left;
      
    }
    
    .single-date-5 {
        width: 33.333333333333%;
        padding: 0 10px;
        margin: 10px 10px 10px 0;
    }
    .single-date-client-5{
        border-top: 1px dashed #007bff;
        padding-top: 2em;
    }

    .single-footer-wight.box-main{
        /*width: 600px;*/
        padding: 0px 10px 10px 0;
        margin: 0px 10px 10px 0;
    }
    .table-main-head-5{
        width: 100%;
        border-collapse: collapse;
    }
    .sub-footer-5 {
        border-bottom: 1px dashed #007bff;
        height: 220px;
    }
    .position-initial {
        position: initial!important;
        width: 100px!important;
    }
    .price {
        text-align: right;
        float: right;
    }

    .info-total-5{
        height: 100px;
        width: 250px;
    } 
    .date-price-5 {
        width: 600px;
        float: right;
    }
    .social-media {
        width: 200px;
        float: left;
        padding: 0;
        margin: 5px 0;
    }
    .table-cuentas-5 {
        float: right;
        font-size: 12px;
    }
    .table-cuentas-5 td, .table-cuentas-5 th {
        padding: 0px 5px;
    }
</style>
<div class="modelo_plantila_pdf_a4 plantilla_5">
    <div class="masthead-5">
        <img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p-2.png" class="img-header">
        <div class="header-main-5">
            <div class="col-3 col-lg-3">
                <img src="https://arpsystem.com.pe/facturacionv8/public/img/logo_rectangular_ejemplo.png" style="width: 180px;">
                <h3 class="font-weight-light text-primary mt-3">TuEmpresa SRL</h3>
                <p>R.U.C.: 20604209987</p>
                <p>Jr. Alfonso Ugarte 3636</p>
                <p>Cajamarca, Cajamarca, Cajamarca</p>
                <p><i class="flaticon-call text-primary mr-2"></i>Telf.: 993222333</p>
                <p><i class="flaticon-envelope mr-2 text-primary"></i>tu.correo.electronico@gmail.com</p>
                    <p><i class="flaticon-placeholder-1 mr-2 text-primary"></i>https://tuempresa.com</p>
            </div>			
            <div class="pl-3 col-7 col-lg-7">
                <h4 class="pt-2 font-weight-light text-uppercase" style="line-height: 1.5">Factura de Venta Electrónica: <br> <span  class="bg-theme-primary text-white mt-2">F001 - 000393</span></h4> 
                <div id="date_invoice">
                    <div class="single-date-5">
                        <p class="text-primary font-weight-700">Fecha emisión:</p>
                        <p> 11-11-2020 / 11:04 AM</p>
                    </div>	
                    <div class="single-date-5">
                        <p class="text-primary font-weight-700">Cond. de pago:</p>
                        <p>Al Contado</p>
                    </div>
                    <div class="single-date-5">
                        <p class="text-primary font-weight-700">Moneda:</p>
                        <p>Soles</p>
                    </div>
                </div>		
            </div>	
        </div>
    </div> 
    <div class="single-date-client-5 mb-3 mt-2" style="height: 100px">
        <div class="col-6">
            <p><span class="text-primary font-weight-700">Razón social:</span> NOMBRE DE COMPAÑÍA</p>
            <p><span class="text-primary font-weight-700">RUC:</span> 20604209987</p>
            <p><span class="text-primary font-weight-700">Dirección:</span> Jr. Alfonso Ugarte 3636</p>
        </div>
        <div class="col-6">
            
            
        </div>
    </div>	
    <div class="table-invoice mt-2 pt-2">
        <table class="table-main-head-5 mt-3">
            <tbody>
                <tr class="bg-theme-primary text-white">
                    <th>Cant.</th>
                    <th width="450px">Descripción</th>
                    <th>Precio</th>
                    <th>Unid/Med</th>
                    <th>Afect. IGV</th>
                    <th>Importe</th>
                </tr>
            
                <tr style="border: none !important;">
                    <td class="text-right">1</td>
                    <td class="cabecera_detalle_items" style="text-align: left;"><div style="width: 330px !important;">Café Colombiano 1kg</div></td>
                    <td  class="font-weight-700" text-right">S/ 5</td>
                    <td>KILOGRAMOS</td>
                    <td>Gravado</td>
                    <td class="text-right">S/ 5.00</td>
                </tr>
            
                <tr>
                    <td colspan="6" class="text-uppercase">SON CINCO  CON 00/100 SOLES</td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-total-5">
            <div class="info-total-5 mt-3">
                
            </div>
            <div class="date-price-5">
                <div class="footer-table-5 font-weight-700 text-uppercase mr-0 pr-0">
                    <p class="border-invoice-bottom">Gravada: <span class="price ml-5">S/ 4.24</span> </p>
                </div>

                <div class="footer-table-5 font-weight-700 text-uppercase mr-0 pr-0">
                    <p class="border-invoice-bottom">IGV (18.00%): <span class="price ml-5">S/ 0.76</span> </p>
                </div>

                <div class="footer-table-5 font-weight-700 text-uppercase mr-0 pr-0">
                    <p class="border-invoice-bottom">Descuento: <span class="price ml-5">S/ 0.76</span> </p>
                </div>

                <div class="footer-table-5 font-weight-700 text-uppercase mr-0 pr-0">
                    <p class="border-invoice-bottom"><span class="bg-theme-primary text-white">Total:</span> <span class="price ml-5">S/ 5.00</span> </p>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="sub-footer-5">
            <div class="col-8">
                <div class="single-footer-wight box-main pr-2">
                    <img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-3 mt-3 mb-3">
                    <p class="mb-1 mt-3"><span class="text-primary font-weight-700">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
                    <p><span class="text-primary font-weight-700">HASH: </span>  7nxI2aSavib4X0ghH+k0P/mRiWY=</p>
                    <p><span class="text-primary font-weight-700">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
                    <p class="mb-3">Representación Impresa de la Factura Electrónica</p>
                                    
                    <p class="font-weight-700 text-theme-default">
                        Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
                    </p>
                </div>
            </div>
            <div class="col-4 pl-2">
                <table class="table-cuentas-5 pl-2">
                    <tbody>
                        <tr>
                            <th>BANCO</th>
                            <th>TIPO CTA</th>
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
        </div>
    </footer>
</div>

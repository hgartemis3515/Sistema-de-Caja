

    

<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">

<style>
[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
    margin-left: 0px;
}

.plantilla_6{
    position: relative;
    font-size: 14px!important;
}

.plantilla_6 p{
    margin: 0;
    padding: 0;
}
.tb_resumen_totales_6 td, .tb_resumen_totales_6 th, .table-main-head-6 td, .table-main-head-6 th, .table-cuentas-6 th, .table-cuentas-6 td {
    text-align: left;
    padding: 8px 0 8px 8px;
}
.table-main-head-6 td, .table-main-head-6 th {
    text-align: center;
    padding: 8px 0 8px 8px;
}
.tb_resumen_totales_6 th, .table-cuentas-6 th {
    text-align: center;
}
#columna1{
    margin-left: 0;
    padding-left: 0;
}
#ultima_columna-6{
    margin-right: 0;
    padding-right: 0;
    width: 210px;
}
 #invoice-total-6 {
    float: right;
}
.borde-theme-default {
    border-bottom: 1px solid #2f80c1;
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
.contenedor {
    margin: 10px auto;
    float: right;
    width: 100%;
    padding-top: 1em;
}
.float-right{
    float:right;
}
.float-left{
    float:left;
}
.head-1 {
    width: 250px;
}
.plantilla_6 {
    display: block;
    position:relative;
    z-index: 1;
}
.plantilla_6::before{
    content: " ";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 800px;
    background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p3-2.jpg);
    background-repeat: no-repeat;
    background-position: center right;
    z-index: -1;
}

.footer-table-6 {
    float: left;
    height: 200px;
    margin: 10px;
    padding: 10px;
    width: 100px;
}
.footer-table-6 p{ padding-bottom: 10px}
.footer-table-6 {
    text-align: right;
}
.single-date-6 {
    float: left;
    height: 120px;
    padding: 10px;
    width: 33.333333333333%;
}
.table-main-head-6{
    width: 100%;
    border-collapse: collapse;
}

.text-theme-default-6{
    color: #2f80c1;
}
.title-main-6{
    font-family: "Dancing Script", cursive;
}
.bg-theme-default-6{
    background-color: #2f80c1;
}
.table-cuentas-6 {
    float: right;
    font-size: 12px;
}
.table-cuentas-6 td, .table-cuentas-6 th {
    padding: 5px 10px;
}
.pt-3, .py-3 {
    padding-top: 1rem!important;
}
.pl-2, .px-2 {
    padding-left: .5rem!important;
}
.mb-4, .my-4 {
    margin-bottom: 1.5rem!important;
}
</style>
<div class="modelo_plantila_pdf_a4 plantilla_6">
    <div class="masthead-6 row borde-theme-default">
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
        <div class="col-6 pl-2 pt-3">
            <h4 class="text-theme-default-6 text-uppercase font-weight-700">TuEmpresa SRL</h4>
			<p>Jr. Alfonso Ugarte 3636</p>
			<p>Cajamarca, Cajamarca, Cajamarca</p>
			<p><i class="flaticon-call text-theme-default-6 mr-2"></i>Telf.: 993222333</p>
			<p><i class="flaticon-envelope mr-2 text-theme-default-6"></i>tu.correo.electronico@gmail.com</p>
			<p><i class="flaticon-placeholder-1 mr-2 text-theme-default-6"></i>https://tuempresa.com</p>
        </div>
        <div class="col-6 text-center pl-2 pt-3">
            <img style="width: 200px;" src="https://arpsystem.com.pe/facturacionv8/public/img/logo_rectangular_ejemplo.png" class="mb-1">
            <div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
            <h4 class="text-theme-default-6 text-uppercase  mb-2 mt-4 text-center font-weight-700" style="font-size: 18px!important">R.U.C.  20600000001</h4>
        </div>
    </div>
    <div id="servicios" class="contenedor borde-primary">
        <div class="single-date-6" id="columna1">
        <p><span class="text-theme-default-6 font-weight-700">Razón social:</span> NOMBRE COMPAÑIA</p>
        <p><span class="text-theme-default-6 font-weight-700">Fecha de Emisión: </span> 11-11-2020 / 11:04 AM</p>
        </div>			
        <article class="single-date-6">
            <p><span class="text-theme-default-6 font-weight-700">RUC:</span> 20600000001</p>
            <p><span class="text-theme-default-6 font-weight-700">Dirección:</span> Jr. Alfonso Ugarte 3636</p>
            
        </article>			
        <article class="single-date-6 text-left">
            <p><span class="text-theme-default-6 font-weight-700">Tipo de modena:</span> Soles</p>
            <p><span class="text-theme-default-6 font-weight-700">Condición de pago:</span> Al Contado </p>
        </article>			
    </div>
    <section>
        <table class="table-main-head-6 mt-4">
            <tbody>
                <tr class="bg-theme-default-6  text-white text-uppercase">
                    <th>Cant.</th>
                    <th width="500px">Descripción</th>
                    <th>Precio</th>
                    <th>UNID/MED </th>
                    <th>AFECT.IGV</th>
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
                <tr>
                    <td colspan="6" class="text-uppercase text-center">SON CINCO  CON 00/100 SOLES</td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-6 float-right">
                <div class="resumen_totales_6">
                    <table class="tb_resumen_totales_6 float-right mb-5">
                        <tbody class="font-weight-700 text-right">
                            <tr>
                                <td class="text-theme-default-6 text-right">Gravada:</td>
                                <td class="text-right">
                                    <span class="simbolo_moneda">S/</span> 4.24
                                </td>
                            </tr>
                            <tr>
                                <td class="text-theme-default-6 text-right">IGV (18.00%):</td>
                                <td class="text-right">
                                    <span class="simbolo_moneda">S/</span> 0.76
                                </td>
                            </tr>
                            <tr>
                                <td class="text-theme-default-6 text-right">Descuento Total:</td>
                                <td class="text-right">
                                    <span class="simbolo_moneda">S/</span> 0.00
                                </td>
                            </tr>
                            <tr>
                                <td class="text-theme-default-6 text-right">Total a Pagar:</td>
                                <td class="text-right">
                                    <span class="simbolo_moneda">S/</span> 5.00
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       
    </section>
    <footer class="row">
        <div class="col-lg-6">
            <div class="single-footer-wight box-main mb-2">
                <img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-3 mt-3 mb-3">
                <p class="mb-1 mt-3"><span class="text-theme-default-6 font-weight-700">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
                <p><span class="text-theme-default-6 font-weight-700">HASH: </span>  7nxI2aSavib4X0ghH+k0P/mRiWY=</p>
                <p><span class="text-theme-default-6 font-weight-700">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
                <p class="mb-5">Representación Impresa de la Factura Electrónica</p>
                <p class="font-weight-700 text-theme-default-6">
                    Representación Impresa de Documento Electrónico Generado En Una Versión de Pruebas. No tiene Validez!
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <table class="table-cuentas-6">
                <tbody>
                    <tr class="bg-theme-default-6  text-white text-uppercase">
                        <th>Banco</th>
                        <th>Moneda</th>
                        <th>CTA CTE</th>
                        <th>CCI</th>
                    </tr>
                    <tr class="text-center">
                        <td><img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/logobcp.jpg" width="50" class="mr-2 img-fluid">
                </td>
                        <td>Cuenta de Ahorro</td>
                        <td>24596035269047</td>
                        <td>00224519603526904797</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </footer>
</div>


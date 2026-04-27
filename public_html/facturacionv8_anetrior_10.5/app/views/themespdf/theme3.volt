<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
		<title>Rojo Polo</title>
	</head>
		<style>
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			body{
				position: relative;
			}
		
				header {
				border-bottom: 1px solid #007bff;
				text-align: center;
				height: 40px;
				margin-bottom: 3em;
			}
			p{
				margin: 0;
				padding: 0;
			}
			td, th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
			}
			th {
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
			.contenedor {
				margin: 10px auto;
				float: right;
				width: 100%;
				padding-top: 1em;
			}
			.col-3{
				width: 25%;
				float: left;
				padding: 0;
			}
			.col-4{
				width: 33.333333333333%;
				float: left;
				padding: 0;
			}
			.col-6 {
				width: 50%;
				float: left;
				padding: 0;
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
				height: 170px;
				padding: 10px;
				width: 33.333333333333%;
			}
			.table-main-head{
				width: 100%;
				border-collapse: collapse;
			}
			footer {
				position: absolute;
				bottom: 0%;
				border-top: 1px solid #007bff;
				width: 100%;
			}
		</style>
	<body>
		<header>
			<h4 class="pt-2 float-left">
				Factura Eléctronica de Venta
			</h4>
			<h4 class="pt-2 float-right">B001-0000006</h4>
		</header>
		<div class="masthead">
			<div class="col-6  mb-4">
				<h4 class="text-primary text-uppercase mb-2">
				SOFTHY SOLUCIONES EN SOFTWARE S.A.C. - SOFTHY S.A.C.</h4>
				<p>Jr. Alfonso Ugarte 3636.</p>
				<p>Cajamarca, Cajamarca, Cajamarca.</p>
				<p><i class="flaticon-call text-primary mr-2"></i>Telf.: 956295282</p>
				<p><i class="flaticon-envelope mr-2 text-primary"></i>aquino.alex25@gmail.com</p>
				<p><i class="flaticon-placeholder-1 mr-2 text-primary"></i> https://facturalaya.com</p>
			</div>
			<div class="col-6 pl-5">
				<img src="https://arpsystem.com.pe/servicio-de-facturacion-electronica/img/logo.png" alt="">
				<div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
				<h4 class="text-primary text-uppercase  mb-2 mt-4 text-center">R.U.C. 20604209987</h4>
			</div>
		</div>
		<div id="servicios" class="contenedor borde-primary">
			<div class="single-date" id="columna1">
				<p class="text-primary font-weight-bold">Cliente:</p>
				<p>AUTOSPORT TPP S.G. S.A.C.</p>
				<p>20604586128</p>
				<p>Jr. Alfonso Ugarte Nro. 1900 Atumpampa</p>
			</div>			
			<article class="single-date">
			<p><span class="text-primary font-weight-bold">Fecha de Emisión: </span> 25-08-2020 / 12:40 PM </p>
				<p><span class="text-primary font-weight-bold">Condición de pago:</span> Al Contado. </p>
				<p><span class="text-primary font-weight-bold">Tipo de modena:</span> Soles.</p>
			</article>			
			<article class="single-date text-right ">
				<p><span class="text-primary font-weight-bold">Orden de compra: </span></p>
				<p><span class="text-primary font-weight-bold">N° de Guía:</span></p>
				<p><span class="text-primary font-weight-bold">Fecha vencimiento:</span> 02/02/2020</p>
			</article>			
		</div>
		<section>
			<table class="table-main-head">
				<tbody>
					<tr class="bg-primary text-white text-uppercase">
						<th>Cant.</th>
						<th width="500px">Descripción</th>
						<th>Precio Unitario</th>
						<th>UNID/MED </th>
						<th>AFECT.IGV</th>
						<th>Importe</th>
					</tr>
					<tr>
						<td>1</td>
						<td>Sweteaters azul talla M</td>
						<td>S/ 80</td>
						<td>KILOGRAMOS</td>
						<td>Gravado</td>
						<td>S/ 80.00</td>
					</tr>
					<tr>
						<td>1</td>
						<td>Sweteaters azul talla M</td>
						<td>S/ 80</td>
						<td>KILOGRAMOS</td>
						<td>Gravado</td>
						<td>S/ 80.00</td>
					</tr>
					<tr>
						<td colspan="6" class="text-uppercase text-center font-weight-bold">SON OCHENTA CON 00/100 SOLES</td>
					</tr>
				</tbody>
			</table>
			<div id="invoice-total">
				<div class="footer-table">
					<p>SubTotal</p>
					<p>IVA 21.0%</p>
					<h5 class="font-weight-bold">Total</h5>
				</div>			
				<article class="footer-table">
					<p>1600.000</p>
					<p>500.000</p>
					<h5 class="font-weight-bold">200.000</h5>
				</article>	
			</div>
		</section>
		<footer>
			<div class="col-6 pb-3">
				<img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-3 mt-3 mb-3">
				<p class="mb-1 mt-3"><span class="text-primary font-weight-bold">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
				<p><span class="text-primary font-weight-bold">HASH: </span> 4gr1BHHIgukS8i4tEBeaQB0RP/g=</p>
				<p><span class="text-primary font-weight-bold">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
			</div>
			<div class="col-6 pl-3 pb-3">
				<p class="text-primary font-weight-bold mb-3 mt-3">Cuentas:</p>
				<p><img src="https://infonegocios.biz/uploads/medidas-bbva.jpg" width="50" class="mr-2"><span class="text-primary font-weight-bold">BCP - Cuenta de Ahorro:</span> 24596035269047. CCI: 00224519603526904797.</p>
				<p><img src="https://infonegocios.biz/uploads/medidas-bbva.jpg" width="50" class="mr-2"><span class="text-primary font-weight-bold">BCP - Cuenta de Ahorro:</span> 24596035269047. CCI: 00224519603526904797.</p>
			</div>
			<div class="w-100 mt-4 pt-4">	
			<p class="font-italic w-100 pt-3">Representación Impresa de la Factura Electrónica
			Representación Impresa de Documento Electrónico Generado En
			Una Versión de Pruebas. No tiene Validez!</p></div>
		</footer>
	</body>
</html>
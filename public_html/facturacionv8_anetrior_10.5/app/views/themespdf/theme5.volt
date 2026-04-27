<html lang="es">
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<title>Rojo Polo</title>
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
	</head>
	<style>
		[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
			margin-left: 0px;
		}

		body{
			position: relative;
			
		}
		body::before{
			content: " ";
			position: absolute;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 300px;
			background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p3-3.jpg);
			background-size: cover;
			background-repeat: no-repeat;
			z-index: -1;
		}
	
		header {
			text-align: center;
			height: 50px;
		}
		p{
			margin: 0;
			padding: 0;
		}
		td, th {
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
		.borde-theme-default {
			border-bottom: 1px solid #2f80c1;
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
		.masthead {
			display: block;
			height: 100px;
			position:relative;
		}
		.masthead::before{
			content: " ";
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 800px;
			background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p3-2.jpg);
			background-repeat: no-repeat;
			backgroud-position: center right;
			z-index: -1;
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
	/*	footer {
			position: absolute;
			bottom: 18%;
			width: 100%;
		}*/
		.text-theme-default{
			color: #2f80c1;
		}
		.title-main{
			font-family: "Dancing Script", cursive;
		}
		.bg-theme-default{
			background-color: #2f80c1;
		}
		.table-cuentas {
			float: right;
			font-size: 12px;
		}
		.table-cuentas td, .table-cuentas th {
			padding: 5px 10px;
		}
	</style>
	<body>
		<header class="borde-theme-default">
			<h4 class="pt-2 float-left">
				Factura Eléctronica de Venta
			</h4>
			<h4 class="pt-2 float-right">B001-0000006</h4>
		</header>
		<div class="masthead">
			<div class="col-6  pl-2 pt-3 mb-4">
				<h4 class="text-theme-default text-uppercase mb-2">
				SOFTHY SOLUCIONES EN SOFTWARE S.A.C. - SOFTHY S.A.C.</h4>
				<p>Jr. Alfonso Ugarte 3636.</p>
				<p>Cajamarca, Cajamarca, Cajamarca.</p>
				<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: 956295282</p>
				<p><i class="flaticon-envelope mr-2 text-theme-default"></i>aquino.alex25@gmail.com</p>
				<p><i class="flaticon-placeholder-1 mr-2 text-theme-default"></i> https://facturalaya.com</p>
			</div>
			<div class="col-6  pt-3 pl-5">
				<img src="https://arpsystem.com.pe/servicio-de-facturacion-electronica/img/logo.png" alt="">
				<div class="border-top mt-2" style="width: 100px; margin: auto;"></div>
				<h4 class="text-theme-default text-uppercase  mb-2 mt-4 text-center">R.U.C. 20604209987</h4>
			</div>
		</div>
		<div id="servicios" class="contenedor borde-primary">
			<div class="single-date pl-2" id="columna1">
				<p class="text-theme-default font-weight-bold">Cliente:</p>
				<p>AUTOSPORT TPP S.G. S.A.C.</p>
				<p>20604586128</p>
				<p>Jr. Alfonso Ugarte Nro. 1900 Atumpampa</p>
			</div>			
			<div class="single-date">
			<p><span class="text-theme-default font-weight-bold">Fecha de Emisión: </span> 25-08-2020 / 12:40 PM </p>
				<p><span class="text-theme-default font-weight-bold">Condición de pago:</span> Al Contado. </p>
				<p><span class="text-theme-default font-weight-bold">Tipo de modena:</span> Soles.</p>
			</div>			
			<div class="single-date text-right ">
				<p><span class="text-theme-default font-weight-bold">Orden de compra: </span></p>
				<p><span class="text-theme-default font-weight-bold">N° de Guía:</span></p>
				<p><span class="text-theme-default font-weight-bold">Fecha vencimiento:</span> 02/02/2020</p>
			</div>			
		</div>
		<section>
			<table class="table-main-head">
				<tbody>
					<tr class="bg-theme-default  text-white text-uppercase">
						<th>Cant.</th>
						<th width="500px">Descripción</th>
						<th>Precio</th>
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
			<div class="col-6">
				<h5 class="text-theme-default font-weight-bold">Observación</h5>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500</p>
			</div>
			<div class="col-6">
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
				</div>
			</div>
			
		</section>
		<footer>
			<div class="col-6">
				<div class="single-footer-wight box-main">
					<img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-3 mb-3">
					<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
					<p><span class="text-theme-default font-weight-bold">HASH: </span> 4gr1BHHIgukS8i4tEBeaQB0RP/g=</p>
					<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
				</div>
			</div>
			<div class="col-6">
				<table class="table-3 table-cuentas">
					<tbody>
						<tr class="bg-theme-default  text-white text-uppercase">
							<th>Banco</th>
							<th>Moneda</th>
							<th>CTA CTE</th>
							<th>CCI</th>
						</tr>
						<tr class="text-center">
							<td><img src="https://infonegocios.biz/uploads/medidas-bbva.jpg" width="50" class="mr-2 p-1 img-fluid"></td>
							<td>PEN</td>
							<td>24596035269047</td>
							<td>00224519603526904797</td>
						</tr>
						<tr class="text-center">
							<td><img src="https://cafetaipa.com/wp-content/uploads/2009/12/nuevo-logo-comercial-interbank-peru1.jpg" width="50" class="mr-2 p-1 img-fluid"></td>
							<td>PEN</td>
							<td>24596035269047</td>
							<td>00224519603526904797</td>
						</tr>
						<tr class="text-center">
							<td><img src="https://d31dn7nfpuwjnm.cloudfront.net/images/valoraciones/0035/1607/Como_saber_mi_estado_de_cuenta_en_el_Banco_de_la_Nacion.jpg?1568725855" width="50" class="mr-2 p-1 img-fluid"></td>
							<td>PEN</td>
							<td>24596035269047</td>
							<td>00224519603526904797</td>
						</tr>
						<tr class="text-center">
							<td><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/BBVAprovinciallogo.svg/1200px-BBVAprovinciallogo.svg.png" width="50" class="mr-2 p-1 img-fluid"></td>
							<td>PEN</td>
							<td>24596035269047</td>
							<td>00224519603526904797</td>
						</tr>
						<tr class="text-center">
							<td><img src="https://www.pega.com/sites/default/files/styles/640/public/media/images/2018-11/scotiabank-logo-color.png?itok=ztG23OU1" width="50" class="mr-2 p-1 img-fluid"></td>
							<td>PEN</td>
							<td>24596035269047</td>
							<td>00224519603526904797</td>
						</tr>
					</tbody>
				</table>
			</div>
		</footer>
	</body>
</html>
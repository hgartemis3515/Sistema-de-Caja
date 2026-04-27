<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
	<title>Document</title>
</head>
<body>
	<style>
		[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
			margin-left: 0;
		}
		body{
			font-family: "Montserrat", sans-serif;	
			position: relative;
		}
		body::before{
			content: " ";
			position: absolute;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 100px;
			background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p4-2.png);
			background-repeat: no-repeat;
			z-index: -1;
		}
		footer{
			position: absolute;
			bottom: 0em;
			left: 0;
			font-size: 12px;
		}
		footer p{
			margin: 0;
			padding: 0;
			font-size: 14px;
		}
		table{
			width: 100%
		}
		td, th {
			border-bottom: 1px solid #dddddd;
			text-align: left;
			padding: 1.5em 15px;
		}
		p{
			margin:0;
			padding: 0;
		}
		tr:nth-child(even) {
			background-color: #f0f0f0;
		}
		#invoice-total {
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
		.footer-table {
			height: 200px;
			width: 100px;
		}
		.footer-table p{ padding-bottom: 10px}
		
		.img-header {
			padding-top: 2em;
		}
		.method-price, #invoice-total, .footer-table {
			float: left;
			margin: 10px;
			padding: 10px;
			position: relative;
		}
		.method-price{
			height: 200px;
			width: 500px;
		}
		.method-price p{
			padding: 0;
			margin: 0;
		}
		.invoice-number{
			position: absolute;
			top: 1.3em;
			right: 1em;
		}
		.invoice-date{
			position: absolute;
			bottom: -1.3em;
			right: 1em;
		}
		.masthead {
			display: block;
			height: 100px;
			position:relative;
			margin-bottom: 2.5em;
		}
		.masthead::before{
			content: " ";
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 400px;
			background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p4-1.png);
			background-repeat: no-repeat;
			backgroud-position: center right;
			z-index: -1;
		}
		.text-theme-default{
			color: #77af18;
		}
		.text-theme-secundary{
			color: #333333;
		}
		.bg-theme-default{
			background: #77af18;
		}
		.table-cuentas {
			float: right;
			font-size: 12px;
		}
		.table-cuentas td, .table-cuentas th {
			padding: 5px 10px;
		}
		.empresa-info{
			height: 250px;
			border-bottom: 1px solid #000;
			margin-bottom: 1.5em;
		}
	</style>
	<div class="masthead">
		<div class="img-header mt-4">
			<img src="https://arpsystem.com.pe/servicio-de-facturacion-electronica/img/logo_footer.png" class="p-3" width="350px" alt="">
		</div>
		<div class="invoice-number">
			<h5 class="text-white text-uppercase font-weight-bold">N° de Factura: #00000</h5>
		</div>
		<div class="invoice-date">
			<h6 class="text-uppercase font-weight-bold">Fecha: 09/08/2020</h6>
		</div>
	</div>
	<div class="empresa-info w-100">
		<div class="col-6">
			<h5 class="text-theme-default font-weight-bold text-uppercase mb-2">SOFTHY SOLUCIONES EN SOFTWARE S.A.C. - SOFTHY S.A.C.</h5>
			<p>Jr. Alfonso Ugarte 3636.</p>
			<p>Cajamarca, Cajamarca, Cajamarca.</p>
			<p><i class="flaticon-call text-theme-default mr-2"></i>Telf.: 956295282</p>
			<p><i class="flaticon-envelope mr-2 text-theme-default"></i>aquino.alex25@gmail.com</p>
			<p><i class="flaticon-placeholder-1 mr-2 text-theme-default"></i> https://facturalaya.com</p>
		</div>
		<div class="col-6">
			<div class="client-info">
				<p><span class="text-theme-default font-weight-bold">Cliente:</span>  Alex Castañeda</p>
				<p><span class="text-theme-secundary font-weight-bold">Ruc:</span> 9098214390</p>
				<p><span class="text-theme-secundary">Dirección: </span> Jr. Alfonso Ugarte 1115. Cajamarca.</p>
			</div>
			<hr>
			<p><span class="text-theme-default font-weight-bold">Fecha de Emisión:</span> 25-08-2020 /
			12:40 PM</p>
			<p><span class="text-theme-default font-weight-bold">Tipo de modena:</span> Soles.</p>
			<p><span class="text-theme-default font-weight-bold">Orden de compra:</span></p>
			<p><span class="text-theme-default font-weight-bold">N° de Guía:</span></p>
			<p><span class="text-theme-default font-weight-bold">Fecha vencimiento:</span> 02/02/2020</p>
		</div>
	</div>
	<div class="w-100">
		
	</div>

	<section class="table-content">
		<table class="table-main-head">
			<tbody>
				<tr class="font-weight-bold text-uppercase text-center">
					<th>Cant.</th>
					<th width="450px">Descripción</th>
					<th>Precio</th>
					<th>Unid/Med</th>
					<th>Afect. IGV</th>
					<th>Importe</th>
				</tr>
				<tr>
					<td>1</td>
					<td>3i47yMdQ3Y</td>
					<td>prueba detraccion</td>
					<td>UND</td>
					<td>prueba detraccion</td>
					<td>UND</td>
				</tr>
				<tr>
					<td>1</td>
					<td>3i47yMdQ3Y</td>
					<td>prueba detraccion</td>
					<td>UND</td>
					<td>prueba detraccion</td>
					<td>UND</td>
				</tr>
				<tr>
					<td>1</td>
					<td>3i47yMdQ3Y</td>
					<td>prueba detraccion</td>
					<td>UND</td>
					<td>prueba detraccion</td>
					<td>UND</td>
				</tr>
			</tbody>
		</table>
		<div class="invoice-price-footer">
			<div class="method-price">
				<p class="text-theme-default font-weight-bold mb-3 mt-3">Observación</p>
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. In similique consequuntur facilis repudiandae obcaecati.</p>
				<hr>
			</div>
			<div id="invoice-total">
				<div class="footer-table">
					<p>SubTotal</p>
					<p>IVA 21.0%</p>
					<h5 class="font-weight-bold text-theme-default">Total</h5>
				</div>			
				<div class="footer-table">
					<p>1600.000</p>
					<p>500.000</p>
					<h5 class="font-weight-bold text-theme-default">200.000</h5>
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
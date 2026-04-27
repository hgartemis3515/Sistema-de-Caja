<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
	<title>Document</title>
</head>
<body>
	<style>
	@import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap");
		body {
			font-family: "Open Sans",Tahoma,Geneva,sans-serif;
			position:relative;
		}
		ul {
			list-style-type: circle;
			margin-right: 0!important;
			padding: 0 0 0 10px;
		}
		td, th {
			border-bottom: 1px solid #dddddd;
			padding: 10px;
		}
		tr:nth-child(even) {
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
		.invoice-total {
			width: 100%;
		}
		.display-inline{
			display: inline;
		}
		footer {
			position: absolute;
			bottom: 0;
			width: 100%;
		}
		
		.footer-table {
			float: right;
			margin: 10px;
			padding: 10px;
			width: 250px;
			text-align: left;
		}
		.footer-table p{ padding-bottom: 10px}
		.header-main{
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
		[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
			margin: 10px;
			text-align: center;
			font-size: 15px;
			color: #007bff;
		}
		.letter-spacing-1{
			letter-spacing: 2;
		}
		.masthead {
			position: relative;
			border-top: 1px dashed #007bff;
			padding-top: 2em;
		}
		.masthead img{
			position: absolute;
			top: 0;
			right: 0;
		}
		.single-header1, .single-header2, .single-date, .info-total, .date-price, .single-footer-wight, .social-media{
			float: left;
			margin: 10px;
			padding: 10px;
		}
		.single-header1{
			height: 200px;
			width: 300px;
		} 
		.single-header2{
			width: 550px;
			border-bottom: 1px dashed #007bff;
		}
		.single-date {
			width: 160px;
			padding: 0;
			margin: 10px 10px 10px 0;
		}
		.single-footer-wight{
			width: 200px;
		}
		.single-footer-wight.box-main{
			width: 650px;
			padding: 10px 10px 10px 0;
			margin: 10px 10px 10px 0;
		}
		.table-main-head{
			width: 100%;
			border-collapse: collapse;
		}
		.sub-footer {
			border-bottom: 1px dashed #007bff;
			height: 200px;
		}
		.price {
			text-align: right;
			float: right;
		}

		.info-total{
			height: 200px;
			width: 250px;
		} 
		.date-price {
			width: 600px;
			float: right;
		}
		.social-media {
			width: 200px;
			float: left;
			padding: 0;
			margin: 5px 0;
		}
		.table-cuentas {
			float: right;
			font-size: 12px;
		}
		.table-cuentas td, .table-cuentas th {
			padding: 0px 10px;
		}
	</style>
	<div class="masthead">
		<img src="https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/shape-p-2.png">
		<div class="header-main">
			<div class="single-header1">
				<h3 class="font-weight-light text-primary mt-3">SOFTHY SOLUCIONES EN SOFTWARE S.A.C. - SOFTHY S.A.C.
				</h3>
				<p>R.U.C.: 20604209987</p>
				<p>Jr. Alfonso Ugarte 3636</p>
				<p>Cajamarca, Cajamarca, Cajamarca</p>
			</div>			
			<div class="single-header2">
				<h2 class="pt-2 font-weight-light text-uppercase">Factura ELECTRÓNICA: <br> <span  class="bg-primary text-white mt-2">B001-0000006</span></h2> 
				
				<div id="date_invoice">
					<div class="single-date">
						<p class="text-primary font-weight-bold">Fecha emisión:</p>
						<p> 18/08/2020</p>
					</div>	
					<div class="single-date">
						<p class="text-primary font-weight-bold">Cond. de pago:</p>
						<p>Al Contado</p>
					</div>
					<div class="single-date">
						<p class="text-primary font-weight-bold">Moneda:</p>
						<p>PEN</p>
					</div>
					<div class="single-date">
						<p class="text-primary font-weight-bold">N° de guía:</p>
						<p> 18/08/2020</p>
					</div>	
					<div class="single-date">
						<p class="text-primary font-weight-bold">N° de orden:</p>
						<p>Al Contado</p>
					</div>
					<div class="single-date">
						<p class="text-primary font-weight-bold">Fecha Vencimiento:</p>
						<p>PEN</p>
					</div>
				</div>		
			</div>	
		</div>
	</div> 
	<div class="single-date-client mb-3">
		<p class="text-primary font-weight-bold">Cliente:</p>
		<p>AUTOSPORT TPP S.G. S.A.C.</p>
		<p>20604586128</p>
		<p>Jr. Alfonso Ugarte Nro. 1900 Atumpampa</p>
	</div>	
	<div class="table-invoice">
		<table class="table-main-head">
			<tbody>
				<tr class="bg-primary text-white">
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
					<td>S/ 20.06</td>
					<td>KILOGRAMOS</td>
					<td>Gravado</td>
					<td>S/ 20.06</td>
				</tr>
				<tr>
					<td>2</td>
					<td>3i47yMdQ3Y</td>
					<td>S/ 20.06</td>
					<td>KILOGRAMOS</td>
					<td>Gravado</td>
					<td>S/ 20.06</td>
				</tr>
				<tr>
					<td>3</td>
					<td>3i47yMdQ3Y</td>
					<td>S/ 20.06</td>
					<td>KILOGRAMOS</td>
					<td>Gravado</td>
					<td>S/ 20.06</td>
				</tr>
				<tr>
					<td colspan="6" class="text-uppercase">SON OCHENTA CON 00/100 SOLES</td>
				</tr>
			</tbody>
		</table>
		<div class="invoice-total">
			<div class="info-total">
				<h4 class="bg-primary text-white">Observación:</h4>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500</p>
			</div>
			<div class="date-price">
				<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
					<p class="border-invoice-bottom">Gravada: <span class="price ml-5">1600.000</span> </p>
				</div>
				<div class="footer-table font-weight-bold text-uppercase">
					<p class="border-invoice-bottom">IGV (18.00%): <span class="price ml-5">1600.000</span> </p>
				</div>			
				<div class="footer-table font-weight-bold text-uppercase mr-0 pr-0">
					<p class="border-invoice-bottom"><span class="bg-primary text-white">Total</span>  <span class="price ml-5">1600.000</span> </p>
				</div>
				<div class="footer-table font-weight-bold text-uppercase">
					<p class="border-invoice-bottom">Descuento <span class="price text-sucess ml-5">1600.000</span> </p>
				</div>			
			</div>
		</div>
	</div>
	
	<footer>
		<div class="sub-footer">
			<div class="col-6">
				<div class="single-footer-wight box-main">
					<img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-3 mt-3 mb-3">
					<p class="mb-1 mt-3"><span class="text-primary font-weight-bold">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
					<p><span class="text-primary font-weight-bold">HASH: </span> 4gr1BHHIgukS8i4tEBeaQB0RP/g=</p>
					<p><span class="text-primary font-weight-bold">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
				</div>
			</div>
			<div class="col-6">
				<table class="table-3 table-cuentas">
					<tbody>
						<tr>
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
		</div>
	
		<div class="footer-main">
			
			<div class="single-footer-wight">
				<img src="https://arpsystem.com.pe/servicio-de-facturacion-electronica/img/logo.png" width="200px" alt="">
			</div>

			<div class="single-footer-wight box-main text-right float-right">
				
				<div class="social-media">
					<div class="item-media">
						<i class="flaticon-envelope"></i>
						<p  class="text-primary font-weight-bold">Email</p>
						<p>aquino.alex25@gmail.com</p>
					</div>
				</div>
				<div class="social-media pl-3">
					<div class="item-media">
						<i class="flaticon-call"></i>
						<p class="text-primary font-weight-bold">Teléfono</p>
						<p>Telf.: 956295282 - 956295282</p>
					</div>
				</div>
				<div class="social-media">
					<div class="item-media">
						<i class="flaticon-laptop"></i>
						<p  class="text-primary font-weight-bold">Website:</p>
						<p>https://arpsystem.com.pe/</p>
					</div>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>
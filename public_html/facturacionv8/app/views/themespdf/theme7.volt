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
			margin-left: 0px;
		}

		body{
			position: relative;
		}
		table{
			width: 100%
		}
		th{
			text-align: center;
		}
		td, th {
			border-bottom: 1px solid #dddddd;
			padding: 8px;
		}
		.table-main-head td, .table-main-head th{
			padding: 5px;
		}
		p{
			margin: 0;
			padding: 0;
		}
		#invoice-total {
			/*
			float: right;
			width: 550px;
			*/
			margin-top: 1em;
			border: 1px solid #e1e2e3;
			border-left: 3px solid #e1e2e3;
			border-right: 3px solid #e1e2e3;
			border-radius: 15px;
			
		}
		.border-top{
			border-top: 1px dashed #e1e2e3!important;
		}
		.box-content {
			border: 1px solid #e1e2e3;
			border-radius: 15px;
			padding: 5px 15px;
			border-left: 3px solid #e1e2e3;
			border-right: 3px solid #e1e2e3;
			width: 100%;
		}
		.single-cliente .box-content {
			height: 140px;
		}
		.bg-secondary {
			background-color: #e1e2e3!important;
		}
		.col-4{
			width: 25%;
			float: left;
		}
		.col-6 {
			width: 50%;
			float: left;
		}
		.col-8{
			width: 66.666667%;
			float: left;
		}
		.col-8-r{
			width: 66.666667%;
			float: right;
		}
		.condiciones-box{
			height: 170px;
		}
		.cuentas-media {
			height: 120px;
		}
		.data-client{
			width: 100%;
			height: 200px;
		}
		.data-empresa{
			width: 100%;
			height: 150px;
		}
		.data-empresa2{
			width: 100%;
			height: 200px;
		}
		.display-inline{
			display: inline;
		}
		.item-media {
			text-align: left;
			font-size: 12px;
		}
		.item-media i, .img-cuenta {
			float: left;
			padding: 9px 3px;
			margin-right: 10px
		}
		
		.single-client{
			width: 100%;
		}
		.head-1 {
			width: 350px;
			float: right;
		}
		.masthead {
			height: 150px;
		}
		.single-date, .cotizacion {
			float: left;
			margin: 10px;
			padding: 10px;
		}
		.single-date{
			width: 500px;
		}
		.cotizacion{
			width: 300px;
			height: 150px;
			float: right;
		}
		footer {
			position: absolute;
			bottom: 0%;
			width: 100%;
		}
	</style>
	<div class="masthead">
		<div class="float-left">
			<img src="https://arpsystem.com.pe/servicio-de-facturacion-electronica/img/logo.png" class="p-3" alt="">
		</div>
		<div class="head-1 text-right">
			<img src="https://www.brildor.com/blog//wp-content/uploads/2009/12/Marcas-300x118.png">
		</div>
	</div>
	<div class="data-empresa">
		<div class="single-date">
			<p><span class="font-weight-bold">Empresa: </span><span class="text-uppercase">SOFTHY SOLUCIONES EN SOFTWARE. S.A.C. - SOFTHY S.A.C.</span></p>
			<p><span class="font-weight-bold">Dirección: </span>Jr. Alfonso Ugarte 3636. Cajamarca, Cajamarca, Cajamarca</span></p>
			<p><span class="font-weight-bold"> Teléfonos: </span><span class="text-uppercase">956295282</span></p>
			<p><span class="font-weight-bold">E-mail:</span> aquino.alex25@gmail.com</p>
			<p><span class="font-weight-bold">Website:</span> https://facturalaya.com</p>
		</div>
		<div class="cotizacion">
			<h5 class="text-center bg-secondary rounded text-uppercase p-1 font-weight-bold">Cotización</h5>
			<div class="box-content">
				<p><span class="font-weight-bold">Cotización N°:</span> <span class="text-uppercase"></span></p>
				<p><span class="font-weight-bold">Fecha:</span> <span class="text-uppercase"></span></p>
				<p><span class="font-weight-bold">Moneda:</span> <span class="text-uppercase"></span></p>
			</div>
		</div>
	</div>
	<div class="data-empresa2">
		<div class="single-date">
			<p><span class="font-weight-bold">Asesor de ventas: </span><span class="text-uppercase">XXXXXXXXXXXXXXX</span></p>
			<p><span class="font-weight-bold">Email: </span><span class="text-uppercase">xxxxxxxxx</span></p>
			<p><span class="font-weight-bold">Teléfonos: </span><span class="text-uppercase">xxxxxxxxxx</span></p>
		</div>
		<div class="cotizacion">
			<h5 class="text-center bg-secondary rounded text-uppercase p-1 font-weight-bold">Tipo de cambio:</h5>
			<div class="box-content">
				<p><span class="font-weight-bold">Cotización N°:</span> <span class="text-uppercase"></span></p>
				<p><span class="font-weight-bold">Fecha:</span> <span class="text-uppercase"></span></p>
				<p><span class="font-weight-bold">Moneda:</span> <span class="text-uppercase"></span></p>
			</div>
		</div>
	</div>
	<div class="data-client">
		<div class="single-cliente">
			<h5 class="text-left bg-secondary mb-5 rounded pl-5 pr-5 display-inline text-uppercase p-1 font-weight-bold">Datos cliente</h5>
			<div class="box-content mt-3">
				<div class="col-6">
					<p><span class="font-weight-bold">Razón Social:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">RUC:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">Dirección:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">Referencia:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">Atención:</span> <span class="text-uppercase"></span></p>
				</div>
				<div class="col-6">
					<p><span class="font-weight-bold">Nacional (local) <span class="bg-secondary p-1">X</span:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">Exportación:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">Teléfono:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">Fecha:</span> <span class="text-uppercase"></span></p>
					<p><span class="font-weight-bold">Moneda:</span> <span class="text-uppercase"></span></p>
				</div>
			</div>
		</div>
	</div>
	<div class="table-content">
		<p class="font-italic">Estimados señores:</p>
		<p class="font-italic">Lorem ipsum dolor sit amet consectetur adipisicing elit. In similique consequuntur facilis repudiandae obcaecati.</p>
		<table class="table-main-head mt-2">
			<tbody>
				<tr class="font-weight-bold rounded mb-4 text-uppercase bg-secondary text-center">
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
		<div class="w-100 condiciones-box">
			<div class="col-4 pb-4 mt-4">
				<h5 class="text-left bg-secondary mb-5 rounded pl-5 pr-5 display-inline text-uppercase p-1 font-weight-bold">Observación</h5>
				<p class="mt-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s</p>
			</div>
			<div class="col-8-r">
				<div class="mb-1" id="invoice-total">
					<table class="table-main-head mt-2" width="80%">
						<tbody class="text-center">
							<tr class="font-weight-bold rounded mb-4 text-uppercase text-center">
								<th>Valor venta</th>
								<th>I.G.V</th>
								<th>Percepción</th>
								<th>Importe Total</th>
							</tr>
							<tr>
								<td>1</td>
								<td>3i47yMdQ3Y</td>
								<td>prueba detraccion</td>
								<td>UND</td>
							</tr>
						</tbody>
					</table>
					<p class="text-uppercarse text-center p-1 font-weight-bold">SON SETENTA MANZANAS AMARICANAS</p>
				</div>
				<div class="single-footer-wight box-main">
					<img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="100px" class="float-left mr-3 mb-3">
					<p class="mb-1 mt-3"><span class="text-theme-default font-weight-bold">Consulte su documento electrónico en:</span> https://arpsystem.com.pe/facturacionv8/consultas/index/1</p>
					<p><span class="text-theme-default font-weight-bold">HASH: </span> 4gr1BHHIgukS8i4tEBeaQB0RP/g=</p>
					<p><span class="text-theme-default font-weight-bold">VENDEDOR: </span> Alex Castañeda (cod: 1)</p>
				</div>
			</div>
		</div>
	</div>
	<footer class="mt-5 pt-5" style="margin-top: 3em">
		<div class="cuentas-media pt-3 border-top w-100">
			<div class="col-4 p-0">
				<div class="item-media">
					<img src="https://infonegocios.biz/uploads/medidas-bbva.jpg" width="50px" class="img-cuenta">
					<p class="text-primary font-weight-bold">CTA CRE:</p>
					<p><span class="text-primary font-weight-bold">N°: </span>24596035269047</p>
					<p><span class="text-primary font-weight-bold">CCI:</span> 00224519603526904797</p>
				</div>
			</div>
			<div class="col-4 p-0">
				<div class="item-media">
					<img src="https://cafetaipa.com/wp-content/uploads/2009/12/nuevo-logo-comercial-interbank-peru1.jpg" width="50px" class="img-cuenta">
					<p class="text-primary font-weight-bold">CTA CRE:</p>
					<p><span class="text-primary font-weight-bold">N°: </span>24596035269047</p>
					<p><span class="text-primary font-weight-bold">CCI:</span> 00224519603526904797</p>
				</div>
			</div>
			<div class="col-4 p-0">
				<div class="item-media">
					<img src="https://www.pega.com/sites/default/files/styles/640/public/media/images/2018-11/scotiabank-logo-color.png?itok=ztG23OU1" width="50px" class="img-cuenta">
					<p class="text-primary font-weight-bold">CTA CRE:</p>
					<p><span class="text-primary font-weight-bold">N°: </span>24596035269047</p>
					<p><span class="text-primary font-weight-bold">CCI:</span> 00224519603526904797</p>
				</div>
			</div>
			<div class="col-4 p-0">
				<div class="item-media">
					<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/BBVAprovinciallogo.svg/1200px-BBVAprovinciallogo.svg.png" width="50px" class="img-cuenta">
					<p class="text-primary font-weight-bold">CTA CRE:</p>
					<p><span class="text-primary font-weight-bold">N°: </span>24596035269047</p>
					<p><span class="text-primary font-weight-bold">CCI:</span> 00224519603526904797</p>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>
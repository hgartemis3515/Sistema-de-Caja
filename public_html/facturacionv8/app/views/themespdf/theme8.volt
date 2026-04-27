<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
	<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
	<title>Document</title>
</head>
<body>
	<style>
		[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
			margin-left: 0px;
		}
		body{
			position: relative;
			font-family: "Cairo", sans-serif;
		}
		
		body::before{
			content: " ";
			position: absolute;
			top: 25%;
			left: 10%;
			width: 80%;
			height: 700px;
			background-image: url(http://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/logo-bg.png);
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center center;
			z-index: -1;
			margin: auto;
			opacity: .5;
		}
		table{
			width: 100%
		}
		th{
			text-align: center;
		}
		.table-head td, .table-head th {
			border: 1px solid #000;
			padding: 10px;
		}
		p{
			margin: 0;
			padding: 0;
		}
		.box-content{
			border: 1px solid #000;
			width: 100%;
			height: 150px;
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
		.col-8{
			width: 66.666667%;
			float: left;
		}
		.header-title{
			width: 37%;
			float: left;
		}
		.header-title p{
			font-size: 15px;
		}
		
		.table-head{
			width: 100%;
		}
		.border-black{
			border: 1px solid #000;
		}
		.bg-theme-default{
			background: #a3251e; 
			color: #fff;
		}
		.color-theme-main{
			color: #a3251e; 
		}
		.masthead{
			height: 200px;
		}
		.tabla-head{
			width: 38%;
			float: left;
			height: 200px;
		}
		footer {
			position: absolute;
			bottom: 3%;
			width: 100%;
		}
		.footer-info p{
			font-size: 14px;
		}
		.table-footer{
			border: 1px solid #000;
		}
		.table-footer td, .table-footer th {
			padding: 0px;
		}
		
	</style>
	<div class="masthead w-100">
		<div class="float-left mr-5 header-logo">
			<img src="https://www.insecore.com/admin/temp/empresa/empresaecgf9beeclddchj806iad0kb5.png" width="160px">
		</div>
		<div class="header-title">
			<h5 class="font-weight-bold">SOFTHY SOLUCIONES EN SOFTWARE. S.A.C. - SOFTHY S.A.C.</h5>
			<p><i class="flaticon-placeholder-1 mr-2"></i>Psj. Arequipa N° 192 Carmen de la Legua</p>
			<p><i class="flaticon-placeholder-1 mr-2"></i>Reynoso, Prov. Const. del Callao. OFICINAS/ALMACÉN</p>
			<p><i class="flaticon-call mr-2"></i>(01) 574 5737 - (51) 992521525 - 999440179</p>
			<p><i class="flaticon-envelope mr-2"></i>ventas@insecore.com www.insecore.com</p>
		</div>
		<div class="tabla-head ml-3">
			<table class="table-head table">
				<tr class="mt-2">
					<td  class="text-center font-weight-bold text-uppercase">R.U.C.: 20000000001</td>
				</tr>
				<tr class="bg-main">
					<td  class="text-center bg-theme-default font-weight-bold text-uppercase">Facturación Electrónica</td>
				</tr>
				<tr>
					<td  class="text-center">Nro. F001-0000004</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="box-content pt-2 mt-3">
		<div class="col-6">
			<p><span class="font-weight-bold text-uppercase">Cliente:</span> <span class="text-uppercase"></span></p>
			<p><span class="font-weight-bold text-uppercase">RUC:</span> <span class="text-uppercase"></span></p>
			<p><span class="font-weight-bold text-uppercase">Dirección:</span> <span class="text-uppercase"></span></p>
			<p><span class="font-weight-bold text-uppercase">Cond. Pago:</span> <span class="text-uppercase"></span></p>
			<p><span class="font-weight-bold text-uppercase">Vendedor:</span> <span class="text-uppercase"></span></p>
		</div>
		<div class="col-6">
			<p><span class="font-weight-bold text-uppercase">F. Emisión:</span> <span class="text-uppercase"></span></p>
			<p><span class="font-weight-bold text-uppercase">F. Vencimiento:</span> <span class="text-uppercase"></span></p>
			<p><span class="font-weight-bold text-uppercase">N° Guía:</span> <span class="text-uppercase"></span></p>
			<p><span class="font-weight-bold text-uppercase">Tipo de Moneda:</span> <span class="text-uppercase"></span></p>
		</div>
	</div>
	<div class="table-content">
		<table class="table-main-head mt-4">
			<tbody>
				<tr class="font-weight-bold  mb-4 text-uppercase bg-theme-default border-black text-center">
					<th>Cant.</th>
					<th width="500px">Descripción</th>
					<th>Precio Unitario</th>
					<th>Importe</th>
				</tr>
				<tr>
					<td>1</td>
					<td>3i47yMdQ3Y</td>
					<td>prueba detraccion</td>
					<td>UND</td>
				</tr>
				<tr>
					<td>2</td>
					<td>3i47yMdQ3Y</td>
					<td>prueba detraccion</td>
					<td>UND</td>
				</tr>
				<tr>
					<td>3</td>
					<td>3i47yMdQ3Y</td>
					<td>prueba detraccion</td>
					<td>UND</td>
				</tr>
			</tbody>
		</table>
	</div>
	<footer>
		<div class="col-8 footer-info">

			<p class="font-weight-bold text-uppercase">Consulte su documento electrónico en:</p>
			<img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024.jpg" width="40px" class="float-left mr-3 mb-3">
			<p>https://icloudfact.com/facturacionv8/consultas/index/2001
			Representación impresa de la FACTURA ELECTRÓNICA</p>
			<div class="border-black mt-2 p-2">
				<p class="text-uppercase font-weight-bold">DEPÓSITO A NOMBRE DE INSECORE S.A.C.</p>
				<p>Cuenta Corriente:</p>
				<p><img src="https://infonegocios.biz/uploads/medidas-bbva.jpg" width="40px" class="img-cuenta mr-2">BCP - SOLES: N° 191-2591430059. CCI: 00224519603526904797</p>
				<p><img src="https://www.pega.com/sites/default/files/styles/640/public/media/images/2018-11/scotiabank-logo-color.png?itok=ztG23OU1" width="40px" class="img-cuenta mr-2">BCP - DOLARES: ° 191-2591442190. CCI: 00224519603526904797</p>
				<p><img src="https://cafetaipa.com/wp-content/uploads/2009/12/nuevo-logo-comercial-interbank-peru1.jpg" width="40px" class="img-cuenta mr-2">BBVA - SOLES: N° 0011-166-010004963966. CCI: 00224519603526904797</p>
				<p><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/BBVAprovinciallogo.svg/1200px-BBVAprovinciallogo.svg.png" width="40px" class="img-cuenta mr-2">BBVA - DOLARES: N° 0011-166-010004964769. CCI: 00224519603526904797</p>
			</div>
		</div>
		<div class="col-4">
			<div class="mt-5 pt-2">
				<table class="table-footer font-weight-bold text-uppercase">
					<tr>
						<td colspan="2" class="text-center font-weight-bold text-uppercase"></td>
					</tr>
					<tr class="bg-main">
						<td  colspan="2"  class="text-center bg-theme-default font-weight-bold">Resumen</td>
					</tr>
					<tr>
						<td  class="text-center">Gravada: </td>
						<td  class="text-center">$ 452.00</td>
					</tr>
					<tr>
						<td  class="text-center">IGV (18.00%) </td>
						<td  class="text-center">$ 452.00</td>
					</tr>
					<tr>
						<td  class="text-center">DESCUENTOS </td>
						<td  class="text-center">$ 452.00</td>
					</tr>
					<tr class="font-weight-bold color-theme-main">
						<td  class="text-center">IMPORTE TOTAL: </td>
						<td  class="text-center">$ 452.00</td>
					</tr>
				</table>
			</div>
		</div>
	</footer>
</body>
</html>
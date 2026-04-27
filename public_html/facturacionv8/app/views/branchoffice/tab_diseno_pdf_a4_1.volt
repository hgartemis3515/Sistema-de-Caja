<style>
.dates-clients p {
	margin: 0;
}
.header-box{
	color: #5e5e5e;
	display: grid;
	grid-template-columns: repeat(3, 1fr);
}
</style>
<div class="modelo_plantila_pdf_a4">
	<div class="header-box">
		<div class="item-header-box header-logo">
			<img src="<?php if($contribuyente->logo_461 == '/facturacionv8/public/img/logo_facturalaya_461.png'){ echo '/facturacionv8/public/img/logo_rectangular_ejemplo.png'; } else { echo $contribuyente->logo_461; } ?>" width="250px">
		</div>
		<div class="item-header-box text-center font-weight-bold" style="width: 325px">
			<h4 class="text-uppercase font-weight-bold"><?php echo ucwords($contribuyente->nombre_comercial); ?></h4>
			<p><?php if($sucursal){ echo $sucursal->direccion.' '.$sucursal->urbanizacion; } else { echo "Aquí la Dirección de tu Empresa - Urbanización"; } ?></p>
			<p><i class="icon-phone-wave mr-2"></i>Telf.: <?php  if($sucursal){ echo $sucursal->telefono; } else { echo "999999999"; } ?></p>
			<p><i class="icon-envelop mr-2"></i> Email: <?php  if($sucursal){ echo $sucursal->email; } else { echo "tu_email@gmail.com"; } ?></p>
		</div>
		<div class="item-header-box">
			<div class="content-number-header"  style="border: 1px solid #000">
				<p class="text-center font-weight-bold">R.U.C.: 20000000001</p>
				<p class="text-center font-weight-bold text-uppercase">Facturación Electrónica</p>
				<p class="text-center font-weight-bold">F001- 000303</p>
			</div>
		</div>
	</div>
	<!-- <div class="row header-box">
		<div class="col-lg-4 header-logo">
			<img src="<?php echo $contribuyente->logo_461; ?>" width="250px">
		</div>
		<div class="col-lg-4 text-center font-weight-bold">
			<h4 class="text-uppercase font-weight-bold"><?php echo ucwords($contribuyente->nombre_comercial); ?></h4>
				<p><?php if($sucursal){ echo $sucursal->direccion.' '.$sucursal->urbanizacion; } else { echo "Aquí la Dirección de tu Empresa - Urbanización"; } ?></p>
				<p><i class="icon-phone-wave mr-2"></i>Telf.: <?php  if($sucursal){ echo $sucursal->telefono; } else { echo "999999999"; } ?></p>
				<p><i class="icon-envelop mr-2"></i> Email: <?php  if($sucursal){ echo $sucursal->email; } else { echo "tu_email@gmail.com"; } ?></p>
		</div>
		<div class="col-lg-4">
			<div class="content-number-header border" style="border: 1px solid #000">
				<p class="text-center font-weight-bold">R.U.C.: 20000000001</p>
				<p class="text-center font-weight-bold text-uppercase">Facturación Electrónica</p>
				<p class="text-center font-weight-bold">F001- 000303</p>
			</div>
		</div>
	</div> -->
	<div class="position-relative">
		<p class="font-weight-bold">Introduce un texto aquí!</p>
		<div class="theme01_txt_pdf_a4_1 single-editable-box"  contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_a4_1); } ?></div>
		<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
	</div>
	<div class="dates-clients">
		<p>Cliente: TUEMPRESA SRL</p>
		<p>RUC: 20600000001</p>
		<p>Dirección: Jr. Puno 234 </p>
	</div>
	<div class="table_items">
		<div class="single-items-table">
			<p>Fecha de emisión</p>
			<p>13-02-2020 / 01:31 AM </p>
		</div>
		<div class="single-items-table">
			<p>Condición de pago</p>
			<p>Pago en Efectivo</p>
		</div>
		<div class="single-items-table">
			<p>Tipo de Moneda</p>
			<p>Soles</p>
		</div>
		<div class="single-items-table">
			<p>Número de Guía</p>
			<p></p>
		</div>
		<div class="single-items-table  border-right">
			<p>Orden de Compra</p>
			<p></p>
		</div>
	</div>
	<div class="table_documents">
		<div class="text-center">
			<p class="hd-table">CANT</p>
			<p>4</p>
		</div>
		<div style="width: 300px">
			<p class="hd-table">DESCRIPCIÓN</p>
			<p>SERVICIO DE TRANSPORTE LIMA - PIURA - hora de llegada 5am </p>
		</div>
		<div class="text-center">
			<p class="hd-table">PRECIO</p>
			<p>S/ 145</p>
		</div>
		<div class="text-center">
			<p class="hd-table">UNID/MED</p>
			<p>SERVICIO</p>
		</div>
		<div class="text-center">
			<p class="hd-table">AFECT.IGV </p>
			<p>Inafecto</p>
		</div>
		<div class="border-right text-center">
			<p class="hd-table">IMPORTE</p>
			<p>S/ 580.00</p>
		</div>
		<div class="price-final border-right border-top">
			<p>SON UN MIL CINCUENTA Y CINCO CON 00/100 SOLES</p>
		</div>
		<div class="price-final position-relative border-right">
			<p class="font-weight-bold">Introduce un texto aquí!</p>
			<div class="theme01_txt_pdf_a4_2 single-editable-box border-right" contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_a4_2); } ?></div>
		</div>
	</div>
	<div class="table-footer-01">
		<div class="item-resumen">
			<img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Commons_QR_code.png" alt="" class="float-left img-qr-01" width="150px">
			<div class="text-observation">
				<p style="margin-bottom: 15px">Observación:</p>
				<p>Autorizado mediante la resolución Nº 064-005-0002737/Sunat</p>
				<p>Consulte su documento electrónico en: https://<?php echo $patrocinador['url_domain']; ?>/facturacionv8/consultas/index/<?php echo $contribuyente->id_contribuyente; ?> </p>
				<p>HASH: /Duu6iTuDjZAj116zk3LQAHSgMk=</p>
				<p>	Atendido Por: <?php echo $usuario->nombre.' '.$usuario->apellido; ?> (cod. <?php echo $usuario->idusuario; ?>)</p>	
				<p>Representación Impresa de la Factura Electrónica</p>
			</div>
			
			<br>
			<p>Representación Impresa de Documento Electrónico Generado En
					Una Versión de Pruebas. No tiene Validez!
					</p>
		</div>
		<div class="item-resumen">
			<p class="font-weight-bold">RESUMEN:</p>
			<p>Gravada: S/ 0.00</p>
			<p>Inafecto: S/ 580.00</p>
			<p>Exonerado: S/ 475.00</p>
			<p>IGV (18.00%): S/ 0.00</p>
			<p>Descuento Total: S/ 0.00</p>
			<p>Total a Pagar: S/ 1,055.00</p>
		</div>
	</div>
	<div class="position-relative mt-2">
		<p class="font-weight-bold">Introduce un texto aquí!</p>
		<div class="theme01_txt_pdf_a4_3 single-editable-box" contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_a4_3); } ?></div>
		<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
	</div>
	<div class="content-footer mt-2">
		<p class="title-cuentas">Cuentas corrientes:</p>
		<div class="table-cuentas-01">
			<div class="box-cuentas">
				<p>Banco</p>
				<p>BCP</p>
			</div>
			<div class="box-cuentas">
				<p>Moneda</p>
				<p>PEN</p>
			</div>
			<div class="box-cuentas">
				<p>CTA CTE</p>
				<p>24596035269047</p>
			</div>
			<div class="box-cuentas">
				<p>CCI</p>
				<p>00224519603526904797</p>
			</div>
		</div>
	</div>
</div>
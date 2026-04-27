<div class="modelo_plantila_pdf_ticket">
	<div class="content-box-3">
		<div class="header_model_3">
			<img src="<?php if($contribuyente->logo_461 == '/facturacionv8/public/img/logo_facturalaya_461.png'){ echo '/facturacionv8/public/img/logo_rectangular_ejemplo.png'; } else { echo $contribuyente->logo_461; } ?>" width="250px" class="margin-bottom-2">
			<p class="text-uppercase font-weight-bold"><?php echo ucwords($contribuyente->nombre_comercial); ?></p>
			<p>R.U.C.: <?php echo $contribuyente->ruc; ?></p>
			<p><?php if($sucursal){ echo $sucursal->direccion.' '.$sucursal->urbanizacion; } else { echo "Aquí la Dirección de tu Empresa - Urbanización"; } ?></p>
			<p> Telf.: <?php  if($sucursal){ echo $sucursal->telefono; } else { echo "999999999"; } ?></p>
			<div class="position-relative text-left mt-2">
				<p class="font-weight-bold">Introduce un texto aquí!</p>
				<div class="theme03_txt_pdf_ticket1 single-editable-box"  contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_ticket_1); } ?></div>
				<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
			</div>
		</div>
		<hr style="border: .5px dashed #000;">
		<div class="text-center">
			<p style="font-size: 15px;" class="font-weight-bold text-uppercase">FACTURA ELECTRÓNICA</p>
			<p style="font-size: 15px;" class="font-weight-bold text-uppercase">F001 - 000039</p>
			<p >Fecha de Emisión: 14-02-2020 / 23:46 PM</p>
			<p>Señor (es): FACTUALAYA SRL</p>
			<p>RUC: 20600000001</p>
			<p>Direc.: Av. America Sur Nro. 4125 Los Pinos La Libertad - Trujillo - Trujillo</p> 
		</div>
		<hr style="border: .5px dashed #000; margin: 10px 0">
		<div class="grid-single-3">
			<div>
				<p>Cant</p>
				<p>1</p>
			</div>
			<div>
				<p>Descripción</p>
				<p>SERVICIO DE FACTURACIÓN ELECTRÓNICA EN LA NUBE DE PAGO</p>
			</div>
			<div>
				<p>Precio</p>
				<p>S/ 139</p>
			</div>
			<div>
				<p>Importe</p>
				<p>S/ 139.0</p>
			</div>
		</div>
		<hr style="border: .5px dashed #000; margin: 10px 0">
		<div class="text-right">
			<p>Gravada: S/ 117.80</p>
			<p>IGV (18.00%): S/ 21.20</p>
			<p>Descuento Total: S/ 0.00</p>
			<p>Total a Pagar: S/ 139.00</p>
		</div>
		<hr style="border: .5px dashed #000; margin: 10px 0">
		<p>Importe en Letras: SON CIENTO TREINTA Y NUEVE CON </p>
		<div class="position-relative mt-2">
			<p class="font-weight-bold">Introduce un texto aquí!</p>
			<div class="theme03_txt_pdf_ticket2 single-editable-box"  contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_ticket_2); } ?></div>
			<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
		</div>
		<div class="text-center">
			<img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Commons_QR_code.png" alt="" class="img-qr-01" width="110px" style="margin-bottom: 15px;">
		</div>
		<p class="text-center mt-2">Representación Impresa de la Factura Electrónica Consulte su Documento en: https://<?php echo $patrocinador['url_domain']; ?>/facturacionv8/consultas/index/<?php echo $contribuyente->id_contribuyente; ?> HASH: z/7Jwwp62KFqT1z2McRtvfRG0i4= Atendido Por: <?php echo $usuario->nombre.' '.$usuario->apellido; ?> (cod. <?php echo $usuario->idusuario; ?>)</p>
		<div class="position-relative mt-2">
			<p class="font-weight-bold">Introduce un texto aquí!</p>
			<div class="theme03_txt_pdf_ticket3 single-editable-box"  contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_ticket_3); } ?></div>
			<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
		</div>
	</div>
</div>
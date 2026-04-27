<style>
	.box-header p{
		margin: 0;
	}
	.img_plantilla_2{
		margin: 0 1em;
	}
	.header-box-2{
		display: grid;
		grid-template-columns: 534px 1fr;
	}
	.table-3{
		display: grid;
		grid-template-columns: repeat(2, 1fr);
	}
	.table-3 .item-table-3{
		padding-left: 20px;
	}
	.table-5{
		display: grid;
		grid-template-columns: 650px 1fr;
	}
	.item-table-4:last-child {
		padding-right: 10px;
		padding-left: 10px;
	}
	.table-6{
		display: grid;
		grid-template-columns: 669px 1fr;
	}
</style>
<div class="modelo_plantila_pdf_a4">
	<div class="header-box-2">
		<div class="item-header-2">
			<div class="box-header text-center font-weight-bold">
				<img src="<?php if($contribuyente->logo_461 == '/facturacionv8/public/img/logo_facturalaya_461.png'){ echo '/facturacionv8/public/img/logo_rectangular_ejemplo.png'; } else { echo $contribuyente->logo_461; } ?>" width="250px">
				<h4 class="text-uppercase font-weight-bold"><?php echo ucwords($contribuyente->nombre_comercial); ?></h4>
				<p><?php if($sucursal){ echo $sucursal->direccion.' '.$sucursal->urbanizacion; } else { echo "Aquí la Dirección de tu Empresa - Urbanización"; } ?></p>
				<p>Telf.: <?php  if($sucursal){ echo $sucursal->telefono; } else { echo "999999999"; } ?></p>
				<p>Email: <?php  if($sucursal){ echo $sucursal->email; } else { echo "tu_email@gmail.com"; } ?></p>
			</div>
		</div>
		<div class="item-header-2">
			<table class="table-head bordered" width="90%" style="margin: 0 auto;">
				<tr>
					<td colspan="3" class="text-center font-weight-bold">R.U.C.: 20000000001</td>
				</tr>
				<tr class="bg-main">
					<td colspan="3" class="text-center font-weight-bold">Factura de Venta Electrónica</td>
				</tr>
				<tr>
					<td colspan="3" class="text-center">Nro. F001-0000004</td>
				</tr>
			</table> 
		</div>
	</div>
	<!-- <div class="row text-center">
		<div class="col-lg-7 col-lg-7 col-sm-7 col-7">
			<div class="box-header text-center font-weight-bold">
				<img src="<?php echo $contribuyente->logo_461; ?>" width="250px">
				<h4 class="text-uppercase font-weight-bold"><?php echo ucwords($contribuyente->nombre_comercial); ?></h4>
				<p><?php if($sucursal){ echo $sucursal->direccion.' '.$sucursal->urbanizacion; } else { echo "Aquí la Dirección de tu Empresa - Urbanización"; } ?></p>
				<p>Telf.: <?php  if($sucursal){ echo $sucursal->telefono; } else { echo "999999999"; } ?></p>
				<p>Email: <?php  if($sucursal){ echo $sucursal->email; } else { echo "tu_email@gmail.com"; } ?></p>
			</div>
		</div>
		<div class="col-lg-5 col-lg-5 col-sm-5 col-5  p-0">
			<table class="table-head bordered" width="90%" style="margin: 0 auto;">
				<tr>
					<td colspan="3" class="text-center font-weight-bold">R.U.C.: 20000000001</td>
				</tr>
				<tr class="bg-main">
					<td colspan="3" class="text-center font-weight-bold">Factura de Venta Electrónica</td>
				</tr>
				<tr>
					<td colspan="3" class="text-center">Nro. F001-0000004</td>
				</tr>
			</table> 
		</div>
	</div> -->
	<div class="position-relative mt-2">
		<p class="font-weight-bold">Introduce un texto aquí!</p>
		<div class="theme02_txt_pdf_a4_1 single-editable-box"  contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_a4_1); } ?></div>
		<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
	</div>
	<div class="table_2">
		<div class="item-table-2">
			<p>Fecha de Emisión:</p>
			<p>14-02-2020 / 23:46 PM</p>
		</div>
		<div class="item-table-2">
			<p>Cond. Pago</p>
			<p>pago en Efectivo</p>
		</div>
		<div class="item-table-2">
			<p>Moneda</p>
			<p>Soles</p>
		</div>
		<div class="item-table-2">
			<p>Guía de Remisión N°</p>
			<p>-</p>
		</div>
	</div>
	<div class="table-3">
		<div class="item-table-3 borderradius-left-top">
			<p><span class="font-weight-bold">Nombre / Razón Social: </span> Tu Empresa SRL </p>
		</div>
		<div class="item-table-3 border-right borderradius-right-top">
			<p><span class="font-weight-bold">Fecha de emisión: </span> 13/02/2020</p>
		</div>
		<div class="item-table-3">
			<p><span class="font-weight-bold">Dirección: </span> Jr. Alfonso Ugarte 3636</p>
		</div>
		<div class="item-table-3 border-right">
			<p><span class="font-weight-bold">Guía de Remisión N°: </span> </p>
		</div>
		<div class="item-table-3 border-bottom borderradius-left-bottom">
			<p><span class="font-weight-bold">RUC: </span>20600000001</p>
		</div>
		<div class="item-table-3 border-bottom border-right borderradius-right-bottom">
			<p><span class="font-weight-bold">Cond. de Pago: </span> CONTADO</p>
		</div>
	</div>
	
	<table class="table-4 bordered mt-30">
		<tbody>   
			<tr class="bg-main"> 
				<th>Item</th>
				<th>Código</th>
				<th width="330px">Descripción</th>
				<th>Unid.</th>
				<th>Cantidad</th>
				<th>P.Unitario</th>
				<th>Total</th>
			</tr>
			<tr class="border-left">
				<td>1</td>
				<td>3i47yMdQ3Y</td>
				<td>prueba detraccion</td>
				<td>UND</td>
				<td>1.00</td>
				<td>50.00</td>
				<td>50.00</td>
			</tr>
			<tr class="border-left">
				<td>2</td>
				<td>3i47yMdQ3Y</td>
				<td>prueba detraccion</td>
				<td>UND</td>
				<td>1.00</td>
				<td>50.00</td>
				<td>50.00</td>
			</tr>
			<tr class="border-left">
				<td>3</td>
				<td>3i47yMdQ3Y</td>
				<td>prueba detraccion</td>
				<td>UND</td>
				<td>1.00</td>
				<td>50.00</td>
				<td>50.00</td>
			</tr>
			<tr>
				<td colspan="7" class="text-uppercase" style="border-top: solid #000 0px!important; border-bottom: 0px!important">SON: CINCUENTA Y NUEVE CON 00/100 SOLES</td>
			</tr>
		</tbody>
	</table>
	<div class="table-5">
		<div class="item-table-4 text-center" style="padding: 5px 15px;">
			<div class="position-relative">
				<p class="font-weight-bold">Introduce un texto aquí!</p>
				<div class="theme02_txt_pdf_a4_2 single-editable-box"  contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_a4_2); } ?></div>
				<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
			</div>
			<p>Bienes para ser el consumidor en la amazonia peruana, es na que solo a veces puede ir para cierto tipos de empresas...</p>
			<p><span class="font-weight-bold color-blue">Observación:</span> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores, quisquam? Qui maiores culpa alias pariatur eius cupiditate rerum distinctio molestiae repellat.</p>
		</div>
		<div class="item-table-4">
			<div class="row">
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom font-weight-bold" style="border-top: 0!important;border-right: 0!important">Gravado </p></div>
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom" style="border-top: 0!important">S/ 50.00</p></div>
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom  font-weight-bold" style="border-top: 0!important;border-right: 0!important">IGV (18.00%): </p></div>
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom" style="border-top: 0!important">S/ 50.00</p></div>
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom font-weight-bold" style="border-top: 0!important;border-right: 0!important">Descuento Total </p></div>
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom" style="border-top: 0!important">S/ 50.00</p></div>
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom font-weight-bold" style="border-top: 0!important;border-right: 0!important">Total </p></div>
				<div class="col-lg-6 p-0"><p class="sub-total border-bottom" style="border-top: 0!important">S/ 50.00</p></div>
			</div>
		</div>
	</div>
	<div class="row table-footer">
		<div class="col-lg-12">
			<p class="text-uppercase font-weight-bold">Detracción en Moneda Soles</p>
			<div class="box-border row">
				<div class="col-lg-6">
					<p>% Detracción: 10.00</p>
				</div>
				<div class="col-lg-6">
					<p>Monto Detracción: 5.90</p>
				</div>
				<div class="col-lg-12">
					<p>Descripción: OPERACION SUJETA AL SISTEMA DE PAGO OBLIGACIONES TRIBUTARIAS DEL BANCO DE LA NACION 212313515152222
					</p>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="row table-cuentas">
				<div class="col-lg-12 border" style="border-bottom: 0!important;">
					<p class="text-uppercase">Usted puede hacer pagos directamente en nuestras cuentas corrientes</p>
				</div>
				<div class="col-lg-12">
					<p class="text-uppercase">BANCO: BCP</p> 
					<p><span class="text-uppercase">NRO CUENTA (SOLES): 24596035269047</p>
					<p><span class="text-uppercase">CCI: 00224519603526904797</p>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="box-border">
				<div class="table-6">
					<div class="item-table-6">
						<p>Autorizado mediante la resolución Nº 064-005-0002737/Sunat</p>
						<p>Representación impresa de la Factura Electrónica</p>
						<p>Para consultar el comprobante visita https://<?php echo $patrocinador['url_domain']; ?>/facturacionv8/consultas/index/<?php echo $contribuyente->id_contribuyente; ?></p>
						<p>Resumen: tk5zRD3Gf5cdXxAf8NgLEhxxyt4</p>
						<p>Atentido Por: <?php echo $usuario->nombre.' '.$usuario->apellido; ?> (cod. <?php echo $usuario->idusuario; ?>)</p>
					</div>
					<div class="item-table-6">
						<img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Commons_QR_code.png" alt="" class="float-right" width="100px">
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<div class="position-relative">
		<p class="font-weight-bold">Introduce un texto aquí!</p>
		<div class="theme02_txt_pdf_a4_3 single-editable-box"  contenteditable="true"><?php if($sucursal) { echo html_entity_decode($sucursal->txt_pdf_a4_3); } ?></div>
		<img src="/facturacionv8/img/arrow_left.png" alt="" width="25px" class="arrow_left">
	</div>
</div>
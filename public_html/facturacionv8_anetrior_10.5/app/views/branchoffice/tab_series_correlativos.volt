<div class="alert alert-warning alert-styled-left">
	<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
	<span class="text-semibold">Importante!</span> Las series y correlativos de tus comprobantes no se deben repetir entre sucursales, una vez creada la sucursal ya no podrás cambiar las series!. Si vas a crear una segunda sucursal, cambié el número 1 de cada serie por el número dos, y si fuese una tercera sucursal cambia por el número tres, y así sucesivamente...
</div>

<div class="table-responsive">
	<table class="table table-bordered" id="tabla_user">
		<thead>
			<tr>
				<th>Tipo de comprobante</th>
				<th class="factura_serie">Serie</th>
				<th>Número</th>
				<th>Formato</th>
			</tr>
		</thead>
		<tbody>									
			<tr>
				<td class="font-weight-bold">Factura Eléctronica</td>
				<td> <input type="text" value="F001" id="factura_serie" name="factura_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="factura_numero" name="factura_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="factura_formato" id="factura_formato">
						<option value="A4">A4</option>
						<option value="A5">A5</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="font-weight-bold">Boleta de Venta Electrónica</td>
				<td> <input type="text" value="B001" id="boleta_serie" name="boleta_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="boleta_numero" name="boleta_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="boleta_formato" id="boleta_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="font-weight-bold">Nota de Crédito que modifica una Factura</td>
				<td> <input type="text" value="FC01" id="notacredito_factura_serie" name="notacredito_factura_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="notacredito_factura_numero" name="notacredito_factura_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="notacredito_factura_formato" id="notacredito_factura_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="font-weight-bold">Nota de Débito que modifica una Factura</td>
				<td> <input type="text" value="FD01" id="notadebito_factura_serie" name="notadebito_factura_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="notadebito_factura_numero" name="notadebito_factura_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="notadebito_factura_formato" id="notadebito_factura_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="font-weight-bold">Nota de Crédito que modifica una Boleta</td>
				<td> <input type="text" value="BC01" id="notacredito_boleta_serie" name="notacredito_boleta_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="notacredito_boleta_numero" name="notacredito_boleta_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="notacredito_boleta_formato" id="notacredito_boleta_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="font-weight-bold">Nota de Débito que modifica una Boleta</td>
				<td> <input type="text" value="BD01" id="notadebito_boleta_serie" name="notadebito_boleta_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="notadebito_boleta_numero" name="notadebito_boleta_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="notadebito_boleta_formato" id="notadebito_boleta_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="font-weight-bold">Guía de Remisión</td>
				<td> <input type="text" value="T001" id="guia_remision_serie" name="guia_remision_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="guia_remision_numero" name="guia_remision_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="guia_remision_formato" id="guia_remision_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>

			<tr>
				<td class="font-weight-bold">Guía Transportista</td>
				<td> <input type="text" value="V001" id="guia_transportista_serie" name="guia_transportista_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="guia_transportista_numero" name="guia_transportista_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="guia_transportista_formato" id="guia_transportista_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td class="font-weight-bold">Orden de Compra</td>
				<td> <input type="text" value="OC01" id="orden_compra_serie" name="orden_compra_serie" class="txt_propiedad_docelect form-control"> </td>
				<td> <input type="text" value="1" id="orden_compra_numero" name="orden_compra_numero" class="txt_propiedad_docelect form-control"> </td>
				<td> 
					<select class="js-example-basic-single" name="orden_compra_formato" id="orden_compra_formato">
						<option value="A4">A4</option>
						<option value="ticket">Ticket</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="col-lg-12 mt-20 text-right">
	<button class="btn bg-indigo legitRipple btn_branchoffice" type="button">
		<i class="icon-floppy-disk mr-2"></i>
		Guardar Todos los Datos!
	</button>
</div>
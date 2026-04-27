<style>
.limiter {
	width: 100%;
	margin: 0 auto;
}

.container-table100 {
	width: 100%;
	min-height: 100vh;
	background: #d1d1d1;
	display: -webkit-box;
	display: -webkit-flex;
	display: -moz-box;
	display: -ms-flexbox;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-wrap: wrap;
	padding: 33px 30px;
}

.wrap-table100 {
	width: 1300px;
}


.column100 {
	width: 130px;
	padding-left: 25px;
}

.column100.column1 {
	width: 265px;
	padding-left: 42px;
}

.row100.head th {
	padding-top: 24px;
	padding-bottom: 20px;
}

.row100 td {
	padding-top: 18px;
	padding-bottom: 14px;
}

	
/*==================================================================
[ Ver1 ]*/
.table100.ver1 td {
	line-height: 1.4;
}

.table100.ver1 th {
	font-size: 12px;
	color: #fff;
	line-height: 1.4;
	text-transform: uppercase;
	background-color: #36304a;
}

.table100.ver1 .row100:hover {
	background-color: #f2f2f2;
}

.table100.ver1 .hov-column-ver1 {
	background-color: #f2f2f2;
}

.table100.ver1 .hov-column-head-ver1 {
	background-color: #484848 !important;
}

.table100.ver1 .row100 td:hover {
	background-color: #3f51b5;
	color: #fff;
}
.font-weight-700{
	font-weight: 700;
}
.head-table{
	padding: 20px;
	font-size: 14px;
}

</style>
<div class="table-responsive" id="tabla_caja_chica">
	<div class="table100 ver1 m-b-110">
		<table class="table table-bordered" data-vertable="ver1">
			<tr class="bg-indigo text-center head-table font-weight-700">
				<td>Comprobantes</td>
				<td id="cabecera_cash"  colspan="2">Cash/Efectivo</td>
				<td id="cabecera_tarjeta" colspan="2">Tarjeta Crédito/Débito</td>
				<td id="cabecera_transferencia" colspan="2" >Transferencia</td>
				<td id="cabecera_parcial" colspan="2" style="display: none;">Pago Parcial</td>
				<td id="cabecera_porcobrar" colspan="2" >Cuenta por Cobrar</td>
				<td id="cabecera_total" colspan="2">Total</td>
				</tr>
				<tr id="fila_Facturas">
					<td></td>
					<td class="montos_en_soles">S/.</td>
					<td class="montos_en_dolares">$</td>
					<td class="montos_en_soles">S/.</td>
					<td class="montos_en_dolares">$</td>
					<td class="montos_en_soles">S/.</td>
					<td class="montos_en_dolares">$</td>
					<td class="montos_en_soles">S/.</td>
					<td class="montos_en_dolares">$</td>
					<td style="display: none;">S/.</td>
					<td style="display: none;">$</td>
					<td class="montos_en_soles">S/.</td>
					<td class="montos_en_dolares">$</td>
				</tr>
			<tr class="row100" id="fila_Facturas">
				<td class="column100 column1 tool_tip" data-column="column1" onclick="ver_detalle_caja('01', '', '')"><img src="/facturacionv8/img/factura.svg" style="width: 25px;margin-right: 15px;">Facturas <span style="display: none;" id="factura_total_documentos" class="badge badge-success"></span></td>
				<td class="montos_en_soles column100 column2 tool_tip" data-column="column2" id="factura_cash_soles" onclick="ver_detalle_caja('01', 'PEN', 'contado')">&nbsp;</td>
				<td class="montos_en_dolares column100 column3 tool_tip" data-column="column3" id="factura_cash_dolares" onclick="ver_detalle_caja('01', 'USD', 'contado')">&nbsp;</td>
				<td class="montos_en_soles column100 column4 tool_tip" data-column="column4" id="factura_tarjeta_soles" onclick="ver_detalle_caja('01', 'PEN', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column5 tool_tip" data-column="column5" id="factura_tarjeta_dolares" onclick="ver_detalle_caja('01', 'USD', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column6 tool_tip" data-column="column6" id="factura_transferencia_soles" onclick="ver_detalle_caja('01', 'PEN', 'transferencia')">&nbsp;</td>
				<td class="montos_en_dolares column100 column7 tool_tip" data-column="column7" id="factura_transferencia_dolares" onclick="ver_detalle_caja('01', 'USD', 'transferencia')">&nbsp;</td>
				<td style="display: none;" class="column100 column8" data-column="column8" id="factura_credito_soles">&nbsp;</td>
				<td style="display: none;" class="column100 column9" data-column="column9" id="factura_credito_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column10 tool_tip" data-column="column10" id="factura_montoadeudado_soles" onclick="ver_detalle_caja('01', 'PEN', '', 'credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column11 tool_tip" data-column="column11" id="factura_montoadeudado_dolares" onclick="ver_detalle_caja('01', 'USD', '', 'credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column12 tool_tip" data-column="column12" id="factura_total_soles" onclick="ver_detalle_caja('01', 'PEN', '')">&nbsp;</td>
				<td class="montos_en_dolares column100 column13 tool_tip" data-column="column13" id="factura_total_dolares" onclick="ver_detalle_caja('01', 'USD', '')">&nbsp;</td>
			</tr>
			<tr class="row100" id="fila_boletas">
				<td class="column100 column1 tool_tip" data-column="column1" onclick="ver_detalle_caja('03', '', '')"><img src="/facturacionv8/img/boleta.svg" style="width: 25px;margin-right: 15px;">Boletas <span style="display: none;" id="boleta_total_documentos" class="badge badge-success"></span></td>
				<td class="montos_en_soles  column100 column2 tool_tip" data-column="column2" id="boleta_cash_soles" onclick="ver_detalle_caja('03', 'PEN', 'contado')">&nbsp;</td>
				<td class="montos_en_dolares  column100 column3 tool_tip" data-column="column3" id="boleta_cash_dolares" onclick="ver_detalle_caja('03', 'USD', 'contado')">&nbsp;</td>
				<td class="montos_en_soles  column100 column4 tool_tip" data-column="column4" id="boleta_tarjeta_soles" onclick="ver_detalle_caja('03', 'PEN', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_dolares  column100 column5 tool_tip" data-column="column5" id="boleta_tarjeta_dolares" onclick="ver_detalle_caja('03', 'USD', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_soles  column100 column6 tool_tip" data-column="column6" id="boleta_transferencia_soles" onclick="ver_detalle_caja('03', 'PEN', 'transferencia')">&nbsp;</td>
				<td class="montos_en_dolares  column100 column7 tool_tip" data-column="column7" id="boleta_transferencia_dolares" onclick="ver_detalle_caja('03', 'USD', 'transferencia')">&nbsp;</td>
				<td style="display: none;"  class="column100 column8" data-column="column8" id="boleta_credito_soles">&nbsp;</td>
				<td style="display: none;" class="column100 column9" data-column="column9" id="boleta_credito_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column10 tool_tip" data-column="column10" id="boleta_montoadeudado_soles" onclick="ver_detalle_caja('03', 'PEN', '', 'credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column11 tool_tip" data-column="column11" id="boleta_montoadeudado_dolares" onclick="ver_detalle_caja('03', 'USD', '', 'credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column12 tool_tip" data-column="column12" id="boleta_total_soles" onclick="ver_detalle_caja('03', 'PEN', '')">&nbsp;</td>
				<td class="montos_en_dolares column100 column13 tool_tip" data-column="column13" id="boleta_total_dolares" onclick="ver_detalle_caja('03', 'USD', '')">&nbsp;</td>
			<tr>
			<tr class="row100" id="fila_nota_credito">
				<td class="column100 column1 tool_tip" data-column="column1" onclick="ver_detalle_caja('07', '', '')"><img src="/facturacionv8/img/nota_credito.svg" style="width: 25px;margin-right: 15px;">Notas Crédito <span style="display: none;" id="nota_credito_total_documentos" class="badge badge-success"></span></td>
				<td class="montos_en_soles column100 column2 tool_tip" data-column="column2" id="nota_credito_cash_soles" onclick="ver_detalle_caja('07', 'PEN', 'contado')">&nbsp;</td>
				<td class="montos_en_dolares column100 column3 tool_tip" data-column="column3" id="nota_credito_cash_dolares" onclick="ver_detalle_caja('07', 'USD', 'contado')">&nbsp;</td>
				<td class="montos_en_soles column100 column4 tool_tip" data-column="column4" id="nota_credito_tarjeta_soles" onclick="ver_detalle_caja('07', 'PEN', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column5 tool_tip" data-column="column5" id="nota_credito_tarjeta_dolares" onclick="ver_detalle_caja('07', 'USD', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column6 tool_tip" data-column="column6" id="nota_credito_transferencia_soles" onclick="ver_detalle_caja('07', 'PEN', 'transferencia')">&nbsp;</td>
				<td class="montos_en_dolares column100 column7 tool_tip" data-column="column7" id="nota_credito_transferencia_dolares" onclick="ver_detalle_caja('07', 'USD', 'transferencia')">&nbsp;</td>
				<td style="display: none;" class="column100 column8" data-column="column8" id="nota_credito_credito_soles">&nbsp;</td>
				<td style="display: none;" class="column100 column9" data-column="column9" id="nota_credito_credito_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column10 tool_tip" data-column="column10" id="nota_credito_montoadeudado_soles" onclick="ver_detalle_caja('07', 'PEN', '', 'credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column11 tool_tip" data-column="column11" id="nota_credito_montoadeudado_dolares" onclick="ver_detalle_caja('07', 'USD', '', 'credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column12 tool_tip" data-column="column12" id="nota_credito_total_soles" onclick="ver_detalle_caja('07', 'PEN', '')">&nbsp;</td>
				<td class="montos_en_dolares column100 column13 tool_tip" data-column="column13" id="nota_credito_total_dolares" onclick="ver_detalle_caja('07', 'USD', '')">&nbsp;</td>
			</tr>
			<tr class="row100" id="fila_nota_debito">
				<td class="column100 column1 tool_tip" data-column="column1" onclick="ver_detalle_caja('08', '', '')"><img src="/facturacionv8/img/nota_debito.svg" style="width: 25px;margin-right: 15px;">Notas Débito <span style="display: none;" id="nota_debito_total_documentos" class="badge badge-success"></span></td>
				<td class="montos_en_soles column100 column2 tool_tip" data-column="column2" id="nota_debito_cash_soles" onclick="ver_detalle_caja('08', 'PEN', 'contado')">&nbsp;</td>
				<td class="montos_en_dolares column100 column3 tool_tip" data-column="column3" id="nota_debito_cash_dolares" onclick="ver_detalle_caja('08', 'USD', 'contado')">&nbsp;</td>
				<td class="montos_en_soles column100 column4 tool_tip" data-column="column4" id="nota_debito_tarjeta_soles" onclick="ver_detalle_caja('08', 'PEN', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column5 tool_tip" data-column="column5" id="nota_debito_tarjeta_dolares" onclick="ver_detalle_caja('08', 'USD', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column6 tool_tip" data-column="column6" id="nota_debito_transferencia_soles" onclick="ver_detalle_caja('08', 'PEN', 'transferencia')">&nbsp;</td>
				<td class="montos_en_dolares column100 column7 tool_tip" data-column="column7" id="nota_debito_transferencia_dolares" onclick="ver_detalle_caja('08', 'USD', 'transferencia')">&nbsp;</td>
				<td style="display: none;" class="column100 column8"  data-column="column8" id="nota_debito_credito_soles">&nbsp;</td>
				<td style="display: none;" class="column100 column9"  data-column="column9" id="nota_debito_credito_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column10 tool_tip" data-column="column10" id="nota_debito_montoadeudado_soles" onclick="ver_detalle_caja('08', 'PEN', '', 'credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column11 tool_tip" data-column="column11" id="nota_debito_montoadeudado_dolares" onclick="ver_detalle_caja('08', 'USD', '', 'credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column12 tool_tip" data-column="column12" id="nota_debito_total_soles" onclick="ver_detalle_caja('08', 'PEN', '')">&nbsp;</td>
				<td class="montos_en_dolares column100 column13 tool_tip" data-column="column13" id="nota_debito_total_dolares" onclick="ver_detalle_caja('08', 'USD', '')">&nbsp;</td>
			</tr>
			<tr class="row100" id="fila_nota_venta">
				<td class="column100 column1 tool_tip" data-column="column1" onclick="ver_detalle_caja('77', '', '')"><img src="/facturacionv8/public/img/svg/nota_venta2.svg" style="width: 25px;margin-right: 15px;"> Nota de Venta <span style="display: none;" id="nota_venta_total_documentos" class="badge badge-success"></span></td>
				<td class="montos_en_soles column100 column2 tool_tip" data-column="column2" id="nota_venta_cash_soles" onclick="ver_detalle_caja('77', 'PEN', 'contado')">&nbsp;</td>
				<td class="montos_en_dolares column100 column3 tool_tip" data-column="column3" id="nota_venta_cash_dolares" onclick="ver_detalle_caja('77', 'USD', 'contado')">&nbsp;</td>
				<td class="montos_en_soles column100 column4 tool_tip" data-column="column4" id="nota_venta_tarjeta_soles" onclick="ver_detalle_caja('77', 'PEN', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column5 tool_tip" data-column="column5" id="nota_venta_tarjeta_dolares" onclick="ver_detalle_caja('77', 'USD', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column6 tool_tip" data-column="column6" id="nota_venta_transferencia_soles" onclick="ver_detalle_caja('77', 'PEN', 'transferencia')">&nbsp;</td>
				<td class="montos_en_dolares column100 column7 tool_tip" data-column="column7" id="nota_venta_transferencia_dolares" onclick="ver_detalle_caja('77', 'USD', 'transferencia')">&nbsp;</td>
				<td style="display: none;"  class="column100 column8" data-column="column8" id="nota_venta_credito_soles">&nbsp;</td>
				<td style="display: none;" class="column100 column9" data-column="column9" id="nota_venta_credito_dolares">&nbsp;</td>
				<td class="montos_en_soles  column100 column10 tool_tip" data-column="column10"  id="nota_venta_montoadeudado_soles" onclick="ver_detalle_caja('77', 'PEN', '', 'credito')">&nbsp;</td>
				<td class="montos_en_dolares  column100 column11 tool_tip" data-column="column11"  id="nota_venta_montoadeudado_dolares" onclick="ver_detalle_caja('77', 'USD', '', 'credito')">&nbsp;</td>
				<td class="montos_en_soles  column100 column12 tool_tip" data-column="column12"  id="nota_venta_total_soles" onclick="ver_detalle_caja('77', 'PEN', '')">&nbsp;</td>
				<td class="montos_en_dolares  column100 column13 tool_tip" data-column="column13"  id="nota_venta_total_dolares" onclick="ver_detalle_caja('77', 'USD', '')">&nbsp;</td>
			</tr>
			<tr class="row100" id="fila_condicion_pago">
				<td class="column100 column1 tool_tip" data-column="column1" onclick="ver_detalle_abonos('', '')"><img src="/facturacionv8/public/img/svg/abonos.svg" style="width: 25px;margin-right: 15px;">Abonos <span style="display: none;" id="abono_total_documentos" class="badge badge-success"></span></td>
				<td class="montos_en_soles column100 column2 tool_tip" data-column="column2" id="abono_cash_soles" onclick="ver_detalle_abonos('PEN', 'contado')">&nbsp;</td>
				<td class="montos_en_dolares column100 column3 tool_tip" data-column="column3" id="abono_cash_dolares" onclick="ver_detalle_abonos('USD', 'contado')">&nbsp;</td>
				<td class="montos_en_soles column100 column4 tool_tip" data-column="column4" id="abono_tarjeta_soles" onclick="ver_detalle_abonos('PEN', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_dolares column100 column5 tool_tip" data-column="column5" id="abono_tarjeta_dolares" onclick="ver_detalle_abonos('USD', 'tarjeta_credito')">&nbsp;</td>
				<td class="montos_en_soles column100 column6 tool_tip" data-column="column6" id="abono_transferencia_soles" onclick="ver_detalle_abonos('PEN', 'transferencia')">&nbsp;</td>
				<td class="montos_en_dolares column100 column7 tool_tip" data-column="column7" id="abono_transferencia_dolares" onclick="ver_detalle_abonos('USD', 'transferencia')">&nbsp;</td>
				<td style="display: none;"  class="column100 column8" data-column="column8" id="abono_credito_soles">&nbsp;</td>
				<td style="display: none;"  class="column100 column9" data-column="column9" id="abono_credito_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column10" data-column="column10" id="abono_montoadeudado_soles">&nbsp;</td>
				<td class="montos_en_dolares column100 column11" data-column="column11" id="abono_montoadeudado_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column12 tool_tip" data-column="column12" id="abono_total_soles" onclick="ver_detalle_abonos('PEN', '')">&nbsp;</td>
				<td class="montos_en_dolares column100 column13 tool_tip" data-column="column13" id="abono_total_dolares" onclick="ver_detalle_abonos('USD', '')">&nbsp;</td>
			</tr>
			<tr class="row100" id="fila_totales" style="font-weight: bold;">
				<td class="column100 column1 tool_tip" data-column="column1" onclick="ver_detalle_abonos('', '', 'todo')"><img src="/facturacionv8/public/img/svg/subtotales.svg" style="width: 25px;margin-right: 15px;">SubTotales</td>
				<td class="montos_en_soles column100 column2  tool_tip" data-column="column2" id="totales_cash_soles" onclick="ver_detalle_abonos('PEN', 'contado', 'todo')">&nbsp;</td>
				<td class="montos_en_dolares column100 column3 tool_tip" data-column="column3" id="totales_cash_dolares" onclick="ver_detalle_abonos('USD', 'contado', 'todo')">&nbsp;</td>
				<td class="montos_en_soles column100 column4 tool_tip" data-column="column4" id="totales_tarjeta_soles" onclick="ver_detalle_abonos('PEN', 'tarjeta_credito', 'todo')">&nbsp;</td>
				<td class="montos_en_dolares column100 column5 tool_tip" data-column="column5" id="totales_tarjeta_dolares" onclick="ver_detalle_abonos('USD', 'tarjeta_credito', 'todo')">&nbsp;</td>
				<td class="montos_en_soles column100 column6 tool_tip" data-column="column6" id="totales_transferencia_soles" onclick="ver_detalle_abonos('PEN', 'transferencia', 'todo')">&nbsp;</td>
				<td class="montos_en_dolares column100 column7 tool_tip" data-column="column7" id="totales_transferencia_dolares" onclick="ver_detalle_abonos('USD', 'transferencia', 'todo')">&nbsp;</td>
				<td style="display: none;"  class="column100 column8" data-column="column8" id="totales_credito_soles">&nbsp;</td>
				<td style="display: none;"  class="column100 column9" data-column="column9" id="totales_credito_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column10" data-column="column10" id="totales_montoadeudado_soles">&nbsp;</td>
				<td class="montos_en_dolares column100 column11" data-column="column11" id="totales_montoadeudado_dolares">&nbsp;</td>
				<td class="montos_en_soles column100 column12 tool_tip" data-column="column12" id="totales_total_soles" onclick="ver_detalle_abonos('PEN', '', 'todo')">&nbsp;</td>
				<td class="montos_en_dolares column100 column13 tool_tip" data-column="column13" id="totales_total_dolares" onclick="ver_detalle_abonos('USD', '', 'todo')">&nbsp;</td>
			</tr>
				
			<tr id="totales_cobros" class="text-success font-weight-700" style="display: none;">
				<td colspan="9" class="text-right texto_subtotales">Cuentas Pagadas: </td>
				<td class="montos_en_soles" id="totales_cobros_soles">&nbsp;</td>
				<td class="montos_en_dolares" id="totales_cobros_dolares">&nbsp;</td>
			</tr>
			<tr id="totales_ingresos" class="text-success font-weight-700">
				<td colspan="9" class="text-right texto_subtotales">Ingresos Caja: </td>
				<td class="montos_en_soles" id="totales_ingresoscaja_soles">&nbsp;</td>
				<td class="montos_en_dolares" id="totales_ingresoscaja_dolares">&nbsp;</td>
			</tr>
			<tr id="totales_resumen" class="text-danger font-weight-700">
				<td colspan="9" class="text-right texto_subtotales">Egresos Caja: </td>
				<td class="montos_en_soles" id="totales_egresoscaja_soles">&nbsp;</td>
				<td class="montos_en_dolares" id="totales_egresoscaja_dolares">&nbsp;</td>
			</tr>
			<tr id="totales_totales" class="text-indigo font-weight-700">
				<td colspan="9" class="text-right texto_subtotales">Total en Caja: </td>
				<td class="montos_en_soles" id="totales_totalcaja_soles">&nbsp;</td>
				<td class="montos_en_dolares" id="totales_totalcaja_dolares">&nbsp;</td>
			</tr>
		</table>
	</div>
</div>
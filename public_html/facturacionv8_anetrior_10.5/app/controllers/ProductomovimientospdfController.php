<?php
class ProductomovimientospdfController extends ControllerBase
{
	public function template_transformacion_a4_1($cpe) {
		$html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>Transformación: '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 10px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			.tb_resumen_totales {
				width: 100%;
			}
			body{
				position: relative;
				font-size: 11px;
				font-family: arial, sans-serif;
				color: #000;
			}
			.codigo_qr p{
				font-size: 11px;
				line-height: 1.5;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			.table td, .table th {
				padding: .6rem;
				vertical-align: top;
				border: 0;
			
			}
			
			.table thead th {
				vertical-align: bottom;
				
			}
			table {
				font-size: 11px;
			}
			.table_detraccion, .table_percepcion{
				border: 1px solid #000;
			}
			.table_detraccion td, .table_percepcion td{
				border:none;
				padding: 5px;
				font-size: 11px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 11px;
				color: #000;
			}
			td, th {
				text-align: left;
				padding: 5px;
			}
			th {
				text-align: center;
			}
			.col-2 {
				width: 16.66666667%;
				float: left;
				padding: 0;
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
			.col-5 {
				width: 41.66666667%;
				float: left;
				padding: 0;
			}
			.col-6 {
				width: 50%;
				float: left;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}

			.col-8{
				width: 66.666667%;
				float:left;
			}
			.col-9 {
				width: 75%;
				float: left;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			
			.masthead {
				display: block;
				height: 200px;
			}
			.head-pg{
				position:relative;
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'inactivo') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}  
			
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			} 
			
			if($cpe['cabecera']['estado_documento'] == 'inactivo' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
			$html = $html.'
		
			.table-head p{
				line-height: 2;
			}
			.tb_resumen_totales td {
				padding: 2px 5px;
				border: solid #5f5f5f 1px;
				border-top: none;
				text-align: left;
			}
			.resumen_totales {
				height: 100px;
			}
			.spacing-lg {
				width: 60%;
			}
			/* Tablas */

			.table-cuentas {
				border-collapse: separate !important;
				border-spacing: 0;
				border-top: solid #000 1px;
				border-left: solid #000 1px;
				border-right: solid #000 1px;
				border-bottom: solid #000 1px!important;
			}
			.table-cuentas th {
				margin-bottom: 0;
			}
		
			.table-cuentas td{
				padding: 5px;
			}
			.table-cuentas td p{
				font-size: 9px;
			}
			.table-footer p {
				font-size: 9px;
			}
			.table-items-productos {
				margin: 0;
				border: 1px solid #000;
				border-bottom-right-radius: 0px!important;
			}
			.table-items-productos th {
				margin-bottom: 0;
			}
			.table-items-productos td {
				border: none;
				text-align: center;
				
			}
			
			.bg-main{
				background: #006cae;
			}
			.notas_doc{
				/* dividir cadenas largas en lineas*/
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-footer {
				border: 1px solid #000;
				width: 100%;
			}

			/* === Tabla Bordes === */
			.border-left{
				border-left: 1px solid #000!important;
			}
			.border-right{
				border-right: 1px solid #000!important;
			}
			.table-head {
				border: 1px solid #000;
				text-align: center;
				width: 100%;
				font-size: 11px!important;
			}
			.table-head td{
				padding: .8em 1em;
				line-height: 2;
				font-size: 14px;
			}
			.table-2 {
				border: 1px solid #000;
			}
			.table-2 td{
				padding: 1em;
			}
			.table-client{ 
				border: 1px solid #000;
				padding-top: 1px solid #000;
			}
			
			.bordered {
				border-collapse: separate !important;
				border-spacing: 0;
				-moz-border-radius: 12px;
				-webkit-border-radius: 12px;
				border-radius: 12px;
				width: 100%;

			}
			.bordered th {
				border-top: none;
			}
			.bordered td:first-child, .bordered th:first-child {
				border-left: none;
			}
			.bordered th:first-child {
				-moz-border-radius: 12px 0 0 0;
				-webkit-border-radius: 12px 0 0 0;
				border-radius: 12px 0 0 0;
			}
			.bordered th:last-child {
				-moz-border-radius: 0 12px 0 0;
				-webkit-border-radius: 0 12px 0 0;
				border-radius: 0 12px 0 0;
			}
			.bordered th:only-child{
				-moz-border-radius: 12px 12px 0 0;
				-webkit-border-radius: 12px 12px 0 0;
				border-radius: 12px 12px 0 0;
			}
			.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 12px;
				-webkit-border-radius: 0 0 0 12px;
				border-radius: 0 0 0 12px;
			}
			.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 12px 0;
				-webkit-border-radius: 0 0 12px 0;
				border-radius: 0 0 12px 0;
			
			}
			/* === Tabla 4 === */
			.table-4.bordered {
				border-collapse: separate !important;
				border-spacing: 0;
				-moz-border-radius: 6px;
				-webkit-border-radius: 6px;
				/* border-radius: 6px; */
				border-top-left-radius: 6px;
				border-top-right-radius: 6px;
				border-bottom-right-radius: 0px!important;
				border-bottom-left-radius: 6px;
			}
			.table-4.bordered th:first-child {
				-moz-border-radius: 6px 0 0 0;
				-webkit-border-radius: 6px 0 0 0;
				border-radius: 6px 0 0 0;
			}
			.table-4.bordered th:last-child {
				-moz-border-radius: 0 6px 0 0;
				-webkit-border-radius: 0 6px 0 0;
				border-radius: 0 6px 0 0;
			}
			.table-4.bordered th:only-child{
				-moz-border-radius: 6px 6px 0 0;
				-webkit-border-radius: 6px 6px 0 0;
				border-radius: 6px 6px 0 0;
			}
			.table-4.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 6px;
				-webkit-border-radius: 0 0 0 6px;
				border-radius: 0 0 0 6px;
			}
			.table-4.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 0px 0;
				-webkit-border-radius: 0 0 6px 0;
				border-radius: 0 0 6px 0;
			} 
			
			.tb_resumen_totales {
				width: 180px;
			}
			
			.table-items-productos .td_descripcion{ 
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-retencion td{
				font-size: 11px;
				text-align: center;
			}
		</style>
		<body>';
		
            if(empty($cpe['emisor']['logo_rectangular'])) {
                $height = "style='height: 150px;'";
                    $html = $html.'
                    <div class="masthead w-100" '.$height.'>
                    <div class="col-3">
                        <img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px">
                    </div>
                    <div class="col-5 text-center" style="padding:10px 5px">
                        <h5 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h5>
                        '.$cpe['emisor']['direccion'].'
                        '.$cpe['emisor']['ubigeo'].'
                        <p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
                        '.$cpe['emisor']['sitio_web'].'
                    </div>
                    <div class="col-4  mt-1" style="padding:10px 5px">
                        <table class="table-head bordered" style="font-size: 15px!important;">
                            <tr>
                                <td colspan="3" class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
                            </tr>
                            <tr class="bg-main text-white">
                                <td colspan="3" class="text-center font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].'</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">Nro. '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
                            </tr>
                        </table>
                    </div>
                </div>';
            } else {
                $height = "style='height: 230px;'";
                    $html = $html.'
                    <div class="masthead w-100" '.$height.'>
                    <div class="col-7 text-center mb-4" style="padding:10px 5px">
                        <img src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px; padding:0!important; margin:0!important;">
                        <h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
                        '.$cpe['emisor']['direccion'].'
                        '.$cpe['emisor']['ubigeo'].'
                        <p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
                        '.$cpe['emisor']['sitio_web'].'
                    </div>
                    <div class="col-5"  style="padding:10px 5px">
                        <table class="table-head bordered">
                            <tr>
                                <td colspan="3" class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
                            </tr>
                            <tr class="bg-main text-white">
                                <td colspan="3" class="text-center font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].'</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">Nro. '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
                            </tr>
                        </table>
                    </div>
                </div>';
            }
            
            
			$html = $html.'
			<table class="table-2 bordered mt-30 mb-3">
				<tbody class="text-center">
					<tr>
						<td class="text-center">
							<p class="font-weight-bold">Fecha Registro:</p>
							<p>'.$cpe['cabecera']['fecha_registro'].'</p> 
						</td>

						<td class="border-left text-center">
							<p class="font-weight-bold">Sucursal Origen</p>
							<p>'.$cpe['cabecera']['sucursal'].'</p> 
						</td>

						<td class="border-left border-right text-center">
							<p class="font-weight-bold">Sucursal Destino</p>
							<p>'.$cpe['cabecera']['sucursal_destino'].'</p> 
						</td>
						
						<td class="text-center">
							<p class="font-weight-bold">Usuario</p>
							<p>'.$cpe['cabecera']['usuario'].'</p> 
						</td>
					</tr>
				</tbody>
			</table>';

			$html = $html.'
			<p class="font-weight-bold text-uppercase">Lista de Productos Iniciales</p>
            <table class="table-items-productos table-4 bordered">
                <tbody>
                    <tr class="text-uppercase bg-main text-white">
                        <th>Item</th>
                        <th>Código</th>
                        <th width="500px">Descripción</th>
                        <th>Unid.</th>
                        <th>Cantidad</th>
                    </tr>';
					$n1 = 0;
                    foreach($cpe['lista_productos_inicial'] as $item) {
						$n1++;
                        $html = $html.'
                        <tr>
                            <td align="center" class="text-center">'.$n1.'</td>
                            <td align="center">'.$item['cod_prod'].'</td>
                            <td class="text-left td_descripcion" style="width: 250px !important;">'.$item['nom_prod'].'</td>
                            <td align="center">'.$item['u_medida'].'</td>
                            <td align="center">'.($item['cantidad'] + 0).'</td>
                        </tr>
                        ';
                    }
                $html = $html.'
                </tbody>
            </table>
		    <br /><br />
			<p class="font-weight-bold text-uppercase">Lista de Productos Finales</p>
            <table class="table-items-productos table-4 bordered">
                <tbody>
                    <tr class="text-uppercase bg-main text-white">
                        <th>Item</th>
                        <th>Código</th>
                        <th width="500px">Descripción</th>
                        <th>Unid.</th>
                        <th>Cantidad</th>
                    </tr>';
					$n1 = 0;
                    foreach($cpe['lista_productos_finales'] as $item) {
						$n1++;
                        $html = $html.'
                        <tr>
                            <td align="center" class="text-center">'.$n1.'</td>
                            <td align="center">'.$item['cod_prod'].'</td>
                            <td class="text-left td_descripcion" style="width: 250px !important;">'.$item['nom_prod'].'</td>
                            <td align="center">'.$item['u_medida'].'</td>
                            <td align="center">'.($item['cantidad'] + 0).'</td>
                        </tr>
                        ';
                    }
                $html = $html.'
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <p>Detalle: '.$cpe['cabecera']['nota'].'</p>
            </div>
		</body>
		</html>
		';

        return $html;
	}

    public function template_salida_entrada_a4_1($cpe) {
        $html = '
		<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page{
				margin: 10px;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				margin-left: 0;
			}
			.tb_resumen_totales {
				width: 100%;
			}
			body{
				position: relative;
				font-size: 11px;
				font-family: arial, sans-serif;
				color: #000;
			}
			.codigo_qr p{
				font-size: 11px;
				line-height: 1.5;
			}
			.txt_pdf_a4_1, .txt_pdf_a4_2, .txt_pdf_a4_3{
				font-size: 11px;
			}
			.table td, .table th {
				padding: .6rem;
				vertical-align: top;
				border: 0;
			
			}
			
			.table thead th {
				vertical-align: bottom;
				
			}
			table {
				font-size: 11px;
			}
			.table_detraccion, .table_percepcion{
				border: 1px solid #000;
			}
			.table_detraccion td, .table_percepcion td{
				border:none;
				padding: 5px;
				font-size: 11px;
			}
			p{
				margin: 0;
				padding: 0;
				line-height: 1.3;
				font-size: 11px;
				color: #000;
			}
			td, th {
				text-align: left;
				padding: 5px;
			}
			th {
				text-align: center;
			}
			.col-2 {
				width: 16.66666667%;
				float: left;
				padding: 0;
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
			.col-5 {
				width: 41.66666667%;
				float: left;
				padding: 0;
			}
			.col-6 {
				width: 50%;
				float: left;
			}
			.col-7 {
				width: 55%;
				float: left;
				padding: 0;
			}

			.col-8{
				width: 66.666667%;
				float:left;
			}
			.col-9 {
				width: 75%;
				float: left;
				padding: 0;
			}
			.col-45 {
				width: 45%;
				float: left;
				padding: 0;
			}
			.float-right{
				float:right;
			}
			.float-left{
				float:left;
			}
			
			.masthead {
				display: block;
				height: 200px;
			}
			.head-pg{
				position:relative;
			}
			
			';
			if($cpe['cabecera']['estado_documento'] == 'inactivo') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				} ';
			}  
			
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			} 
			
			if($cpe['cabecera']['estado_documento'] == 'inactivo' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 1000px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_anulado.png);
					z-index: -1;
				} ';
			}
			$html = $html.'
		
			.table-head p{
				line-height: 2;
			}
			.tb_resumen_totales td {
				padding: 2px 5px;
				border: solid #5f5f5f 1px;
				border-top: none;
				text-align: left;
			}
			.resumen_totales {
				height: 100px;
			}
			.spacing-lg {
				width: 60%;
			}
			/* Tablas */

			.table-cuentas {
				border-collapse: separate !important;
				border-spacing: 0;
				border-top: solid #000 1px;
				border-left: solid #000 1px;
				border-right: solid #000 1px;
				border-bottom: solid #000 1px!important;
			}
			.table-cuentas th {
				margin-bottom: 0;
			}
		
			.table-cuentas td{
				padding: 5px;
			}
			.table-cuentas td p{
				font-size: 9px;
			}
			.table-footer p {
				font-size: 9px;
			}
			.table-items-productos {
				margin: 0;
				border: 1px solid #000;
				border-bottom-right-radius: 0px!important;
			}
			.table-items-productos th {
				margin-bottom: 0;
			}
			.table-items-productos td {
				border: none;
				text-align: center;
				
			}
			
			.bg-main{
				background: #006cae;
			}
			.notas_doc{
				/* dividir cadenas largas en lineas*/
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-footer {
				border: 1px solid #000;
				width: 100%;
			}

			/* === Tabla Bordes === */
			.border-left{
				border-left: 1px solid #000!important;
			}
			.border-right{
				border-right: 1px solid #000!important;
			}
			.table-head {
				border: 1px solid #000;
				text-align: center;
				width: 100%;
				font-size: 11px!important;
			}
			.table-head td{
				padding: .8em 1em;
				line-height: 2;
				font-size: 14px;
			}
			.table-2 {
				border: 1px solid #000;
			}
			.table-2 td{
				padding: 1em;
			}
			.table-client{ 
				border: 1px solid #000;
				padding-top: 1px solid #000;
			}
			
			.bordered {
				border-collapse: separate !important;
				border-spacing: 0;
				-moz-border-radius: 12px;
				-webkit-border-radius: 12px;
				border-radius: 12px;
				width: 100%;

			}
			.bordered th {
				border-top: none;
			}
			.bordered td:first-child, .bordered th:first-child {
				border-left: none;
			}
			.bordered th:first-child {
				-moz-border-radius: 12px 0 0 0;
				-webkit-border-radius: 12px 0 0 0;
				border-radius: 12px 0 0 0;
			}
			.bordered th:last-child {
				-moz-border-radius: 0 12px 0 0;
				-webkit-border-radius: 0 12px 0 0;
				border-radius: 0 12px 0 0;
			}
			.bordered th:only-child{
				-moz-border-radius: 12px 12px 0 0;
				-webkit-border-radius: 12px 12px 0 0;
				border-radius: 12px 12px 0 0;
			}
			.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 12px;
				-webkit-border-radius: 0 0 0 12px;
				border-radius: 0 0 0 12px;
			}
			.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 12px 0;
				-webkit-border-radius: 0 0 12px 0;
				border-radius: 0 0 12px 0;
			
			}
			/* === Tabla 4 === */
			.table-4.bordered {
				border-collapse: separate !important;
				border-spacing: 0;
				-moz-border-radius: 6px;
				-webkit-border-radius: 6px;
				/* border-radius: 6px; */
				border-top-left-radius: 6px;
				border-top-right-radius: 6px;
				border-bottom-right-radius: 0px!important;
				border-bottom-left-radius: 6px;
			}
			.table-4.bordered th:first-child {
				-moz-border-radius: 6px 0 0 0;
				-webkit-border-radius: 6px 0 0 0;
				border-radius: 6px 0 0 0;
			}
			.table-4.bordered th:last-child {
				-moz-border-radius: 0 6px 0 0;
				-webkit-border-radius: 0 6px 0 0;
				border-radius: 0 6px 0 0;
			}
			.table-4.bordered th:only-child{
				-moz-border-radius: 6px 6px 0 0;
				-webkit-border-radius: 6px 6px 0 0;
				border-radius: 6px 6px 0 0;
			}
			.table-4.bordered tr:last-child td:first-child {
				-moz-border-radius: 0 0 0 6px;
				-webkit-border-radius: 0 0 0 6px;
				border-radius: 0 0 0 6px;
			}
			.table-4.bordered tr:last-child td:last-child {
				-moz-border-radius: 0 0 0px 0;
				-webkit-border-radius: 0 0 6px 0;
				border-radius: 0 0 6px 0;
			} 
			
			.tb_resumen_totales {
				width: 180px;
			}
			
			.table-items-productos .td_descripcion{ 
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-retencion td{
				font-size: 11px;
				text-align: center;
			}
		</style>
		<body>';
		
            if(empty($cpe['emisor']['logo_rectangular'])) {
                $height = "style='height: 150px;'";
                    $html = $html.'
                    <div class="masthead w-100" '.$height.'>
                    <div class="col-3">
                        <img src="'.$cpe['emisor']['logo_cuadrado'].'" style="width: 100px; height: 100px">
                    </div>
                    <div class="col-5 text-center" style="padding:10px 5px">
                        <h5 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h5>
                        '.$cpe['emisor']['direccion'].'
                        '.$cpe['emisor']['ubigeo'].'
                        <p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
                        '.$cpe['emisor']['sitio_web'].'
                    </div>
                    <div class="col-4  mt-1" style="padding:10px 5px">
                        <table class="table-head bordered" style="font-size: 15px!important;">
                            <tr>
                                <td colspan="3" class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
                            </tr>
                            <tr class="bg-main text-white">
                                <td colspan="3" class="text-center font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].'</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">Nro. '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
                            </tr>
                        </table>
                    </div>
                </div>';
            } else {
                $height = "style='height: 230px;'";
                    $html = $html.'
                    <div class="masthead w-100" '.$height.'>
                    <div class="col-7 text-center mb-4" style="padding:10px 5px">
                        <img src="'.$cpe['emisor']['logo_rectangular'].'" style="width: 200px; padding:0!important; margin:0!important;">
                        <h6 class="font-weight-bold text-uppercase">'.$cpe['emisor']['nombre_comercial'].'</h6>
                        '.$cpe['emisor']['direccion'].'
                        '.$cpe['emisor']['ubigeo'].'
                        <p>Telf: '.$cpe['emisor']['telefono'].' - Email: '.$cpe['emisor']['email'].'</p>
                        '.$cpe['emisor']['sitio_web'].'
                    </div>
                    <div class="col-5"  style="padding:10px 5px">
                        <table class="table-head bordered">
                            <tr>
                                <td colspan="3" class="text-center font-weight-bold text-uppercase">R.U.C.: '.$cpe['emisor']['ruc'].'</td>
                            </tr>
                            <tr class="bg-main text-white">
                                <td colspan="3" class="text-center font-weight-bold">'.$cpe['cabecera']['nombre_cpe'].'</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">Nro. '.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</td>
                            </tr>
                        </table>
                    </div>
                </div>';
            }
            
            
			if($cpe['cabecera']['id_tipo_movimiento'] == 'in' || $cpe['cabecera']['id_tipo_movimiento'] == 'sa') {
				$html = $html.'
				<table class="table-2 bordered mt-30 mb-3">
					<tbody class="text-center">
						<tr>
							<td class="text-center">
								<p class="font-weight-bold">Fecha Registro:</p>
								<p>'.$cpe['cabecera']['fecha_registro'].'</p> 
							</td>
	
							<td class="border-left border-right text-center">
								<p class="font-weight-bold">Sucursal</p>
								<p>'.$cpe['cabecera']['sucursal'].'</p> 
							</td>
							
							<td class="border-left text-center">
								<p class="font-weight-bold">Usuario</p>
								<p>'.$cpe['cabecera']['usuario'].'</p> 
							</td>
						</tr>
					</tbody>
				</table>';
			} else if($cpe['cabecera']['id_tipo_movimiento'] == 'ts') {
				$html = $html.'
				<table class="table-2 bordered mt-30 mb-3">
					<tbody class="text-center">
						<tr>
							<td class="text-center">
								<p class="font-weight-bold">Fecha Registro:</p>
								<p>'.$cpe['cabecera']['fecha_registro'].'</p> 
							</td>
	
							<td class="border-left text-center">
								<p class="font-weight-bold">Sucursal Origen</p>
								<p>'.$cpe['cabecera']['sucursal'].'</p> 
							</td>

							<td class="border-left border-right text-center">
								<p class="font-weight-bold">Sucursal Destino</p>
								<p>'.$cpe['cabecera']['sucursal_destino'].'</p> 
							</td>
							
							<td class="text-center">
								<p class="font-weight-bold">Usuario</p>
								<p>'.$cpe['cabecera']['usuario'].'</p> 
							</td>
						</tr>
					</tbody>
				</table>';
			}

			$html = $html.'
            <table class="table-items-productos table-4 bordered">
                <tbody>
                    <tr class="text-uppercase bg-main text-white">
                        <th>Item</th>
                        <th>Código</th>
                        <th width="500px">Descripción</th>
                        <th>Unid.</th>
                        <th>Cantidad</th>
                    </tr>';
                    foreach($cpe['detalle'] as $item) {
                    
                        $html = $html.'
                        <tr>
                            <td align="center" class="text-center">'.$item['nro_item'].'</td>
                            <td align="center">'.$item['codigo'].'</td>
                            <td class="text-left td_descripcion" style="width: 250px !important;">'.$item['descripcion'].'</td>
                            <td align="center">'.$item['unidad_medida'].'</td>
                            <td align="center">'.($item['cantidad'] + 0).'</td>
                        </tr>
                        ';
                    }
                $html = $html.'
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <p>Detalle: '.$cpe['cabecera']['nota'].'</p>
            </div>
		</body>
		</html>
		';

        return $html;
    }

    public function template_salida_entrada_ticket_1($cpe) {
        $html = '
        <html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
			<link rel="stylesheet" href="https://arpsystem.com.pe/servicio-de-facturacion-electronica/fonts/flaticon/flaticon.css">
			<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700&display=swap" rel="stylesheet">
			<title>'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</title>
		</head>
		<style>
			@page { margin: 20px !important; }
			body{
				font-family: Arial Narrow, Arial, sans-serif;
				margin: 10px;
				position:relative;
			}
			';
			if($cpe['cabecera']['estado_documento'] == 'inactivo') {
				$html = $html.'
				.masthead::before{
					content: " ";
					position: absolute;
					top: 3em;
					left: 0;
					width: 100%;
					height: 800px;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/anulado_pg_ticket.png);
					background-repeat: no-repeat;
					backgroud-position: center right;
					z-index: -1;
				}';
			}
			if($cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/prueba_bg.png);
					z-index: -1;
				} ';
			}
			if($cpe['cabecera']['estado_documento'] == 'inactivo' && $cpe['emisor']['tipo_envio_sunat'] == 'prueba') {
				$html = $html.'
				body::before{
					content: " ";
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background-image: url(https://arpsystem.com.pe/facturacionv8/img/reporte_plantilla/anulado_pg_ticket_prueba.png);
					z-index: -1;
				} ';
			}

			$html = $html.'
			
			.table td, .table th {
				padding: 0.75rem;
				vertical-align: top;
				border-top: 1px solid #000;
			}
			p{
				margin: 0;
				padding: 0;
				font-size: 11px;
			}
			table{
				width: 100%;
				font-size: 11px!important;
			}
			th{
				text-align: left;
				padding: 8px;
				border-top: 1px solid #000;
				border-bottom: 1px solid #000;
			}
			td{
				
				padding: 2px;
			}
			.border-table{
				border-bottom: 1px solid #000;
			}
			.padding-right-4{
				padding-right: 4em;
			} 
			.nota_doc{
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			.table-cuotas th{
				border-bottom: 1px solid #000;
				border-top: 1px solid #000;
				font-weight: 200;
			}
			.table-main-head .td_descripcion {
				-ms-word-break: break-all;
				word-break: break-all;
				word-break: break-word;
				-ms-hyphens: auto;
				-moz-hyphens: auto;
				-webkit-hyphens: auto;
				hyphens: auto;
			}
			[class^="flaticon-"]:before, [class*=" flaticon-"]:before, [class^="flaticon-"]:after, [class*=" flaticon-"]:after {
				font-family: Flaticon;
				font-size: 12px;
			}
		</style>';
        
        $html = $html.'
		<body>
			<div class="masthead text-center">';
				if(empty($cpe['emisor']['logo_rectangular'])) {
					$html = $html.'
					<img style="width: 100px; height: 100px" src="'.$cpe['emisor']['logo_cuadrado'].'" />';
					
				}else{
					$html = $html.'
					<img style="width: 200px;"  src="'.$cpe['emisor']['logo_rectangular'].'" />';
				}
				$html = $html.'
				<h6 class="text-uppercase font-weight-bold">'.$cpe['emisor']['nombre_comercial'].'</h6>
				<p>R.U.C.: '.$cpe['emisor']['ruc'].'</p>
				<p>Direc.: '.$cpe['emisor']['direccion'].'
				<p>'.$cpe['emisor']['ubigeo'].'</p>
				<p><i class="flaticon-call mr-2"></i>Telf.: '.$cpe['emisor']['telefono'].'</p>
				<p><i class="flaticon-envelope mr-2"></i>Email: '.$cpe['emisor']['email'];
				if(!empty($cpe['emisor']['sitio_web'])) {
					$html = $html.'<p><img src="'.$cpe['emisor']['ruta_base'].'/facturacionv8/public/theme_doc_elect/images/website.svg" class="mr-2" style="width: 12px;" /> Website: '.$cpe['emisor']['sitio_web'].'</p>';
				}
				$html = $html.'
				<p class="font-weight-bold text-uppercase mt-3">'.$cpe['cabecera']['nombre_cpe'].'<p>
				<p class="font-weight-bold">'.$cpe['cabecera']['serie'].' - '.$cpe['cabecera']['correlativo'].'</p>
			</div>';

			if($cpe['cabecera']['id_tipo_movimiento'] == 'in' || $cpe['cabecera']['id_tipo_movimiento'] == 'sa') {
				$html = $html.'<div class="mt-1">
					<p><span class="font-weight-bold">Fecha Movimiento:</span> '.$cpe['cabecera']['fecha_registro'].'</p>
					<p><span class="font-weight-bold">Sucursal: </span>'.$cpe['cabecera']['sucursal'].'</p>
					<p><span class="font-weight-bold">Usuario: </span> '.$cpe['cabecera']['usuario'].'</p>
				</div>';
			} else if($cpe['cabecera']['id_tipo_movimiento'] == 'ts') {
				$html = $html.'<div class="mt-1">
					<p><span class="font-weight-bold">Fecha Movimiento:</span> '.$cpe['cabecera']['fecha_registro'].'</p>
					<p><span class="font-weight-bold">Usuario: </span> '.$cpe['cabecera']['usuario'].'</p>
					<p><span class="font-weight-bold">Sucursal Origen: </span>'.$cpe['cabecera']['sucursal'].'</p>
					<p><span class="font-weight-bold">Sucursal Destino: </span>'.$cpe['cabecera']['sucursal_destino'].'</p>
				</div>';
			}

			$html = $html.'
			<div class="table-content mt-1">
				<table class="table-main-head mt-2">
					<tbody>
						<tr class="font-weight-bold  mb-4">
							<th>Código</th>
							<th>Cant.</th>
							<th>Unidad</th>
						</tr>';

                        foreach($cpe['detalle'] as $item) {
                    
                            $html = $html.'
                            <tr>
                                <td>'.($item['codigo']).'</td>
                                <td class="text-center">'.($item['cantidad'] + 0).'</td>
                                <td calss="text-center">'.$item['unidad_medida'].'</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="font-weight-bold">'.$item['descripcion'].'<br /><br /></td>
                            </tr>
                            ';
                        }

                        $html = $html.'
					</tbody>
				</table>
			</div>
			<footer class="nota_doc">
				<p>Detalle: '.$cpe['cabecera']['nota'].'</p>
			</footer>
		</body>
		</html>
        ';

        return $html;
    }
}
?>
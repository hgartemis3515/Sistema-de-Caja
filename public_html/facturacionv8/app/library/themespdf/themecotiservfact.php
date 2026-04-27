<?php 
Class Themecotiservfact {
	public function get_html($contribuyente, $prospecto, $usuario) {
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>   
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
            <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
            <link rel="stylesheet" type="text/css" href="/home/humbertoguadalup/public_html/facturacionv8/public/template_new/global_assets/css/icons/icomoon/styles.css" />
            <link rel="stylesheet" type="text/css" href="/home/humbertoguadalup/public_html/facturacionv8/public/template_new/global_assets/css/icons/fontawesome/styles.min.css" />
        </head>
        
        <style>
        body{
            margin: 0px;
            padding: 0px;
            font-family: arial, sans-serif;
            font-size: 13px;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
        
        p{
            margin: 0px;
            padding: 0px;
        }
        .w-20px{
            width: 20px
        }
        /* ===  Clases generales === */
        .border-none{
            border: none!important;
        }
        .border-bottom-none{
            border-bottom: solid #000 0px!important;
        }
        .font-weight-bold{
            font-weight: 700;
        }
        .text-center{
            text-align: center;
        }
        .text-italic{
            font-style: italic;
        }
        .text-uppercase{
            text-transform: uppercase;
        }
        /* ===  Tabla head === */
        .table-main-head{
            font-size: 14px;
        }
        /* ===  Tabla 1 === */
        .table-1 td, .table-1  th {
        border: 1px solid #000000;
        text-align: left;
        padding: 8px;
        } 
        /* ===  Tabla 2 === */
        .table-2{
            border-top: solid #000 0px;
            border-left: solid #000 1px;
            border-right: solid #000 1px;
            border-bottom: solid #000 1px;
        }
        .table-2 td, .table-2  th {
            border-top: solid #000 0px;
            border-left: solid #000 1px;
            border-right: solid #000 1px;
            border-bottom: solid #000 1px;
            padding: 10px;
        } 
        .table-2-items {
            padding: .3em 3em;
        }
        .price-2{
            font-size: 12px;
        }
        .price-2 p{
            margin-top: 2em;
            padding: 0px !important;
            
        }
        .price-last {
            text-decoration: line-through;
        }
        .price-new{
            font-size: 18px;
        }
        /* == tabla cuentas == */
        .table-cuentas{
            margin-top: 15px;
        }
        .table-cuentas td, .table-cuentas th{
            padding: 7px;
            text-align: center;
        }

        li {
            list-style:none;
        }
        </style>
        <body>
            <table class="table-main-head" style="margin-bottom: 10px; margin-top: 1px !important;">
                <tbody>
                    <tr>
                        <td>
                            <img src="/home/humbertoguadalup/public_html/facturacionv8/public/img/logo_facturalaya_pse_iso_500.png" width="360px">
                        </td>
                        <td>
                            <p><span class="font-weight-bold">Cotización Ref:</span> P00200798</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-1">
                <tbody>
                    <tr>
                        <td>
                            <p class="font-weight-bold">FacturalaYa S.R.L. – <span class="text-uppercase"> RUC: 20604209987</span></p>
                            <p><span class="font-weight-bold">Dirección Fiscal:</span> Jr. Alfonso Ugarte 1115 – Cajamarca</p>
                            <p><span class="font-weight-bold">Oficina Central:</span> 956295282</p>
                            <p><span class="font-weight-bold">Email:</span> facturalaya.srl@gmail.com</p>
                            <p><span class="font-weight-bold">Página Web:</span> https://facturalaya.com</p>
                        </td>
                        <td>
                            <p><span class="font-weight-bold">Contacto:</span> '.ucwords($prospecto->nombre_contacto).'</p>
                            <p><span class="font-weight-bold">Telf.:</span> '.$prospecto->telefonos.'</p>
                            <p><span class="font-weight-bold">Email:</span> '.$prospecto->correos.'</p>
                            <p><span class="font-weight-bold">Empresa:</span> '.$contribuyente->razon_social.'</p>
                            <p><span class="font-weight-bold">RUC:</span> '.$contribuyente->ruc.'</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-1">
                <tbody>
                    <tr>
                        <td>Código</td>
                        <td>DESCRIPCIÓN</td>
                        <td>DETALLE</td>
                    </tr>
                    <tr>
                        <td valign="top">CFSYS</td>
                        <td align="left" valign="middle">
                            <p  class="font-weight-bold text-italic text-uppercase">
								SERVICIO DE FACTURACIÓN ELECTRÓNICA EN LA NUBE DE PAGO MENSUAL 
                            </p>
                            <br />
                            <p  class="font-weight-bold text-italic text-uppercase">INCLUYE:</p> 
                            <ul>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Envío y aprobación de Facturas, Boletas, Notas de Crédito, Notas de Débito y Guías de Remisión Electrónica</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Almacenamiento de documentos electrónicos en nuestros servidores con posibilidad a renovación.</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Comprobantes de pago con Logotipo de la empresa.</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Acceso al Portal Web de Facturación Electrónica para la Emisión de Comprobantes Electrónicos.</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Modificaciones de Ley que la SUNAT haga en los CPE.</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Usuarios Ilimitados</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Sucursales Ilimitadas</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Documentos Ilimitados</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Búsqueda de Ruc y DNI Ilimitado</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Versión UBL 2.1 – Según RS-164-2018-sunat.</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Acceso a Todos los Módulos, Ventas, Compras, Kardex, Guías de Remisión, Reportes, Caja Chica, Almacén, Punto de Venta, Cotizaciones, Notas de Venta, Libro Electrónico de Compras y Ventas, etc.</li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Incluye Certificado Electrónico</li>
                            </ul>
                            <p  class="font-weight-bold text-italic text-uppercase">SOPORTE:  </p>
                            <ul>
                                <li><i class="icon-checkmark4"></i> Soporte Estándar 8x5 L-V 9AM – 6PM</li>
                            </ul>
                            <p  class="font-weight-bold text-italic text-uppercase">IMPORTANTE: </p> 
                            <ul>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Luego de 30 días gratis, el cliente podrá elegir entre los diferentes paquetes de pago mensual detallados en el brochure. (97 soles por mes, 129 soles por mes y 139 soles por mes) </li>
								<li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Bajo ninguna circunstancia el cliente está obligado a renovar su suscripción luego del periodo gratuito.</li>
                                <li><i class="icon-checkmark4"></i> El periodo gratuito ofrecido al cliente es sin ningún tipo de compromiso.</li>                            
                            </ul>
                            <p  class="font-weight-bold text-italic">ACCESO EXCLUSIVO A LA DEMO: <a href="https://arpsystem.com.pe/facturacionv8/login">https://arpsystem.com.pe/facturacionv8/login</a></p> 
                            <ul style="margin-bottom: 0px !important;">
                                <li>Usuario: '.$usuario->email.'  -  Password: '.$usuario->password.'</li>
                            </ul>
                        </td>
                        <td valign="top" class="text-center">
                            <br /><br />
                            <br /><br />
                            <p  class="font-weight-bold text-uppercase">PRECIO NORMAL: <br> <span class="price-last">S/. 197.00</span> </p><br /><br />
                            <p  class="font-weight-bold  text-uppercase">OFERTA POR  TIEMPO LIMITADO:  <br> <span class="price-new"> S/ 0</span></p>
                        </td>
                    </tr>
                    <tr>
                        <td>INST.</td>
						<td align="left" valign="middle">Activación para la Emisión de Comprobante Electrónicos</td>
                        <td align="left" valign="middle" style="text-align:right">S/. 150</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="font-weight-bold" style="text-align:right">Importe Total (Inc. IGV): S/ 150</td>
                    </tr> 
                </tbody>
            </table>
            <p style="color:red; margin-top: 3px; font-style: italic;">OFERTA POR TIEMPO LIMITADO. Mantendremos la oferta hasta el día '.date("d-m-Y", strtotime($prospecto->fecha_expira_oferta)).' o hasta cubrir
            los 3 cupos restantes, luego de esa fecha el costo será de S/. 197 soles mensuales sin ningún periodo gratuito</p>
  
            <div style="page-break-after:always;"></div>
        
            <table class="table-main-head" style="margin-bottom: 10px; margin-top: 1px !important;">
                <tbody>
                    <tr>
                        <td>
                            <img src="/home/humbertoguadalup/public_html/facturacionv8/public/img/logo_facturalaya_pse_iso_500.png" width="360px">
                        </td>
                        <td>
                            <p><span class="font-weight-bold">Cotización Ref:</span> P00200798</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="517" border="1" class="table-cuentas">
                <tr>
                <th width="274" align="center" bgcolor="#1f4e79" style="color:#fff"><p>CUENTAS BANCARIAS  </p>
                <p>Alex Castañeda Aquino Gerente General</p></th>
                <th width="227" align="center" bgcolor="#1f4e79" style="color:#fff">CUENTA AHORRO </th>
                </tr>
                <tr>
                <td>INTERBANK</td>
                <td>702-3-0492-0535-9</td>
                </tr>
                <tr>
                <td>BCP</td>
                <td>245-3766-0793-030</td>
                </tr>
                <tr>
                <td>SCOTIABANK</td>
                <td>707-0045-294</td>
                </tr>
                <tr>
                <td>BBVA</td>
                <td>0011-0277-0200-8097-66</td>
                </tr>
            </table>
            <br />
            <br />
            <p>- Si deseas FACTURA debes hacer el depósito a la cuenta de nuestra empresa, agregando el IGV.<p>
            <table width="517" border="1" class="table-cuentas">
                <tr>
                    <th width="274" align="center" bgcolor="#1f4e79" style="color:#fff"><p>CUENTA BANCARIA – FACTURALAYA SRL  </p>
                    </th>
                    <th width="227" align="center" bgcolor="#1f4e79" style="color:#fff">CUENTA AHORRO </th>
                </tr>
                <tr>
                    <td>BCP</td>
                    <td>245-9603-5269-0-47</td>
                </tr>
                <tr>
                    <td>CCI</td>
                    <td>0022-4519-6035-2690-4797</td>
                </tr>
            </table>
            <p style="margin: 25px 0px;">Somos proveedores autorizados por Sunat (PSE). Puedes verificarlo ingresando al siguiente enlace:
            <a href="http://orientacion.sunat.gob.pe/index.php/empresas-menu/comprobantes-de-pago-empresas/comprobantes-de-pago-electronicos-empresas/see-desde-los-sistemas-del-contribuyente/2-comprobantes-que-se-pueden-emitir-desde-see-sistemas-del-contribuyente/factura-electronica-desde-see-del-contribuyente/3550-padron-de-proveedores-de-servicios-electronicos-pse">http://orientacion.sunat.gob.pe/pse</a> </p>
            <p><img src="/home/humbertoguadalup/public_html/facturacionv8/public/img/pse_padronsunat.png" /></p>
            <p>
            Sitio Web: <a href="https://arpsystem.com.pe/facturacionv8/login">https://arpsystem.com.pe/facturacionv8/login</a>  -  Usuario: '.$usuario->email.'  -  Password: '.$usuario->password.'
            </p>
        </body>';

        return $html;
	}
}
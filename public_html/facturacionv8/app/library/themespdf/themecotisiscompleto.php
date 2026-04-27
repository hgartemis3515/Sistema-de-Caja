<?php 
Class Themecotisiscompleto {
	public function get_html($contribuyente, $prospecto, $usuario) {
        $fecha_expiracion = !empty($prospecto->fecha_expira_oferta) 
                            ? date("d-m-Y", strtotime($prospecto->fecha_expira_oferta)) 
                            : date("d-m-Y", strtotime("+3 days"));
                            
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
                            <p><span class="font-weight-bold">Cotización Ref:</span> COT000'.$contribuyente->id_contribuyente.'</p>
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
                        <td>DESCRIPCIÓN</td>
                        <td>DETALLE</td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle">
                            <p  class="font-weight-bold text-italic text-uppercase">
                                CÓDIGO FUENTE EN PHP DEL SISTEMA MULTIEMPRESA, MULTISUCURSAL, MULTIUSARIO y MULTIALMACÉN PARA BRINDAR EL SERVICIO DE FACTURACIÓN ELECTRÓNICA – V8.0
                            </p>
                            <p  class="font-weight-bold text-italic text-uppercase">INCLUYE:</p> 
                            <ul>
                                <li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Obtendrás un sistema instalado, configurado y personalizado con tu logo y marca.</li>
                                <li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Obtendrás el 100% del código fuente en PHP de la Aplicación de Facturación Electrónica multiemprsa, multiusuario, multisucursal y multialmacén en su versión 8.0.</li>
                                <li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Obtendrás el código fuente en PHP de la ApiRest de facturación electrónica que te permitirá incluso hacer integraciones con otras aplicaciones en otros lenguajes de programación.</li>
                                <li><i class="icon-checkmark4"></i> Podrás solicitar activaciones gratuitas hasta por dos meses totalmente gratis a nuestro servicio PSE. Y todos tus clientes que utilicen dicha activación podrán hacer búsquedas de sus Facturas en SUNAT para el módulo de Compras</li>
                            </ul>
                            <p  class="font-weight-bold text-italic text-uppercase">BONOS:  </p>
                            <ul>
                                <li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Un VPS por un año totalmente gratis, con acceso total vía CPANEL. (Valor Real: S/. 2,300.00)</li>
                                <li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Búsquedas de RUC y DNI totalmente gratis por un año. Lás búsquedas de DNI retornarán el nombre de tu cliente y las búsquedas de RUC para empresas retornará la dirección, estado y ubigeo. (valor Real: S/. 600.00)</li>
                                <li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Servicio de Envío de Correos Electrónicos por un año totalmente Gratis. (valor Real: S/. 600.00)</li>
                                <li><i class="icon-checkmark4"></i> Entrega de documentación de nuestra API para que puedas utilizar nuestro servicio de firmado desde otros sistemas de ventas. (Valor Real: S/. 1,800.00)</li>
                                <li><i class="icon-checkmark4"></i> Entrega del Plugin para la Integración con Tiendas Virtuales en Woocommerce sin costo extra <a target="blank" href="https://youtu.be/04Ux8z8JZrU">(ver ejemplo aquí)</a> (valor Real: S/. 1,200.00)</li>
                                <li><i class="icon-checkmark4"></i> Paquete de 10 Activaciones Gratis por un Año para que puedas utilizarlos con cada uno de tus nuevos clientes (valor Real: S/. 1,500.00)</a></li>
                                <li><i class="icon-checkmark4"></i> Diseño de Landing Page o Página de Venta Personalizada para que Puedas Ofrecer el Servicio de Facturación Electrónica a tus Clientes (valor Real: S/. 1,600.00)</a></li>
                                <li><br /><strong>Solo En Bonos Ganarás más de 9 mil soles!</strong></li>
                            </ul>
                            <p  class="font-weight-bold text-italic text-uppercase">SOPORTE:  </p>
                            <ul>
                                <li><i class="icon-checkmark4"></i> El soporte será por dos meses sobre las  funcionalidades del sistema. Te asignaremos un asesor personal para que puedas consultar con el en caso tengas alguna duda</li>
                            </ul>
                            <p  class="font-weight-bold text-italic text-uppercase">IMPORTANTE: </p> 
                            <ul>
                                <li style="margin-bottom: 5px;"><i class="icon-checkmark4"></i> Queda totalmente prohibido la reventa total o parcial del código fuente, y/o la sesión del código fuente de la API y del sistema multiempresa a terceras personas. En el correo encontrarás información amplicada de todo lo que obtendrás con tu compra!.</li>                            
                            </ul>
                        </td>
                        <td valign="top" class="text-center">
                            <br /><br />
                            <br /><br />
                            <p  class="font-weight-bold text-uppercase">PRECIO NORMAL: <br> <span class="price-last">S/. 6,900.00</span> </p><br /><br />
                            <p  class="font-weight-bold  text-uppercase">OFERTA POR  TIEMPO LIMITADO:  <br /><br /> <span class="price-new"> S/ 2900</span></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle">INSTALACIÓN Y CONFIGURACIÓN</td>
                        <td align="left" valign="middle">S/. 0.0</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="font-weight-bold" style="text-align:right">Importe Total (Sin. IGV): S/ 2900</td>
                    </tr>
                </tbody>
            </table>
            <p style="color:red; margin-top: 3px; font-style: italic;">OFERTA POR TIEMPO LIMITADO. Mantendremos la oferta hasta el día '.$fecha_expiracion.' o hasta cubrir
            los 7 cupos restantes, luego de esa fecha el precio será de S/. 3,900.00 soles + igv y no se entregarán bonos!</p>

            <div style="page-break-after:always;"></div>
        
            <table class="table-main-head" style="margin-bottom: 10px; margin-top: 1px !important;">
                <tbody> 
                    <tr>
                        <td>
                            <img src="/home/humbertoguadalup/public_html/facturacionv8/public/img/logo_facturalaya_pse_iso_500.png" width="360px">
                        </td>
                        <td>
                            <p><span class="font-weight-bold">Cotización Ref:</span> COT000'.$contribuyente->id_contribuyente.'</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>Para emitir la factura debes hacer el depósito a las siguiente cuenta de la Empresa:<p>
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
            <a target="_blank" href="https://orientacion.sunat.gob.pe/3550-padron-de-proveedores-de-servicios-electronicos-pse">http://orientacion.sunat.gob.pe/pse</a> </p>
            <p><img src="/home/humbertoguadalup/public_html/facturacionv8/public/img/pse_padronsunat.png" /></p>
            <h4>Para Ingresar a la Demo Utiliza Los Siguientes Datos:</h4>
            <p>Sitio Web: <a href="https://arpsystem.com.pe/facturacionv8/login">https://arpsystem.com.pe/facturacionv8/login</a>  <br />  Usuario: '.$usuario->email.'  <br />  Password: '.$usuario->password.'</p>
        </body>';

        return $html;
	}
}
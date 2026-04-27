$(function(){
	var php_editor_nota_venta = ace.edit("php_editor_nota_venta");
    php_editor_nota_venta.setTheme("ace/theme/monokai");
    php_editor_nota_venta.getSession().setMode("ace/mode/php");
    php_editor_nota_venta.setShowPrintMargin(false);

	var json_editor_nota_venta = ace.edit("json_editor_nota_venta");
    json_editor_nota_venta.setTheme("ace/theme/monokai");
    json_editor_nota_venta.getSession().setMode("ace/mode/json");
    json_editor_nota_venta.setShowPrintMargin(false);

	agregar_codigo_ejemplo_php_nota_venta(php_editor_nota_venta);
	agregar_codigo_ejemplo_json_nota_venta(json_editor_nota_venta)
});

function agregar_codigo_ejemplo_php_nota_venta(php_editor) {
    php_editor.setValue(
    `
    <?php
        $data["contribuyente"] = array(
            "token_contribuyente"           => "` + $("#token_contribuyente").val() + `", //Token del contribuyente
            "id_usuario_vendedor"           => 1, //Debes ingresar el ID de uno de tus vendedores (opcional)
            "tipo_proceso"                  => "prueba", //Funcional en una siguiente versión. El ambiente al que se enviará, puede ser: {prueba, produccion}
            "tipo_envio"                    => "inmediato" //funcional en una siguiente versión. Aquí puedes definir si se enviará de inmediato a sunat
        );
        
        $data["cliente"] = array(
            "tipo_docidentidad"             => 6, //{0: SINDOC, 1: DNI, 6: RUC}
            "numerodocumento"               => "20604209987", //Es opcional solo cuando tipo_docidentidad es 0, caso contrario se debe ingresar el número de ruc
            "nombre"                        => "Razón social de tu cliente", //Es opcional solo cuando tipo_docidentidad es 1, caso contrario es obligatorio ingresar aquí la razón social
            "email"                         => "email_cliente@gmail.com", //opcional: (si tiene correo se enviará automáticamente el email)
            "direccion"                     => "", //opcional: 
            "ubigeo"	                    => "",
            "sexo"                          => "", //opcional: masculino
            "fecha_nac"                     => "", //opcional: 
            "celular"                       => "" //opcional
        );
        
        $data["cabecera_comprobante"] = array(
            "tipo_documento"                => "77",  //{"77": NOTA DE VENTA}
            "moneda"                        => "PEN",  //{"USD", "PEN"}
            "idsucursal"                    => 1,  //{ID DE SUCURSAL}
            "id_condicionpago"              => "",  //condicionpago_comprobante
            "fecha_comprobante"             => "10/05/2020",  //fecha_comprobante
            "nro_placa"                     => "",  //nro_placa_vehiculo
            "nro_orden"                     => "",  //nro_orden
            "guia_remision"                 => "",  //guia_remision_manual
            "descuento_monto"               => 0,  // (máximo 2 decimales) (monto total del descuento)
            "descuento_porcentaje"          => 0,  // (máximo 2 decimales) (porcentaje total del descuento)
            "observacion"                   => "",  //observacion_documento
        );
        
        $detalle[] = array(
            "idproducto"                    => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	                    => "TV_CODIGOPROD", //codigo del producto (requerido)
            "afecto_icbper"                 => "no",  //"afecto_icbper":"no",
            "id_tipoafectacionigv"          => 10,  //"id_tipoafectacionigv":"10",
            "descripcion"                   => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida"                => 'NIU',  //{NIU para unidades, ZZ para servicio}
            "precio_venta"                  => 5,  //Precio unitario de venta (inc. IGV),
            "cantidad"                      => 111,  //"cantidad":"1"
        );
        
        $detalle[] = array(
            "idproducto"                    => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	                    => "TV_CODIGOPROD2", //codigo del producto (requerido)
            "afecto_icbper"                 => "no",  //"afecto_icbper":"no",
            "id_tipoafectacionigv"          => 20,  //"id_tipoafectacionigv":"10",
            "descripcion"                   => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida"                => 'NIU',  //{NIU para unidades, ZZ para servicio}
            "precio_venta"                  => 78.56,  //Precio unitario de venta (inc. IGV),
            "cantidad"                      => 2.9,  //"cantidad":"1"
        );
        
        $data["detalle"] = $detalle;
        
        $ruta = "https://facturalaya.com/facturacionv8/api/procesar_nota_venta";
        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer ` + $("#token_contribuyente").val() + `",
                "Content-Type: application/json",
                "cache-control: no-cache"
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
            $resp["respuesta"] = "error";
            $resp["titulo"] = "Error";
            $resp["data"] = "";
            $resp["encontrado"] = false;
            $resp["mensaje"] = "Error en Api de Búsqueda";
            $resp["errores_curl"] = $error_msg;
            echo json_encode($resp);
            exit();
        }
        
        echo $respuesta;
        exit();
    ?>
    `
    );
}

function agregar_codigo_ejemplo_json_nota_venta(json_editor) {
    json_editor.setValue(
    `
    {
        "contribuyente":{
           "token_contribuyente":"` + $("#token_contribuyente").val() + `",
           "id_usuario_vendedor":1,
           "tipo_proceso":"prueba",
           "tipo_envio":"inmediato"
        },
        "cliente":{
           "tipo_docidentidad":6,
           "numerodocumento":"20604209987",
           "nombre":"Raz\u00f3n social de tu cliente",
           "email":"email_cliente@gmail.com",
           "direccion":"",
           "ubigeo":"",
           "sexo":"",
           "fecha_nac":"",
           "celular":""
        },
        "cabecera_comprobante":{
           "tipo_documento":"77",
           "moneda":"PEN",
           "idsucursal":1,
           "id_condicionpago":"",
           "fecha_comprobante":"10\/05\/2020",
           "nro_placa":"",
           "nro_orden":"",
           "guia_remision":"",
           "descuento_monto":0,
           "descuento_porcentaje":0,
           "observacion":""
        },
        "detalle":[
           {
              "idproducto":0,
              "codigo":"TV_CODIGOPROD",
              "afecto_icbper":"no",
              "id_tipoafectacionigv":10,
              "descripcion":"Producto de Ejemplo",
              "idunidadmedida":"NIU",
              "precio_venta":5,
              "cantidad":111
           },
           {
              "idproducto":0,
              "codigo":"TV_CODIGOPROD2",
              "afecto_icbper":"no",
              "id_tipoafectacionigv":20,
              "descripcion":"Producto de Ejemplo",
              "idunidadmedida":"NIU",
              "precio_venta":78.56,
              "cantidad":2.9
           }
        ]
     }
    `
    );
}
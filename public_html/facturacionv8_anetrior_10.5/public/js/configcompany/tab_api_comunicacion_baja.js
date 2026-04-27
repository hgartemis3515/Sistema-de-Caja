$(function(){
	var php_editor_comunicacion_baja = ace.edit("php_editor_comunicacion_baja");
    php_editor_comunicacion_baja.setTheme("ace/theme/monokai");
    php_editor_comunicacion_baja.getSession().setMode("ace/mode/php");
    php_editor_comunicacion_baja.setShowPrintMargin(false);

	var json_editor_comunicacion_baja = ace.edit("json_editor_comunicacion_baja");
    json_editor_comunicacion_baja.setTheme("ace/theme/monokai");
    json_editor_comunicacion_baja.getSession().setMode("ace/mode/json");
    json_editor_comunicacion_baja.setShowPrintMargin(false);

	agregar_codigo_ejemplo_php_comunicacion_baja(php_editor_comunicacion_baja);
	agregar_codigo_ejemplo_json_comunicacion_baja(json_editor_comunicacion_baja)
});

function agregar_codigo_ejemplo_php_comunicacion_baja(php_editor) {
    php_editor.setValue(
    `
    <?php
        $data["contribuyente"] = array(
            "token_contribuyente"           => "` + $("#token_contribuyente").val() + `", //Token del contribuyente
			"id_usuario_vendedor"           => 1, //Debes ingresar el ID de uno de tus vendedores (opcional)
            "tipo_proceso"                  => "prueba" //Funcional en una siguiente versión. El ambiente al que se enviará, puede ser: {prueba, produccion}
        );
		
        $data["cabecera_comprobante"] = array(
            "tipo_documento"                => "03",  //{"01": FACTURA, "03": BOLETA, "07": NOTA DE CRÉDITO, "08": NOTA DE DÉBITO, "77": NOTA DE CRÉDITO, "88": COTIZACIÓN}
            "serie_comprobante"             => "B001",  //Serie del Comprobante
            "numero_comprobante"            => 1,  //número del comprobante (debe ser un número entero)
			"motivo_anulacion"				=> "Error en creación de comprobante" //descripción del motivo de anulación
        );
        
        $ruta = "` + $("#url_comunicacion_baja").val() + `";
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

function agregar_codigo_ejemplo_json_comunicacion_baja(json_editor) {
    json_editor.setValue(
    `
    {
        "contribuyente":{
           "token_contribuyente":"` + $("#token_contribuyente").val() + `",
		   "id_usuario_vendedor":1,
           "tipo_proceso":"prueba"
        },
        "cabecera_comprobante":{
           "tipo_documento":"03",
           "serie_comprobante":"PEN",
           "numero_comprobante":1,
		   "motivo_anulacion":"Error en documento"
        }
    }
    `
    );
}
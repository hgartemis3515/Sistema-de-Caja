$(function(){
	var php_editor_guia_remision = ace.edit("php_editor_guia_remision");
    php_editor_guia_remision.setTheme("ace/theme/monokai");
    php_editor_guia_remision.getSession().setMode("ace/mode/php");
    php_editor_guia_remision.setShowPrintMargin(false);

	var json_editor_guia_remision = ace.edit("json_editor_guia_remision");
    json_editor_guia_remision.setTheme("ace/theme/monokai");
    json_editor_guia_remision.getSession().setMode("ace/mode/json");
    json_editor_guia_remision.setShowPrintMargin(false);

	agregar_codigo_ejemplo_php_guia_remision(php_editor_guia_remision);
	agregar_codigo_ejemplo_json_guia_remision(json_editor_guia_remision)
});

function agregar_codigo_ejemplo_php_guia_remision(php_editor) {
    php_editor.setValue(
    `
    <?php
        $data["contribuyente"] = array(
            "token_contribuyente"           => "` + $("#token_contribuyente").val() + `", //Token del contribuyente
            "id_usuario_vendedor"           => 1, //Debes ingresar el ID de uno de tus vendedores (opcional)
            "tipo_proceso"                  => "prueba", //Funcional en una siguiente versión. El ambiente al que se enviará, puede ser: {prueba, produccion}
            "tipo_envio"                    => "inmediato" //funcional en una siguiente versión. Aquí puedes definir si se enviará de inmediato a sunat
        );

        $data["cabecera_comprobante"] = array(
            "tipo_documento"                => "09",  //{"01": GUIA REMISIÓN}
            "idsucursal"                    => 1,  //{ID DE SUCURSAL}
            "fecha_comprobante"             => "10/05/2020",  //fecha_comprobante
            "motivo_traslado"               => "01",  //Catálogo No. 20: Códigos – Motivos de traslado
            "modalidad_traslado"            => "02",  //{01: Transporte Público, 02: Transporte Privado} Catálogo No. 18: Códigos – Modalidad de traslado
            "fecha_traslado"                => "10/05/2020",  //Fecha Inicio de Traslado
            "otro_motivo_traslado"          => "",  //Describir el motivo de traslado en caso se haya seleccionado OTRO en motivo traslado
            "pesobruto"                     => 50,  //Peso Total en KGM
            "numero_bultos"                 => 2,  //Número Total de Bultos
            "numero_contenedor"             => "",  //Indicar el número de contenedor en caso sea importación, caso contrario dejar vacio
            "codigo_puerto"                 => "",  //Indicar Código de Puerto en caso sea importación, caso contrario dejar vacio
            "tipo_doc_referencia"           => "",  //Si la guía está relacionada a una factura o boleta, indicar el tipo de documento {01: factura, 03: boleta}
            "serie_doc_referencia"          => "",  //Serie del documento de referencia
            "numero_doc_referencia"         => "",  //Número del Documento de Referencia
            "informacion_adicional_sunat"   => "",  //Observación, este texto será informado a SUNAT
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

        $data["transporte"] = array(
            "tipo_docidentidad"	            => "1", //{Si la modalidad de transporte es privado (02) entonces el tipo de documento debe ser DNI = 1, caso contrario debe ser RUC: 6}
            "numerodocumento"	            => "44359644", //{Si la modalidad de transporte es privado (02) entonces aquí el número de dni del conductor, caso contrario el RUC de la empresa de transporte}
            "nombre"	                    => "Alex Roel Castaneda Aquino", //{Si la modalidad de transporte es privado (02) entonces aquí el nombre del conductor, caso contrario la razón social de la empresa de transporte}
            "num_placa"	                    => "ARG-78" //{Llenar únicamente si el transporte es privado}
        );

        $data["direccion_partida"] = array(
            "direccion"                     => "Jr. Nombre Jiron Partida 9087, Cajamarca, Perú", //Dirección de partida máximo 100 caracteres
            "ubigeo"                        => "060101" //ubigeo
        );

        $data["direccion_llegada"] = array(
            "direccion"                     => "Jr. Nombre Jiron Llegada 9087, Cajamarca, Perú", //Dirección de llegada máximo 100 caracteres
            "ubigeo"                        => "060105" //ubigeo
        );
        
        $detalle[] = array(
            "idproducto"                    => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	                    => "TV_CODIGOPROD2", //codigo del producto (requerido)
            "descripcion"                   => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida"                => "NIU",  //{NIU para unidades, ZZ para servicio}
            "peso"                          => 78.56,  //Precio unitario de venta (inc. IGV),
            "cantidad"                      => 2.9,  //"cantidad":"1"
        );
        
        $detalle[] = array(
            "idproducto"                    => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	                    => "TV_CODIGOPROD2", //codigo del producto (requerido)
            "descripcion"                   => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida"                => "NIU",  //{NIU para unidades, ZZ para servicio}
            "peso"                          => 78.56,  //Precio unitario de venta (inc. IGV),
            "cantidad"                      => 2.9,  //"cantidad":"1"
        );
        
        $data["detalle"] = $detalle;
        
        $ruta = "https://facturalaya.com/facturacionv8/api/procesar_guia_remision";
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

function agregar_codigo_ejemplo_json_guia_remision(json_editor) {
    json_editor.setValue(
    `
    {
        "contribuyente":{
            "token_contribuyente":"` + $("#token_contribuyente").val() + `",
            "id_usuario_vendedor":1,
            "tipo_proceso":"prueba",
            "tipo_envio":"inmediato"
        },
        "cabecera_comprobante":{
            "tipo_documento":"09",
            "idsucursal":1,
            "fecha_comprobante":"10\/05\/2020",
            "motivo_traslado":"01",
            "modalidad_traslado":"02",
            "fecha_traslado":"10\/05\/2020",
            "otro_motivo_traslado":"",
            "pesobruto":50,
            "numero_bultos":2,
            "numero_contenedor":"",
            "codigo_puerto":"",
            "tipo_doc_referencia":"",
            "serie_doc_referencia":"",
            "numero_doc_referencia":"",
            "informacion_adicional_sunat":""
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
        "transporte":{
            "tipo_docidentidad":"1",
            "numerodocumento":"44359644",
            "nombre":"Alex Roel Castaneda Aquino",
            "num_placa":"ARG-78"
        },
        "direccion_partida":{
            "direccion":"Jr. Nombre Jiron Partida 9087, Cajamarca, Per\u00fa",
            "ubigeo":"060101"
        },
        "direccion_llegada":{
            "direccion":"Jr. Nombre Jiron Llegada 9087, Cajamarca, Per\u00fa",
            "ubigeo":"060105"
        },
        "detalle":[
            {
                "idproducto":0,
                "codigo":"TV_CODIGOPROD2",
                "descripcion":"Producto de Ejemplo",
                "idunidadmedida":"NIU",
                "peso":78.56,
                "cantidad":2.9
            },
            {
                "idproducto":0,
                "codigo":"TV_CODIGOPROD2",
                "descripcion":"Producto de Ejemplo",
                "idunidadmedida":"NIU",
                "peso":78.56,
                "cantidad":2.9
            }
        ]
    }
    `
    );
}
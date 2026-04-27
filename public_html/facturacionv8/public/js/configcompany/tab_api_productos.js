$(function(){
	var php_editor_get_productos = ace.edit("php_editor_get_productos");
    php_editor_get_productos.setTheme("ace/theme/monokai");
    php_editor_get_productos.getSession().setMode("ace/mode/php");
    php_editor_get_productos.setShowPrintMargin(false);

	var json_editor_response_get_productos = ace.edit("json_editor_response_get_productos");
    json_editor_response_get_productos.setTheme("ace/theme/monokai");
    json_editor_response_get_productos.getSession().setMode("ace/mode/json");
    json_editor_response_get_productos.setShowPrintMargin(false);

	var php_editor_get_producto = ace.edit("php_editor_get_producto");
    php_editor_get_producto.setTheme("ace/theme/monokai");
    php_editor_get_producto.getSession().setMode("ace/mode/php");
    php_editor_get_producto.setShowPrintMargin(false);

	var json_editor_response_get_producto = ace.edit("json_editor_response_get_producto");
    json_editor_response_get_producto.setTheme("ace/theme/monokai");
    json_editor_response_get_producto.getSession().setMode("ace/mode/json");
    json_editor_response_get_producto.setShowPrintMargin(false);

	var php_editor_get_num_productos = ace.edit("php_editor_get_num_productos");
    php_editor_get_num_productos.setTheme("ace/theme/monokai");
    php_editor_get_num_productos.getSession().setMode("ace/mode/php");
    php_editor_get_num_productos.setShowPrintMargin(false);

	var json_editor_response_get_num_productos = ace.edit("json_editor_response_get_num_productos");
    json_editor_response_get_num_productos.setTheme("ace/theme/monokai");
    json_editor_response_get_num_productos.getSession().setMode("ace/mode/json");
    json_editor_response_get_num_productos.setShowPrintMargin(false);

	agregar_codigo_ejemplo_php_get_productos(php_editor_get_productos);
	agregar_codigo_ejemplo_json_response_get_productos(json_editor_response_get_productos)

	agregar_codigo_ejemplo_php_get_producto(php_editor_get_producto);
	agregar_codigo_ejemplo_json_response_get_producto(json_editor_response_get_producto)

	agregar_codigo_ejemplo_php_get_num_productos(php_editor_get_num_productos);
	agregar_codigo_ejemplo_json_response_get_num_productos(json_editor_response_get_num_productos)
});

function agregar_codigo_ejemplo_php_get_productos(php_editor) {
    php_editor.setValue(
    `
	<?php
		$data = array(
			"limite_inferior" => 1,
			"limite_superior" => 50,
		);

		$ruta = "https://facturalaya.com/facturacionv8/api/get_productos";
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

function agregar_codigo_ejemplo_json_response_get_productos(json_editor) {
    json_editor.setValue(
    `
	[
		{
		   "id":"228455",
		   "sku":"LE7H6Z8",
		   "ean":"",
		   "titulo":"Producto prueba",
		   "shortDescription":"",
		   "longDescription":"",
		   "price":{
			  "currency":"PEN",
			  "listPrice":"11.8000",
			  "sellPrice":"11.8000"
		   },
		   "stock":-2,
		   "images":[
			  
		   ],
		   "images_details":{
			  "cuadradas":[
				 
			  ],
			  "verticales":[
				 
			  ],
			  "horizontales":[
				 
			  ]
		   },
		   "tags":"",
		   "brand":[
			  
		   ],
		   "category":[
			  
		   ],
		   "video":"",
		   "status":"inactive",
		   "delivery":[
			  
		   ],
		   "variations":[
			  
		   ]
		},
		{
		   "id":"242362",
		   "sku":"A1001",
		   "ean":"",
		   "titulo":"Adaptador de corriente Godox AD-AC para AD600BM",
		   "shortDescription":"",
		   "longDescription":"",
		   "price":{
			  "currency":"PEN",
			  "listPrice":"399.0000",
			  "sellPrice":"399.0000"
		   },
		   "stock":3,
		   "images":[
			  
		   ],
		   "images_details":[
			  
		   ],
		   "tags":"",
		   "brand":[
			  
		   ],
		   "category":[
			  
		   ],
		   "video":"",
		   "status":"active",
		   "delivery":[
			  
		   ],
		   "variations":[
			  
		   ]
		},
		{...},
		{...}
	]
    `
    );
}

function agregar_codigo_ejemplo_php_get_producto(php_editor) {
    php_editor.setValue(
    `
	<?php
		$data = array(
			"token_contribuyente" => "` + $("#token_contribuyente").val() + `", //Token del contribuyente
			"id" => 228455, //
		);
		
		$ruta = "https://facturalaya.com/facturacionv8/api/get_producto";
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

function agregar_codigo_ejemplo_json_response_get_producto(json_editor) {
    json_editor.setValue(
    `
	{
		"id":"228455",
		"sku":"LE7H6Z8",
		"ean":"",
		"titulo":"Producto prueba",
		"shortDescription":"",
		"longDescription":"",
		"price":{
		   "currency":"PEN",
		   "listPrice":"11.8000",
		   "sellPrice":"11.8000"
		},
		"stock":-2,
		"images":[
		   
		],
		"images_details":{
		   "cuadradas":[
			  
		   ],
		   "verticales":[
			  
		   ],
		   "horizontales":[
			  
		   ]
		},
		"tags":"",
		"brand":[
		   
		],
		"category":[
		   
		],
		"video":"",
		"status":"inactive",
		"delivery":[
		   
		],
		"variations":[
		   
		]
	}
    `
    );
}

function agregar_codigo_ejemplo_php_get_num_productos(php_editor) {
    php_editor.setValue(
    `
	<?php
		$data = array(
			"token_contribuyente" => "` + $("#token_contribuyente").val() + `", //Token del contribuyente
		);
		
		$ruta = "https://facturalaya.com/facturacionv8/api/get_num_productos";
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

function agregar_codigo_ejemplo_json_response_get_num_productos(json_editor) {
    json_editor.setValue(
    `
	{
		"respuesta":"ok",
		"total":148
	}
    `
    );
}
$(function() {
    generar_codigo_barras();
});

function generar_codigo_barras() {
    //Convert to boolean
    $("#barcode1").JsBarcode(
        $("#codigo_producto").val(), 
        {
          "format": 'CODE128',
          "background": '#FFFFFF',
          "lineColor": '#000000',
          "fontSize": 15,
          "height": 30,
          "width": 1.5,
          "margin": 5.5,
          "textMargin": 0,
          "displayValue": true,
          "font": 'monospace',
          "fontOptions": 'bold',
          "textAlign": 'center',
          "valid":
            function(valid){
              if(valid){
                console.log('CODIGO VÁLIDO');
              } else{
                console.log('CODIGO INVÁLIDO');
              }
            }
        }
	);
	
	$("#barcode2").JsBarcode(
      $("#codigo_producto").val(),
        {
          "format": 'CODE128',
          "background": '#FFFFFF',
          "lineColor": '#000000',
          "fontSize": 15,
          "height": 30,
          "width": 1.4,
          "margin": 5.5,
          "textMargin": 0,
          "displayValue": true,
          "font": 'monospace',
          "fontOptions": 'bold',
          "textAlign": 'center',
          "valid":
            function(valid){
              if(valid){
                console.log('CODIGO VÁLIDO');
              } else{
                console.log('CODIGO INVÁLIDO');
              }
            }
        }
	);
	
	$("#barcode3").JsBarcode(
      $("#codigo_producto").val(),
      {
        "format": 'CODE128',
        "background": '#FFFFFF',
        "lineColor": '#000000',
        "fontSize": 15,
        "height": 30,
        "width": 1.5,
        "margin": 5.5,
        "textMargin": 0,
        "displayValue": true,
        "font": 'monospace',
        "fontOptions": 'bold',
        "textAlign": 'center',
        "valid":
          function(valid){
            if(valid){
              console.log('CODIGO VÁLIDO');
            } else{
              console.log('CODIGO INVÁLIDO');
            }
          }
      }
  );

}  
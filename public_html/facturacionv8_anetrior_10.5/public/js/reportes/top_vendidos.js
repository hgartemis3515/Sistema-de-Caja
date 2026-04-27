$(function() {
  
	$(".select").select2();
	$(".select_minimizado").select2({
        minimumResultsForSearch: -1
	});
	$('.multiselect').multiselect();
	$(".control_fecha").daterangepicker({singleDatePicker: true, locale: { format: 'DD/MM/YYYY' }});
	$(".btn_generar_reporte_detallado").click(reporte_detallado);
});
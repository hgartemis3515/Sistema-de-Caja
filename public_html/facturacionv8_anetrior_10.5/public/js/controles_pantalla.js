$(function(){

    $('.select2').select2({
        minimumResultsForSearch: -1
	});
    //User dropwdown
    $(".dropwdown-wrap-pt").on("click", function(event) {
        event.stopPropagation(); // Evita que el clic se propague

        // Oculta todos los dropdowns
        $(".dropdown-menu-pt").removeClass("active");

        // Muestra solo el dropdown correspondiente
        $(this).find(".dropdown-menu-pt").addClass("active");
    });

    // Al hacer clic en cualquier parte fuera de los dropdowns
    $(document).on("click", function() {
        $(".dropdown-menu-pt").removeClass("active"); // Oculta todos los dropdowns
    });

    // tooltips
    $(".tooltip-pt").on("mouseenter", function() {
        $(this).find(".box-tooltip-pt").addClass("active");
    });

    $(".tooltip-pt, .box-tooltip-pt").on("mouseleave", function() {
        $(this).find(".box-tooltip-pt").removeClass("active"); 
    });
    //Sidebar navegation
  
    $(".menu-sidebar-left").on("click", function(event) {
        event.stopPropagation(); 
        $(".sidebar-menu-pt").addClass("open");
    });
    $(document).on("click", function() {
        event.stopPropagation(); 
        $(".sidebar-menu-pt, .sidebar-header-button").removeClass("open"); // Oculta todos los dropdowns
    });

    $(document).ready(function() {
        $('.content-drop-main').on('click', function(event) {
            event.stopPropagation();
    
            const parentTabItem = $(this).closest('.tab_item_pt'); 
            const dropTop = parentTabItem.find('.droptop-pt'); 
    
          
            $('.droptop-pt').not(dropTop).hide();
    
           
            const offset = parentTabItem.offset();
            const dropTopHeight = dropTop.outerHeight();
    
            
            dropTop.css({
                top: offset.top - dropTopHeight - 5, 
                left: offset.left,
                display: 'block' 
            });
        });
    
        
        $(document).on('click', function() {
            $('.droptop-pt').hide();
        });
    });
    $(".btn_config_venta").off("click").on("click", function(event) {

        event.stopPropagation();
        $(".vm_config_venta").modal("show");
    });
    $(".btn_sincro_pt").on("click", function(event) { 
        $(".vm_config_post").modal("show");
    });
    $(".btn_config_pt_hd").on("click", function(event) { 
        $(".vm_config_punto_venta").modal("show");
    });
    
    
    
    
});
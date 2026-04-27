{{ partial('systempos/css') }}


<input type="hidden" id="id_usuario" value="<?php echo $idusuario; ?>">
<input type="hidden" id="id_contribuyente" value="<?php echo $id_contribuyente; ?>">
<input type="hidden" id="factor_igv_sunat" value="0.18">
<input type="hidden" id="num_decimales" value="<?php echo $num_decimales; ?>">
<input type="hidden" id="data_tipo_cambio" data-venta="<?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>" data-compra="<?php echo $data_tipo_cambio['tipo_cambio_compra']; ?>" data-fechaconsulta="<?php echo $data_tipo_cambio['fecha_consulta']; ?>" value="<?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>">


<div class="container-wrapper">
    
    {{ partial('systempos/custom_nav_bar') }}

    <div class="main-body">
        <div class="content-wrap-pt">
            <div class="content-products">
                <div class="search-area-products">
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <label><i class="icon-cart-add position-left"></i>Aquí puedes buscar y seleccionar tu producto/Servicio!</label>
                            <div class="input-group">
                                <span class="input-group-btn sr-group">
                                    <button class="btn bg-indigo legitRipple" type="button">
                                        <i class="icon-search4"></i>
                                    </button>
                                    <span  class="btn btn-default legitRipple btn-icon-bar"><i class="icon-barcode2"></i></span>
                                </span>
                                <input type="text" name="txt_busqueda_productos" id="txt_busqueda_productos" class="form-control" placeholder="Buscar productos">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vm_agregar_articulo">
                                        <i class="icon-plus-circle2 mr-2"></i> <span class="nuevo-producto">Nuevo</span>
                                        </button>
                                    
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-list-product d-flex">
                    <div class="item-product-details selected-item">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media-B6TdITzb.png" alt="">
                        </div>
                        <div class="item-details-name d-flex d-flex-column">
                            <p class="item-price">S/9.564,46</p>
                            <p class="item-name">10544Sist. de aire acondicionado montaje vert. en panel o puerta de tablero - 640w35347 LEGRAND</p>
                            <p class="select-product-item">1 seleccionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite active"><i class="icon-star-full2"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media4-DcNzu6e7.png" alt="">
                        </div>
                        <div class="item-details-name d-flex d-flex-column">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                  
                    
                    <div class="item-product-details selected-item">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media4-DcNzu6e7.png" alt="">
                        </div>
                        <div class="item-details-name d-flex d-flex-column">
                            <p class="item-price">S/9.564,46</p>
                            <p class="item-name">10544Sist. Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
                            <p class="select-product-item">10 seleccionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media4-DcNzu6e7.png" alt="">
                        </div>
                        <div class="item-details-name">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite active"><i class="icon-star-full2"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media-B6TdITzb.png" alt="">
                        </div>
                        <div class="item-details-name">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media-B6TdITzb.png" alt="">
                        </div>
                        <div class="item-details-name">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-payment">
                <div class="header-sidebar">
                    <div class="top-sidebar">
                        <div class="invoice_title">
                            <p>Ventas</p>
                        </div>
                        <div class="invoice_options d-flex">
                            <div class="form-group input-select2">
                               
                                <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="cliente_tipo_docidentidad select2" name="cliente_tipo_docidentidad" id="cliente_tipo_docidentidad">
                                    <option value="0">Sin.Doc.</option>
                                    <option value="1">DNI</option>
                                    <option value="6">RUC</option>
                                    <option value="4">CarnetExtranj.</option>
                                </select>
                            </div>
                            <div class="options-buy">
                                <ul class="icons-list text-right">
                                    <li class="dropdown">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">   
                                            <i class="icon-paragraph-justify3"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a onclick=""  href="javascript:void(0)">Moneda</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="data-cliente">
                        <div class="form-group input-select2">
                            <label  class="label-form">
                                <i class="icon-user mr-2"></i> 
                                Tipo de Doc 
                            </label>
                            <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="cliente_tipo_docidentidad select2" name="cliente_tipo_docidentidad" id="cliente_tipo_docidentidad">
                                <option value="0">Sin.Doc.</option>
                                <option value="1">DNI</option>
                                <option value="6">RUC</option>
                                <option value="4">CarnetExtranj.</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label  class="label-form">
                                <i class="icon-user mr-2"></i>
                                <span class="type_id" id="type_id">Doc. de Identidad</span> 
                            
                            </label>
                            <div class="input-group">
                                <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cliente_numerodocumento" id="cliente_numerodocumento" placeholder="Número de documento Aquí!" class="form-control cliente_numerodocumento pt_type_id" required>
                                <span class="input-group-btn">
                                    <button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
                                        <i class="icon-search4" id="icon_search_document"></i>
                                        <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group d-flex box-name-client">
                            <div class="input-name-client label-form">
                                <i class="icon-users2 mr-2"></i> 
                                Nombre
                                <input type="text" class="form-control form-control-sm" name="cliente_nombre" id="cliente_nombre" placeholder="Nombre"> 
                            </div>
                            <div class="btn-add-client pb-2">
                                <button class="btn bg-indigo dropdown-toggle legitRipple">Nuevo </button>
                            </div>
                           
                        </div>
                        <div class="produc-box-movil mb-2  mt-2 d-flex d-flex-column w-100">
                            <label><i class="icon-cart-add position-left"></i>Busca tu producto/Servicio</label>
                            <div class="input-group">
                                <span class="input-group-btn sr-group">
                                    <button class="btn bg-indigo legitRipple" type="button">
                                        <i class="icon-search4"></i>
                                    </button>
                                    <span  class="btn btn-default legitRipple btn-icon-bar"><i class="icon-barcode2"></i></span>
                                </span>
                                <input type="text" name="txt_busqueda_productos" id="txt_busqueda_productos" class="form-control" placeholder="Buscar productos">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vm_agregar_articulo">
                                        <i class="icon-plus-circle2 mr-2"></i> <span class="nuevo-producto">Nuevo</span>
                                    </button>
                                
                                </span>
                            </div>
                        </div>
    
                    </div>
                </div>
               
                <div class="carr-list-product">
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="footer-sidebar">
                    <div class="invoice-payment-total">
                        <button class="btn bg-indigo d-block w-100"> 
                            <span>Vender</span>
                            <span>S/ 4,00.00</span>
                        </button>
                    </div>
                    <div class="details-invoice-small">
                        <div>3 Productos</div>
                        <div>Cancelar</div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

   <div class="footer-wrapper">
        <div class="tab_item_pt active d-flex justify-content-between">
            <span><i class="icon-cart"></i> Venta 1</span>
            <div class="dropwtop-wrap-pt drop-top">
                <div class="title-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
            </ul>
        </div>
        <div class="tab_item_pt  d-flex justify-content-between">
            <i class="icon-cart"></i> Venta 2
            <div class="dropwtop-wrap-pt drop-top">
                <div class="title-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
            </ul>
        </div>
        <div class="tab_item_pt  d-flex justify-content-between">
            <i class="icon-cart"></i> Venta 3
            <div class="dropwtop-wrap-pt drop-top">
                <div class="title-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
            </ul>
        </div>
        <div class="tab_item_pt  d-flex justify-content-between">
            <i class="icon-cart"></i> Venta 4
            <div class="dropwtop-wrap-pt drop-top">
                <div class="title-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
            </ul>
        </div>
        <div class="tab_item_pt add-shop-tab">
            <button>+</button>
        </div>
    </div>

</div>

<div class="sidebar-menu-pt">
    <div class="navigation-menu">
        <div class="navigation-menu-header">
            <a href="javascript:void(0)" class="sidebar-header-button"><i class="ph-list"></i></a>
            <h6>Punto de Venta</h6>
        </div>
        <div class="navigation-menu-body">
            <ul>
                <li><a href="#"><i class="ph-house"></i>Vender</a></li>
                <li><a href="#"><i class="ph-house"></i>Ingresos</a></li>
                <li><a href="#"><i class="ph-house"></i>Gestion en efectivo</a></li>
                <li><a href="#"><i class="ph-house"></i>Contactos</a></li>
                <li><a href="#"><i class="ph-house"></i>Inventario</a></li>
                <li><a href="#"><i class="ph-house"></i>Configuraciones</a></li>
                <li><a href="#">Mi suscripción</a></li>
                <li><a href="#">Portal de clientes</a></li>
                <li><a href="#">Atajos del teclado</a></li>
            </ul>
        </div>
        <div class="navigation-menu-footer">
            <div class="d-flex d-flex-column">
                <p>Última sincronización</p>
                <small xlass="color-tertiary">05/11/2024 8:34 pm</small>
            </div>
          
            <div class="icon-footer-sidebar">
                <a href="#"><i class="icon-loop3"></i></a>
            </div>
        </div>
    </div>
</div>

{{ partial('systempos/modal_aviso_sincronizacion') }}
{{ partial('systempos/modal_config_pos') }}
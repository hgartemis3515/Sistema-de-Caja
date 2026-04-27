<body class="pace-done">
    <!-- Page container -->
    <div class="page-container" id="contenidoeditpage">
        <!-- Page content -->
        <div class="page-content">            
            <!-- Main sidebar -->
            <div class="sidebar sidebar-main" id="editpage_controlesedicion">
                <div class="sidebar-content">                    
                    <div class="editor-texto">
                        <!-- Navbar placement -->                                                                                    
                        <div class="navbar navbar-inverse">
                            <ul class="nav navbar-nav">
                                <li>
                                    <a href="javascript:void(0)" class="btn_ocultar_controles">
                                        <i class="fa fa-caret-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="editor-texto-opciones">
                            <div class="editor-tabbable">
                                <ul class="nav-tabs editor-tabsdos">
                                    <li class="active"><p href="#centered-tab3" data-toggle="tab"><i class="fa fa-sliders" aria-hidden="true"></i></p></li>
                                     <li><p href="#centered-tab4" data-toggle="tab"><i class="fa fa-object-ungroup" aria-hidden="true"></i></p></li>
                                </ul>                                                                        
                                <div class="tab-content editor-tabcontentdos">                                    
                                    <div class="tab-pane active" id="centered-tab3">
                                        <!-- texto -->
                                        <div class="opciones-texto" id="opciones-texto-parent" style="display: none;">
                                            {{ partial('editpage/opciones-texto') }}
                                        </div>
                                        <!-- /texto -->

                                        <!-- boton -->
                                        <div class="opciones-boton" id="opciones-boton" style="display: none;">
                                            {{ partial('editpage/opciones-boton') }}
                                        </div>
                                        <!-- /boton -->

                                        <!-- imagen -->
                                        <div class="opciones-imagen" id="opciones-imagen" style="display: none;">
                                            {{ partial('editpage/opciones-imagen') }}
                                        </div>
                                        <!-- /imagen -->

                                        <!-- video -->
                                        <div class="opciones-video" id="opciones-video" style="display: none;">
                                            {{ partial('editpage/opciones-video') }}
                                        </div>
                                        <!-- /video -->

                                        <!-- box -->
                                        <div class="opciones-box" id="opciones-box" style="display: none;">
                                            {{ partial('editpage/opciones-box') }}
                                        </div>
                                        <!-- /box -->

                                        <!-- icon -->
                                        <div class="opciones-icon" id="opciones-icon" style="display: none;">
                                            {{ partial('editpage/opciones-icon') }}
                                        </div>
                                        <!-- /icon -->

                                        <!-- url -->
                                        <div class="opciones-url" id="opciones-url" style="display: none;">
                                            {{ partial('editpage/opciones-url') }}
                                        </div>
                                        <!-- /url -->
                                    </div>
                                    <div class="tab-pane" id="centered-tab4">
                                        <ul class="navigation navigation-main navigation-accordion navacordos">
                                            <li> 
                                                <a href="#">                                                                      
                                                    <span><i class="fa fa-desktop" aria-hidden="true"></i>Página</span>
                                                </a>
                                                <ul class="navigation navigation-main navigation-accordion navacordos">
                                                    <li> 
                                                        <a href="#">                                                                      
                                                            <span><i class="fa fa-bars" aria-hidden="true"></i>Clear value</span>
                                                            <div class="botonescapas">
                                                                <a href="index2.html" class="botoncapas"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                                <a href="index2.html" class="botoncapas botoncapasdos"><i class="fa fa-unlock" aria-hidden="true"></i></a>
                                                                <a href="index2.html" class="botoncapas botoncapastres"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                            </div>
                                                        </a>
                                                        <ul class="navigation navigation-main navigation-accordion navacordos">
                                                            <li> 
                                                                <a href="#"><span><i class="fa fa-font" aria-hidden="true"></i>Tipografía</span>
                                                                <div class="botonescapas">
                                                                    <a href="index2.html" class="botoncapas"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                                    <a href="index2.html" class="botoncapas botoncapasdos"><i class="fa fa-unlock" aria-hidden="true"></i></a>
                                                                    <a href="index2.html" class="botoncapas botoncapastres"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                                </div>
                                                                </a>    
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                  
                </div>
            </div>

            <!-- Main content -->
            <div class="content-wrapper">                
                <div class="editor-header">
                    <div class="editor-logo">
                        <img src="/facturacionv8/img/logo_facturalaya_291_blanco.png" />
                    </div>
                    <div class="editor-header-url">
                        <p><a href="URL_BASE" target="_blank">URL_bASE</a></p>
                    </div>
                    <div class="editor-configurar">
                        <a href="javascript:void(0)" class="btn_mostrarpaneledicion" title="Configurar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a href="javascript:void(0)" class="btn_configurarpagina" title="Configurar"><i class="fa fa-cog" aria-hidden="true"></i></a>
                        <a style="display: none;" href="#" title="Guardar"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        <a href="javascript:void(0)" title="Guardar" class="btn_reiniciarpagina"><i class="fa fa-retweet" aria-hidden="true"></i></a>
                        <a href="javascript:void(0)" title="Publicar" class="editor-publicar btn_guardarcambios"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Guardar Cambios</a>
                        <a href="javascript:void(0)" title="Ayuda"><i class="fa fa-question" aria-hidden="true"></i></a>
                    </div>                    
                </div>
                <div class="editor-paneles">
                    <div class="editor-tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight text-center">
                            <li class="active"><p href="#centered-tab1" data-toggle="tab">Main Page</p></li>
                            <li style="display: none;"><p href="#centered-tab2" data-toggle="tab">Conver Page</p></li>
                        </ul>
                        <div class="editor-menu-responsivo">
                            <a href="#" class="editor-menu-activo" title="Mobil"><i class="fa fa-mobile" aria-hidden="true"></i></a>
                            <a href="#" title="tablet"><i class="fa fa-tablet" aria-hidden="true"></i></a>
                            <a href="#" title="PC"><i class="fa fa-desktop" aria-hidden="true"></i></a>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="centered-tab1">
                                <input type="hidden" id="txt_idpage" value="<?php echo $iduserpage; ?>">
                                <input type="hidden" id="txt_tipopagina" value="<?php echo $tipopagina; ?>">
                                <iframe id="contentlanding" spellcheck="false" src="<?php echo $urliframe; ?>"></iframe>
                            </div>
                            <div class="tab-pane" id="centered-tab2">
                                Probando cambios parte dos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="contenidobarraopciones" style="display: none;">
        <div id="barradeopciones" data-idselected="" class="opcion-texto-toolbar clearfix fullRoundToolbar ce-toolbar-top-radius" style="display: none; width: 325px;top:10px;left: 10px;display: block;height: 30px; z-index: 901000;">
            <div id="barradeopciones_opciones1" class="opcion-texto-toolbar-actions clearfix">
                <!-- ce-active-state clase que activa el botón -->
                <a href="javascript:void(0)" id="opcion-texto-bold" class="opcion-texto-bold opcion-texto-icon"><i class="fa fa-bold"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-italic" class="opcion-texto-italic opcion-texto-icon"><i class="fa fa-italic"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-underline" class="opcion-texto-underline opcion-texto-icon"><i class="fa fa-underline"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-strikethrough" class="opcion-texto-strikethrough opcion-texto-icon"><i class="fa fa-strikethrough"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-left" class="opcion-texto-left opcion-texto-icon"><i class="fa fa-align-left"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-center" class="opcion-texto-center opcion-texto-icon"><i class="fa fa-align-center"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-right" class="opcion-texto-right opcion-texto-icon"><i class="fa fa-align-right"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-unlink" class="opcion-texto-unlink opcion-texto-icon"><i class="fa fa-chain-broken"></i></a>
                <a href="javascript:void(0)" id="opcion-texto-close" class="opcion-texto-close opcion-texto-icon pull-right de-edit-advance"><i class="fa fa-times"></i></a>
            </div>
            <div id="barradeopciones_opciones2" class="opcion-texto-toolbar-link-edit pull-left clearfix" style="display: none;">
                <input type="text" value="" id="edit-link-text" placeholder="Link Text" data-link-id="">
                <input type="text" value="" id="edit-link" placeholder="Link URL" data-link-id="">
                <a href="javascript:void(0)" id="remove-ce-link" title="Unlink Text"><i class="fa fa-chain-broken"></i></a>
                <a href="javascript:void(0)" id="new-window-ce-link" title="Make Link Open In New Window" class="ceLinkSelf"><i class="fa fa-external-link-square"></i></a>
                <input id="color-ce-link" class="color linkColorInput" autocomplete="off" style="background-image: none; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);">
                <a href="javascript:void(0)" id="done-editing-link" title="Close Link Settings"><i class="fa fa-times"></i></a>
            </div>
        </div>
    </div>
    <div id="contenidoextra_ediciondeboton" style="display: none;">
        <input type="hidden" id="editar-boton-id-elementselected" class="editar-boton-id-elementselected" name="editar-boton-id-elementselected" value="">
    </div>
    <div id="contenidoextra_ediciondeimagen" style="display: none;">
        <input type="hidden" id="editar-imagen-id-elementselected" class="editar-imagen-id-elementselected" name="editar-imagen-id-elementselected" value="">
        <img style="display: none;" src="/facturacionv8/img/logo_facturalaya_291_blanco.png" id="editar-imagen-srcupload-elementselected" class="editar-imagen-srcupload-elementselected" name="editar-imagen-srcupload-elementselected" />
    </div>
    <div id="contenidoextra_ediciondebox" style="display: none;">
        <input type="hidden" id="editar-box-id-elementselected" class="editar-box-id-elementselected" name="editar-box-id-elementselected" value="">
    </div>
    <div id="contenidoextra_ediciondeicon" style="display: none;">
        <input type="hidden" id="editar-icon-id-elementselected" class="editar-icon-id-elementselected" name="editar-icon-id-elementselected" value="">
    </div>
    <div id="contenidoextra_ediciondevideo" style="display: none;">
        <input type="hidden" id="editar-video-id-elementselected" class="editar-video-id-elementselected" name="editar-video-id-elementselected" value="">
    </div>
    <div id="contenidoextra_ediciondeurl" style="display: none;">
        <input type="hidden" id="editar-url-id-elementselected" class="editar-url-id-elementselected" name="editar-url-id-elementselected" value="">
    </div>

    <!-- Ventana para Agregar imagen -->
    <div id="vm_upload_image" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span class="text-semibold modal-title">Importante</span>
                </div>
                <div class="modal-body">
                    <p>Recuerda que solo se aceptan archivos PNG, JPG, JPEG, GIF. También puedes recordar la imágen, a la derecha se muestra el preview de como quedará recortada tu imágen!...</p>
                    <hr>
                    <div class="row">
                        <div class="form-group col-lg-9">
                            <input id="fileimage" type="file" class="file-input" accept=".jpg,.gif,.png">
                        </div>
                        <div class="form-group col-lg-3">
                            <div class="previewrecorteimg" style="width: 100%;"></div>
                        </div>
                        <img src="" id="imagenresultado" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary legitRipple" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary legitRipple" id="btn_guardarimagen"><i class="icon-spinner6 spinner position-left btn_guardarimagen_loading" style="display: none;"></i><i class="icon-floppy-disk position-left btn_guardarimagen_icono"></i> Guardar Imágen</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Ventana para Agregar imagen -->

    <!-- Ventana configuracion página -->
    <div id="vm_configuracion_pagina" class="modal fade">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span class="text-semibold modal-title">Configuración de Página</span>
                </div>
                <div class="modal-body">
                    <div class="panel panel-flat">

                        <div class="panel-body">
                            <div class="tabbable nav-tabs-vertical nav-tabs-left">
                                <ul class="nav nav-tabs nav-tabs-highlight">
                                    <li class="active"><a href="#tab1_seo" data-toggle="tab" class="legitRipple" aria-expanded="true"><i class="fa fa-search position-left"></i> SEO</a></li>
                                    <li class=""><a href="#tab2_head" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="fa fa-code position-left"></i> Head</a></li>
                                    <li class=""><a href="#tab3_footer" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="fa fa-caret-down position-left"></i> Footer</a></li>
                                    <li class=""><a href="#tab4_customstyle" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="fa fa-file-code-o position-left"></i> Custom Style</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane has-padding active" id="tab1_seo">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <label>*Título de la página:</label>
                                                    <input name="titulopagina" id="titulopagina" type="text" class="custom_txt editpage_txt_titulopagina" placeholder="Escribe el título para tu página personal" value="Alex">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <label>*Descripción:</label>
                                                    <textarea rows="5" cols="5" id="descripcionpagina" name="descripcionpagina" class="custom_txt editpage_txt_descripcionpagina" placeholder="Escribe la descripción para tu página web"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane has-padding" id="tab2_head">
                                        <div class="alert alert-success alert-styled-right alert-arrow-right alert-bordered">
                                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                            Copia y pega el código que deseas ubicar justo antes de la etiqueta "&lt;/head&gt;"
                                        </div>

                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea rows="15" cols="5" id="editpage_txt_scriptheader" class="custom_txt editpage_txt_scriptheader" placeholder="Copia todos los scripts que deseas ubicar justo antes de la etiqueta &lt;/head&gt;"><?php echo $codigoheader; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="tab-pane has-padding" id="tab3_footer">
                                        <div class="alert alert-info alert-styled-right alert-arrow-right alert-bordered">
                                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                            Copia y pega el código que deseas ubicar justo antes de la etiqueta "&lt;/body&gt;"
                                        </div>

                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea rows="15" cols="5" class="custom_txt editpage_txt_scriptbody" placeholder="Copia todos los scripts que deseas ubicar justo antes de la etiqueta &lt;/body&gt;"></textarea>
                                        </div>
                                    </div>

                                    <div class="tab-pane has-padding" id="tab4_customstyle">
                                        <div class="alert alert-primary alert-styled-right alert-arrow-right alert-bordered">
                                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                            Copia y pega tu propio estilo personalizado para modificar tu página personal
                                        </div>
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea rows="15" cols="5" class="custom_txt editpage_txt_customstyle" placeholder="Copia y pega tus propios estilos para tu página personal!"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary legitRipple" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary legitRipple btn_guardar_configuracionpagina"><i class="icon-floppy-disk position-left"></i> Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Ventana configuracion página -->

</body>
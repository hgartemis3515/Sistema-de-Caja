<div class="panel panel-flat  border-top-indigo" style="display:none; position: relative; z-index: 27; max-width: 1100px; margin: 0 auto; margin-top: 15px; margin-bottom: 15px;">
    <div class="panel-body">
        <fieldset class="content-group">
            <legend class="text-bold">
                <span class="text-uppercase"> Dominio y Logos Principales</span> 
            </legend>
        </fieldset>


        <div class="row">
            <div class="col-lg-4 col-sm-5 col-md-5" id="content_pagina_home">
                <div class="img-preview-pag img-border">
                    <img src="https://arpsystem.com.pe/facturacionv8/herramientas/verimage/imguser-5d9acff964a6c-385babd83501a81c518f05a9d1277831.png" alt="Avatar" class="image">
                    <div class="overlay">
                        <div class="text"><a href="#" target="_blank" rel="noopener noreferrer"><i class="fa fa-link fa-1x" aria-hidden="true"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-sm-7 col-md-7">
                <div class="form-group">
                    <label class="font-weight-bold">
                        Dominio: (Ejem. miempresa.com )
                    </label>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <span class="btn btn-default text-initial legitRipple">
                                https://
                            </span>
                        </div>
                        <input type="text" value="<?php echo $contribuyente->dominio; ?>" name="dominio_personalizado"  id="dominio_personalizado" class="form-control" placeholder="Ingresa tu dominio...">
                        <div class="input-group-btn">
                            <button id="btn_guardar_dominio_logos" class="btn bg-indigo legitRipple" type="button">
                                <i class="icon-floppy-disk mr-2"></i>Guardar
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 img-size-logo">
                            <div class="img-preview-pag">
                                <img class="w-100 h-100 p-1" id="img_logo_461" src="<?php echo $contribuyente->logo_461; ?>" alt="">
                                <input type="hidden" value="<?php echo $contribuyente->logo_461; ?>" name="txt_logo_461" id="txt_logo_461" />
                                <div class="overlay">
                                    <div class="text">
                                        <a href="javascript:void(0)" class="btn_logo461x95" data-width="461" data-height="95">
                                            <i class="fa fa-upload" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center btn-info-size">
                                <button type="button" class="btn btn-default btn-xs mt-2 btn-raised legitRipple btn_logo461x95" data-width="461" data-height="95"><i class="icon-cloud-upload2 position-left"></i> Cambiar</button>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4  col-xs-6 img-size-logo">
                            <div class="img-preview-pag bg-indigo">
                                <img class="w-100 h-100 p-1" id="img_logo_291" src="<?php echo $contribuyente->logo_291; ?>" alt="">
                                <input type="hidden" value="<?php echo $contribuyente->logo_291; ?>" name="txt_logo_291" id="txt_logo_291" />
                                <div class="overlay">
                                    <div class="text">
                                        <a href="javascript:void(0)" class="btn_logo291x60" data-width="291" data-height="60">
                                            <i class="fa fa-upload" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="box-text-tam text-center btn-info-size">
                                <button type="button" class="btn btn-default btn-xs mt-2 btn-raised legitRipple btn_logo291x60" data-width="291" data-height="60"><i class="icon-cloud-upload2 position-left"></i> Cambiar</button>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4  col-xs-12 img-size-logo">
                            <div class="img-preview-pag" style="max-width: 60px; margin: 0 auto;">
                                <img class="w-100 h-100 p-1" id="img_logo_56" src="<?php echo $contribuyente->logo_56; ?>" alt="">
                                <input type="hidden" value="<?php echo $contribuyente->logo_56; ?>" name="txt_logo_56" id="txt_logo_56" />
                                <div class="overlay">
                                    <div class="text">
                                        <a href="javascript:void(0)" class="btn_logo56x56" data-width="56" data-height="56">
                                            <i class="fa fa-upload" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="box-text-tam text-center btn-info-size">
                                <button type="button" class="btn btn-default btn-xs mt-2 btn-raised legitRipple btn_logo56x56" data-width="56" data-height="56"><i class="icon-cloud-upload2 position-left"></i> Cambiar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>
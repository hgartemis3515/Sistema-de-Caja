<div class="col-lg-3 col-md-4">
    <div class="thumbnail  border-top-indigo">
        <div class="thumb">
            <img id="img_background_registro" style="max-width: 300px;" src="<?php echo $data['img_background_register']; ?>" alt="">
        </div>
        <div class="caption">
            <div class="input-group">
                <span class="input-group-addon font-weight-bold"><i class="icon-image2"></i></span>
                <input class="form-control" type="text" value="<?php echo $data['img_background_register']; ?>" name="url_img_background_registro" id="url_img_background_registro" />

                <span class="input-group-btn">
                    <button class="btn bg-indigo btn-icon legitRipple" id="btn_upload_image_registro" data-width="1280" data-height="720" type="button">
                        <i class="icon-upload"></i>
                    </button>
                </span>

            </div>
            <div class="text-center mt-20">
                <button type="button" id="btn_guardar_img_registro" class="btn bg-indigo legitRipple"> <i class="icon-box-remove mr-2"></i>Guardar Imágen</button>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-9 col-md-8">
    <div class="panel panel-flat  border-top-indigo">
        <div class="panel-body">
            <fieldset class="content-group">
                <legend class="text-bold">
                    <i class="icon-list mr-2" aria-hidden="true"></i>
                    <span class="font-weight-bold text-uppercase">Diseños para Registro</span>
                </legend>
            </fieldset>
            <div class="style_lista_plantillas">
            <?php                                    
                foreach ($lista_disenios_registro as $theme_registro) {
            ?>
                    <label class="box">
                        <input type="radio" name="opt_id_plantilla_registro" data-iddiseno="<?php echo $theme_registro->id_diseno; ?>" value="<?php echo $theme_registro->id_diseno; ?>" <?php if($theme_registro->id_diseno == $data['id_plantilla_registro']){ echo 'checked'; } ?> />
                        <div class="option">
                            <img title="<?php echo $theme_registro->id_diseno; ?>" src="<?php echo $theme_registro->preview; ?>" />
                        </div>
                    </label>
            <?php
                }
            ?>
            </div>
        </div>
    </div>
</div>
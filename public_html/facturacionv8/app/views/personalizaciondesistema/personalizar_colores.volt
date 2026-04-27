<div class="panel panel-flat  border-top-indigo" style="position: relative; z-index: 27; max-width: 1100px; margin: 0 auto; margin-top: 15px; margin-bottom: 15px;">
    <div class="panel-body">
        <form name="frm_color" id="frm_color" action="">
            <div class="row">
                <div class="col-lg-12">
                    <fieldset class="content-group">
                        <legend class="text-bold">
                            <span class="text-uppercase">Selecciona tu color personalizado</span> 
                        </legend>
                        <p>Podrás elegir colores para personalizar tu sistema ya sea en sólido y degradado</p>
                    </fieldset>
                </div>
                <div class="col-lg-6">
                    <label>Color Sólido</label>
                    <div class="radio">
                        <label>
                            <input type="radio" value="color_solido" name="radio_color" id="radio_solido" class="tipo_color control-primary" <?php if($data['color_base_sistema']->tipo_color == 'color_solido'){echo "checked"; } ?>>
                            Sólido
                        </label>
                    </div>


                    <div class="content-solid">
                        <div class="box_solido">
                            <div class="row">
                                <div class="col-lg-3"><input type="color" value="<?php if($data['color_base_sistema']->tipo_color == 'color_solido'){echo $data['color_base_sistema']->color_solido; } else { echo "#3f51b5"; } ?>" class="form-control" name="color_solido" id="color_solido"></div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-lg-6">
                    <label for="">Color Degradado</label>
                    <div class="radio">
                        <label>
                            <input type="radio" value="color_degradado" name="radio_color" id="radio_degradado" class="tipo_color control-success" <?php if($data['color_base_sistema']->tipo_color == 'color_degradado'){echo "checked"; } ?>>
                            Degradado
                        </label>
                    </div>


                    <div class="content-degradado">
                        <div class="box_degradado">
                            <div class="row">
                                <div class="col-lg-3"><input type="color" class="form-control" name="color_degradado_1" id="color_degradado_1" value="<?php if($data['color_base_sistema']->tipo_color == 'color_degradado'){echo $data['color_base_sistema']->color_1; } else { echo "#3f51b5"; } ?>"></div>
                                <div class="col-lg-3"><input type="color" class="form-control" name="color_degradado_2" id="color_degradado_2" value="<?php if($data['color_base_sistema']->tipo_color == 'color_degradado'){echo $data['color_base_sistema']->color_2; } else { echo "#7880f0"; } ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
                <div class="col-lg-12 text-center mt-5" id="btn_content_save">
                    <hr>
                    <button class="btn bg-indigo legitRipple btn_guardar_color_base_sistema mxy-20 mt-5 mr-2" type="button"><i class="icon-floppy-disk mr-2"></i>Guardar cambios</button>
                    <button class="btn bg-success legitRipple btn_reiniciar_colores_base mxy-20 mt-5 mr-2" data-idopcion="4" type="button"><i class="icon-rotate-ccw2 mr-2"></i>Deshacer Cambios</button>
                    <button class="btn bg-primary legitRipple btn_vista_previa_colores mxy-20 float-right mt-5 mr-2" type="button"> <i class="icon-eye mr-2"></i>Vista previa</button>
                </div>
            </div>
            
        </form>
    </div>
</div>
<style>
    #bg_solido, #bg_degradado{
        width: 100%;
        height: 40px;
    }
    button.bg-indigo{
        border: none!important;
        background-size: 500%!important;
        -webkit-appearance: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
        color: #fff;
        cursor: pointer;
        border-radius: 5rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        outline: none;
        -webkit-tap-highlight-color: transparent;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        padding: 7px 12px;
        font-size: 13px;
        line-height: 1.5384616;
        text-transform: uppercase;
        font-weight: 500;
    }
    .bg-indigo{
        border: none!important;
        background-size: 500%;
        -webkit-appearance: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)!important;
        color: #fff;
        cursor: pointer;
        border-radius: 5rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        outline: none;
        -webkit-tap-highlight-color: transparent;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        padding: 7px 12px;
        font-size: 13px;
        line-height: 1.5384616;
        font-weight: 500;
    }
    .bg-indigo i {
        color: #fff!important;
    
    }
 
.navigation li a>i.new_style_icon, .label-form i.new_style_icon, legend i.new_style_icon  {
    -webkit-text-fill-color: initial;
}
.navbar.bg-indigo {
    border-radius: 0!important;
    padding: 0;
    box-shadow: 0;
}
.zero-contenedor {
    width: 100%;
    background-color: #f5f2f0;
    border: 1px solid #E4E4E4;
    overflow-x: auto !important;
    padding: 8px;
    height: 240px;
    overflow-y: scroll;
}
.zero-contenedor-img {
    width: 100%;
    background-color: #f5f2f0;
    border: 1px solid #E4E4E4;
    /*overflow-x: auto;*/
    padding: 8px;
    height: 180px;
}
.mb-5{
    margin-bottom: 2em!important;
}
.checkbox label, .radio label {
    padding-left: 28px;
    font-size: 11px;
}
input[type=color] {
    height: 70px!important;
    width: 80px!important;
}
.img_subir_content {
    display: flex;
    justify-content: center;
    align-items: center;
}
.row_dominio {
    margin-left: -10px;
    margin-right: -10px;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.row_dominio.justify-content-md-center {
    -ms-flex-pack: center!important;
    justify-content: center!important;
}
.img-preview-pag{
	position: relative;
}

.img_content{
	position: relative;
	border: 2px dashed #ddd;
	border-radius: 2px;
	background-color: #fff;
	width: 100%;
	height: 120px;
	padding: 1em;
	color: #ddd;
}
.img_content:hover{
	border: 2px dashed #7880f0;
	cursor: pointer;
	transition: all .5s;
	color:#7880f0;
}
.img-panel{
	width: 100%;
	height: 140px;
}
/* Efecto overlay */

.image {
  display: block;
  width: 100%;
  height: auto;
}

.overlay {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  transition: .6s ease;
  background-color: rgba(0, 0, 0, 0.486);
}

.img-preview-pag:hover .overlay {
  opacity: 1;
  transition: .5s;
}

.text {
	color: white;
	font-size: 20px;
	position: absolute;
	top: 50%;
	left: 50%;
	-webkit-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	text-align: center;
	border: 1px solid #fff;
	border-radius: 20%;
	width: 30px;
	height: 30px;
}
.text a{
	color: #fff;
}
/* class */
.ml-2{
	margin-left: 20px;
}
.mr-2{
	margin-right: 20px;
}
.mr-3{
	margin-right: 13px!important;
}
.mt-2{
	margin-top: 20px;
}
.mt-4{
	margin-top: 40px;
}
.p-1{
	padding: 10px!important;
}
.w-100{
	width: 100%;
}
.h-100{
	height: 100%;
}
@media(min-width: 300px) and (max-width: 1000px){
    .col-lg-1 {
        width: 50%;
        float: left;
    }
    .zero-contenedor {
        overflow-y: auto;
    }
}
@media(max-width: 1024px){
    .col-lg-1 {
        width: 50%;
        float: left;
    }
    .zero-contenedor {
        overflow-y: auto;
    }
}


/* ESTILOS PARA SELECT IMAGENES */
/*=====================
ESTILOS PARA LISTA DE PLANTILLAS LOGIN
=======================*/

.style_lista_plantillas {
    /*
    width: 100%;
    max-width: 1080px;
    margin: 0 auto;
    padding: 0 20px;
    */
    text-align: center;
}
#img_background_registro, #img_background_login {
    padding: 1em;
}
.style_lista_plantillas .title {
    font-size: 22px;
    font-weight: 500;
    margin-bottom: 20px;
    text-align: center;
}

.style_lista_plantillas input[type="radio"] {
    display: none;
}

.style_lista_plantillas .content {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

.style_lista_plantillas .option {
    width: 100%;
    height: 100%;
    margin: 14px;
    /*padding: 8px 15px;*/
    border: 2px solid #00000050;
    border-radius: 6px;
    display: flex;
    align-items: center;
    cursor: pointer;
}

.style_lista_plantillas .option img {
    width: 100%;
    height: 150px;
}

.style_lista_plantillas .radio-content .radio-title {
    display: inline-block;
    color: #212121;
    font-size: 15px;
    font-weight: 500;
    line-height: 22px;
}

.style_lista_plantillas .radio-content .radio-title span {
    display: inline-block;
    font-size: 24px;
    text-transform: capitalize;
    vertical-align: middle;
}

.style_lista_plantillas input:checked+.option {
    border: 2px solid #8373e6;
    animation: bounceIn 1s;
    background: #8373e6;
}

/* Let's write a media query to make it responsive */

@media (max-width: 500px) {
    .style_lista_plantillas .radio-content .radio-title {
        font-size: 13px;
    }
    .style_lista_plantillas .radio-content .radio-title span {
        font-size: 18px;
    }
    .style_lista_plantillas .option {
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
}


/* Let's create an bounceIn animation */

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale3d(0.3, 0.3, 0.3);
    }
    20% {
        transform: scale3d(1.1, 1.1, 1.1);
    }
    40% {
        transform: scale3d(0.9, 0.9, 0.9);
    }
    60% {
        opacity: 1;
        transform: scale3d(1.03, 1.03, 1.03);
    }
    80% {
        transform: scale3d(0.97, 0.97, 0.97);
    }
    100% {
        opacity: 1;
        transform: scaleX(1);
    }
}


/* Let's write a media query to make it responsive */

@media (max-width: 500px) {
    .style_lista_plantillas .content .title {
        font-size: 28px;
        line-height: 28px;
    }
    .style_lista_plantillas .content .desc {
        font-size: 16px;
        margin-bottom: 20px;
    }
}

/*=====================
FIN: ESTILOS PARA LISTA DE PLANTILLAS LOGIN
=======================*/

/*=====================
menu_opciones_personalizar
=======================*/
.menu_opciones_personalizar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.menu_opciones_personalizar .title {
    font-size: 22px;
    font-weight: 500;
    margin-bottom: 20px;
}

.menu_opciones_personalizar .content {
    width: 100%;
    margin: 0 auto;
    text-align: center;
    padding: 0 20px 15px 20px !important;
}

.menu_opciones_personalizar .box input {
    display: none;
}

.menu_opciones_personalizar .option {
    margin: 10px;
    width: 150px;
    height: 120px;
    border: 3px solid transparent;
    display: inline-block;
    border-radius: 10px;
    position: relative;
    text-align: center;
    box-shadow: 0 0 20px #c3c3c367;
    cursor: pointer;
}

.menu_opciones_personalizar .option>i {
    color: #ffffff;
    background-color: #8373e6;
    font-size: 20px;
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%) scale(4);
    border-radius: 50px;
    padding: 3px;
    transition: 0.2s;
    pointer-events: none;
    opacity: 0;
}

.menu_opciones_personalizar .option .icon {
    width: 50px;
    height: 50px;
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.menu_opciones_personalizar .option .icon i {
    color: #8373e6;
    line-height: 50px;
    font-size: 40px;
}

.menu_opciones_personalizar .option .icon span {
    color: #8373e6;
    font-size: 14px;
    /*text-transform: uppercase;*/
}

.menu_opciones_personalizar .box input:checked+.option {
    border: 3px solid #8373e6;
}

.menu_opciones_personalizar .box input:checked+.option>i {
    opacity: 1;
    transform: translateX(-50%) scale(1);
}

/*=====================
FIN: menu_opciones_personalizar
=======================*/

</style>

<div class="content">


    <!-- inicio: menú para el módulo personalizado -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="section-heading text-center pb-3 mb-3">
                <h2 class="title m-0"> ¡Ahora ya Puedes Personalizar Todo Tu Sistema!</h2>
            </div>
        </div>
        <div class="col-xl-12 col-md-12 my-3">
            <!-- single item -->	
            <div class="menu_opciones_personalizar">
                <div class="title">Selecciona una Sección</div>
                <div class="content">
                    <label class="box">
                        <input type="radio" name="opt_menu_p" id="opt_menu_p_login" value="login" checked />
                        <span class="option">
                            <i class="fa fa-check-circle"></i>
                            <div class="icon">
                                <i class="icon-user-lock"></i>
                                <span>Login</span>
                            </div>
                        </span>
                    </label>
                    <label class="box">
                        <input type="radio" name="opt_menu_p" value="registro" id="opt_menu_p_registro" />
                        <span class="option">
                            <i class="fa fa-check-circle"></i>
                            <div class="icon">
                                <i class="icon-user-plus"></i>
                                <span>Registro</span>
                            </div>
                        </span>
                    </label>
                    <label class="box" style="display:none;">
                        <input type="radio" name="opt_menu_p" value="dominio" id="opt_menu_p_dominio" />
                        <span class="option">
                            <i class="fa fa-check-circle"></i>
                            <div class="icon">
                                <i class="icon-earth"></i>
                                <span>Dominio</span>
                            </div>
                        </span>
                    </label>
                    <label class="box">
                        <input type="radio" name="opt_menu_p" value="colores" id="opt_menu_p_colores" />
                        <span class="option">
                            <i class="fa fa-check-circle"></i>
                            <div class="icon">
                                <i class="icon-droplet"></i>
                                <span>Colores</span>
                            </div>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!-- fin: menú para el módulo personalizado -->

    <div class="row" id="modulo_personalizar_login">
        <?= $this->partial('personalizaciondesistema/personalizar_login') ?>
    </div>
    
    <div class="row" id="modulo_personalizar_registro" style="display:none;">
        <?= $this->partial('personalizaciondesistema/personalizar_registro') ?>
    </div>

    <div class="row" id="modulo_personalizar_dominio" style="display:none;">
        <?= $this->partial('personalizaciondesistema/personalizar_dominio') ?>
    </div>

    <div class="row" id="modulo_personalizar_colores" style="display:none;">
        <?= $this->partial('personalizaciondesistema/personalizar_colores') ?>
    </div>

    <!-- Ventana para Agregar imágen -->
    <style>
        .kv-file-content {
            max-height: 300px !important;
        }

        .file-thumbnail-footer {
            display: none !important;
        }
    </style>

    <!-- Ventana para Agregar Imágen -->
    <div id="vm_cargar_imagen" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <input id="fileimage" type="file" class="file-input" accept=".jpg,.gif,.png">
                            <div class="text-right mt-10">
                            <button type="button" class="btn btn-primary legitRipple" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary legitRipple" id="btn_guardarimagen"><i class="icon-spinner6 spinner position-left btn_guardarimagen_loading" style="display: none;"></i><i class="icon-floppy-disk position-left btn_guardarimagen_icono"></i> Guardar Imágen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Ventana para Agregar imágen -->
    
</div>


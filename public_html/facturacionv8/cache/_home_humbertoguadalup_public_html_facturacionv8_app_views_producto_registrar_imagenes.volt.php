<style>

.btn-labeled.btn-xs>b {
    padding: 10px; 
}
.btn-min{
    padding: 5px 11px;
}
.cursor-none{
    cursor: initial!important;
}

.img-border-content{
    border: 1px solid #7880f0;
}
/*.img-border{
    box-shadow: hsl(0, 0%, 80%) 0 0 16px;
    border: 4px solid #fff;
    border-radius: 5px;
}*/
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
.overflow-auto{
    overflow: auto;
    width: 100%;
        height: 400px;
}
.content-img {
    font-size: 20px;
    text-transform: uppercase;
    font-weight: 700;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.plus-icon {
    display: block;
    font-size: 30px;
}
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
.text-initial{
    text-transform: initial!important;
}
/* New class */
.content-box-1, .content-box-2,  .content-box-3{
    margin-bottom: 1em;
}
.modal-dialog .content-box-1 .thumbnail {
    border-width: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    width: 120px;
    height: 120px;
    display: block;
    margin: auto;
}
.content-box-1 .thumbnail .thumb {
    position: relative;
    display: block;
    text-align: center;
    width: 100%;
    height: 100%;
    background: #f3f4ff;
    border-radius: 5px;

}
.content-box-1 .thumbnail  .thumb img:not(.media-preview) {
    display: inline-block;
    width: 100%;
    max-width: 100%;
    height: 100%;
}

.modal-dialog .content-box-2 .thumbnail {
    border-width: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    width: 167.66px;
    height: 80px;
    display: block;
    margin: auto;
}
.content-box-2 .thumbnail .thumb {
    position: relative;
    display: block;
    text-align: center;
    width: 100%;
    height: 100%;
    background: #f3f4ff;
    border-radius: 5px;

}
.content-box-2 .thumbnail  .thumb img:not(.media-preview) {
    display: inline-block;
    width: 100%;
    max-width: 100%;
    height: 100%;
    border-radius: 8px;
}

.modal-dialog .content-box-3 .thumbnail {
    border-width: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    width: 80px;
    height: 167.66px;
    display: block;
    margin: auto;
}
.content-box-3 .thumbnail .thumb {
    position: relative;
    display: block;
    text-align: center;
    width: 100%;
    height: 100%;
    background: #f3f4ff;
    border-radius: 5px;

}
.content-box-3 .thumbnail  .thumb img:not(.media-preview) {
    display: inline-block;
    width: 100%;
    max-width: 100%;
    height: 100%;
    border-radius: 8px;
}
/* LOADING */
.loading {
/* position: absolute; */
top: 50%;
left: 50%;
}
.loading-bar {
    display: inline-block;
    width: 4px;
    height: 18px;
    border-radius: 4px;
    animation: loading 1s ease-in-out infinite;
}
.loading-bar:nth-child(1) {
    background-color: #3f51b5;
    animation-delay: 0;
}
.loading-bar:nth-child(2) {
    background-color: #2196f3;
    animation-delay: 0.09s;
}
.loading-bar:nth-child(3) {
    background-color: #4caf50;
    animation-delay: .18s;
}
.loading-bar:nth-child(4) {
    background-color: #00bcd4;
    animation-delay: .27s;
}

@keyframes loading {
    0% {
    transform: scale(1);
    }
    20% {
    transform: scale(1, 2.2);
    }
    40% {
    transform: scale(1);
    }
}
/* /LOADING */
/* Media queries 
=====================*/
/*@media(min-width: 300px) and (max-width: 430px){
    .col-12 {
        width: 100%;
    }
}*/
@media(min-width: 360px){
    .form-control {
        height: 33px;
    }
    .btn {
        font-size: 12px;
        padding: 7px 12px
    }
   
    .form-group div[class*=col-lg-]:not(.control-label)+div[class*=col-lg-] {
    margin-top: 0px;
    }
    .btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
    }
    .img-size-logo{
        height: 115px;
    }
    
}
@media (min-width: 600px) and (max-width: 1024px){
    .form-control {
        height: 32px;
    }
    .btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
    }
    .img-size-logo{
        height: 120px;
    }
    .col-sm-6 {
        width: 50%;
        float: left;
    }
    .modal-dialog {
    position: relative;
    width: 70%;
    margin: auto;
}

}
@media only screen and (max-width: 1170px) and (min-width: 770px){
    .form-group div[class*=col-lg-]:not(.control-label)+div[class*=col-lg-] {
    margin-top: 0px;
    }
    .btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
    }
    .img-size-logo{
        height: 120px;
    }
}
@media(min-width: 900px){
    .btn-info-size {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, 0);
}
    /*.form-control {
        height: 32px;
    }*/
    .img-size-logo{
        height: 120px;
    }
}
/* CONTENEDORES DE IMG */
#btn_agregarimagen_cuadrado b, #btn_agregarimagen_vertical b {
    display: block;
    margin-top: 5px;
}
.contenedor-imagenes-cuadrado {
	width: 100%;
	background-color: #f5f2f0;
	border: 1px solid #E4E4E4;
	overflow-x: auto;
	padding: 3px;
    height: 145px;
}
#btn_agregarimagen_cuadrado {
    height: 120px;
    background-color: rgba(255, 255, 255, 0.322);
    border: 5px dashed #fff;
    color: #6e6e6e;
    float: left;
    border-radius: 10px;
    width: 120px;
    padding: 5px;
}
.contenedor-imagenes-horizontal {
	width: 100%;
	background-color: #f5f2f0;
	border: 1px solid #E4E4E4;
	overflow-x: auto;
	padding: 3px;
    height: 108px;
    padding-top: 8px;
}
#btn_agregarimagen_horizontal {
    height: 80px;
    background-color: rgba(255, 255, 255, 0.322);
    border: 5px dashed #fff;
    color: #6e6e6e;
    float: left;
    border-radius: 10px;
    padding: 5px;
    width: 167.66px;
}
.contenedor-imagenes-vertical {
	width: 100%;
    height: 180px;
	background-color: #f5f2f0;
	border: 1px solid #E4E4E4;
	overflow-x: auto;
	padding: 3px;
    padding-top: 8px;
    text-align: center;
}
#btn_agregarimagen_vertical {
    height: 167.66px;
    background-color: rgba(255, 255, 255, 0.322);
    border: 5px dashed #fff;
    color: #6e6e6e;
    border-radius: 10px;
    padding: 5px;
    width: 80px;
}
/*CONTROL CREADO EN FITNESSBYGAB */
#btn_agregarimagen{
	height: 200px;
	background-color: rgba(255, 255, 255, 0.322);
	border: 5px dashed #fff;
	color: #6e6e6e;
	float: left;
}

div.close.fileinput-remove
{
	display: none;
}
.btn-primary, .btn-primary.disabled, .btn-primary:disabled {
    color: #fff;
    background-color: #563d7c;
    border-color: #563d7c;
}
.btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #6352ce;
    border-color: #6352ce;
}
.border-circle{
	padding: 20px;
}
.card-title{
	font-size: 50px;
	text-shadow: 1px 3px 1px #99a8af;
	color: #563d7c;
}
.card-header{
	text-transform: uppercase;
	font-size: 16px;
	font-weight: 700;
}
.card-footer {
	padding: 0rem; 
}
.camera-item{
	border-right: 1px solid rgba(0,0,0,.125);
	border-left: 1px solid rgba(0,0,0,.125);
}
.contenido_planpersonalizado{
	margin: 2rem;
}
.fa-star{
	font-size: 10px;
	color: #563d7c;
	padding: 10px;
}
.modal-lg {
	max-width: 950px;
}
.navigation-item .btn{
	/* padding: 3px 6px 3px 6px; */
	padding: 0px;
	background: transparent;
	color: #646464;
}
.navigation-item{
	padding: 0px;
}
.navigation-item a{
	width: 100%;
    height: 100%;
	padding: 20px !important;
}
.camera-item button {
	width: 100%;
    height: 100%;
	padding: 12px !important;
}

.checkbox-item {
	padding-top: 20px !important;
}

.icons:hover
{
	background-color: rgb(221, 221, 221);
	color: #fff;
	margin: 0px;	
}
.row {
	margin-right: 0px; 
	margin-left: 0px; 
}

/* container upload image  */
iframe.ytb-embed {
	max-width: 96% !important;
	display: block;
	margin: 10px auto;
}

#contenedor-wrapper {
	padding: 0px;
	width: 100%;
	height: auto;
	margin: auto;
	overflow: hidden;
}
#contenedor-wrapper h1,
p {
	margin: 0px;
	padding: 0px;
}
#contenedor-wrapper h1 {
	text-align: center;
	font-family: 'Indie Flower', cursive;
	color: #00bfb6;
	text-decoration: underline;
}
.image-container-producto {
	float: left;
	width: 260px;
	height: 196px;
	position: relative;
	margin: 6px 6px 1px 8px;
	cursor: pointer;
}
.image-container-producto img {
	width: 100%;
	height: 100%;
	position: absolute;
	border: 3px solid #fff;
}
.image-opcion-producto {
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.7);
	position: absolute;
	opacity: 0;
	transition: all 300ms ease-in-out;
	-webkit-transition: all 300ms ease-in-out;
	-moz-transition: all 300ms ease-in-out;
	-o-transition: all 300ms ease-in-out;
	-ms-transition: all 300ms ease-in-out;
}
.image-opcion-producto h1 {
	padding: 45px 0px 5px 0px;
	text-align: center;
	margin-left: -15px;
	text-transform: uppercase;
	font-family: 'Indie Flower', cursive;
	font-size: 25px;
	color: #00bfb6;
}
.boton-eliminar-imagen{
	position: absolute;
	left: 50%;
	top: 50%;
	transform: translate(-50%, -50%);
	-webkit-transform: translate(-50%, -50%);
}
.image-opcion-producto p {
	text-align: center;
	font-family: 'Overpass Mono', monospace;
	color: #fff;
}
.image-container-producto:hover .image-opcion-producto {
	opacity: 1;
}
.thumbnails-tabla{
	width: 100%;
	height: 20px;
	overflow: hidden;
}
.zero-contenedor {
	position: relative;
}
.btn-interior {
	position: absolute;
	top: 0;
	right: 0;
}
/* end container image */
</style>

<form name="frm_imagenes" id="frm_imagenes" action="">

    <input type="hidden" name="array_listaimagenes" id="array_listaimagenes" value="">
    <input type="hidden" name="vm_iddetalle" id="vm_iddetalle" value="" >
    <div class="row">

        <!-- Img cuadrado -->
        <div class="col-md-12" style="padding-bottom: 20px;">
            <label class="label-form"><i class="fa fa-picture-o position-left"></i>Imágenes en formato Cuadrado (Max. 10 Img.)</label>
            <div class="contenedor-imagenes-cuadrado zero-contenedor">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6" style="display: block; margin: auto;">
                    <button type="button" id="btn_agregarimagen_cuadrado" class="btn legitRipple btn_agregarimagen"><b><i class="icon-plus-circle2"></i></b> Agregar Imagen</button>
                    <div id="contenedor-wrapper-imagenes">
                    </div>
                </div>
                <input type="hidden" name="array_listaimagenes_cuadradas" id="array_listaimagenes_cuadradas" value="">

                <div id="contenedor_imagenes_cuadradas">
                    
                </div>
                
            </div>
        </div>

        <!-- Img Horinzontal -->
        <div class="col-md-12" style="padding-bottom: 20px;">
            <label class="label-form"><i class="fa fa-picture-o position-left"></i>Imágenes en formato Horizontal (Max. 10 Img.)</label>
            <div class="contenedor-imagenes-horizontal zero-contenedor">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="display: block; margin: auto;">
                    <button type="button" id="btn_agregarimagen_horizontal" class="btn legitRipple btn_agregarimagen"><b><i class="icon-plus-circle2"></i></b> Agregar Imagen</button>
                    <div id="contenedor-wrapper-imagenes">
                    </div>
                </div>

                <input type="hidden" name="array_listaimagenes_horizontales" id="array_listaimagenes_horizontales" value="">

                <div id="contenedor_imagenes_horizontales">
                    
                </div>
            </div>
        </div>
    </div>

   <!-- Img Vertical -->
    <div class="col-md-12" style="padding-bottom: 20px;">
        <label class="label-form"><i class="fa fa-picture-o position-left"></i>Imágenes en formato Vertical (Max: 10 Img.)</label>
        <div class="contenedor-imagenes-vertical zero-contenedor">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="display: block; margin: auto;">
                <button type="button" id="btn_agregarimagen_vertical" class="btn legitRipple btn_agregarimagen"><b><i class="icon-plus-circle2"></i></b> Agregar <br> Imagen</button>
                <div id="contenedor-wrapper-imagenes">
                </div>
            </div>

            <input type="hidden" name="array_listaimagenes_verticales" id="array_listaimagenes_verticales" value="">

            <div id="contenedor_imagenes_verticales">
               
            </div>
        </div>
    </div>

 
    <div class="row">
        <div class="col-md-12 text-right">
            <div class="form-group">
                <button type="button" class="btn btn-default mr-2" data-dismiss="modal">Cerrar</button>
                <button class="btn bg-indigo legitRipple btn_guardar_nuevo_producto" type="button">
                    <i class="icon-floppy-disk mr-1"></i>  Guardar
                </button>
            </div>
        </div>
    </div>
</form>
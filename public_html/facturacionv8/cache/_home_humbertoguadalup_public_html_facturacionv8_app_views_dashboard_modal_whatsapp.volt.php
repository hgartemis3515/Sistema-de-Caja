<style>
/* modal whatsapp */
.box-content-text {
    box-shadow: rgb(0 0 0 / 16%) 0px 1px 4px;
    padding: 2em 15px;
    border-radius: 15px;
}
.mt-5 {
    margin-top: 1em!important;
}
.form-text {
    background: rgb(239, 239, 239);
    border: none;
}
.phone-main {
    box-shadow: rgb(0 0 0 / 20%) 0px 1px 3px;
    border-color: rgba(0, 0, 0, 0.2);
    padding: 35px 8px 35px 8px;
    border-radius: 15px;
	width: 280px;
    float: right;
}
.single-icon-number {
    background: #ededed;
    padding: 5px;
}

.profile-img {
    border-radius: 50%;
    display: inline-block;
}
.single-icon-number #codigo_pais {
    display: inline-block;
    margin: 15px 5px 15px 15px;
}
.single-icon-number p:last-child {
    display: inline-block;
    margin: 15px 15px 15px 5px;
}
.body-text-message {
    background: #ece5dd;
    height: 350px;
	display: flex;
	justify-content: flex-end;
    align-items: flex-end;
}
.footer-send-text {
    background: #f0f0f0;
    padding: 5px;
    display: grid;
    grid-template-columns: 80% 20%;
	justify-content: center;
    align-items: center;

	
}
.footer-send-text .form-control {
    border: none;
}
.text-item-single {
    background-color: #DCF8C6;
    padding: 5px 10px;
    box-shadow: 0 1px 0.5px rgb(0 0 0 / 13%);
    max-width: 200px;
    border-radius: 7.5px;
	margin: 5px 10px;
	position: relative;
	-ms-word-break: break-all;
	word-break: break-all;
	word-break: break-word;
	-ms-hyphens: auto;
	-moz-hyphens: auto;
	-webkit-hyphens: auto;
	hyphens: auto;
}
.text-item-single p{
	margin: 0;
}
.input-select2 .select2-container--default .selection .select2-selection.select2-selection--single {
    border-bottom-left-radius: 15px;
    border-top-left-radius: 15px;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
	background: rgb(239, 239, 239);
    border: none;
}
.select2-container--open .select2-dropdown--below {
    width: 200px!important;
}
.input-group-1 {
    display: grid;
    grid-template-columns: 80px 1fr;
}
.bandera-peru{
	position: relative;
}
.bandera-peru::after{
	content: ' ';
	position: absolute;
	left: 0;
	top: 0;
	background-image: url('/facturacionv8/img/banderas/peru.png');
}
.box-clientes {
    position: absolute;
    left: 58%;
    top: 50%;
    transform: translate(-50%, -50%);
}
.text-right.icon-docs {
    float: right;
    margin-bottom: 10px;
}
#select_emoji {
    padding: .7em 1.5em;
    margin-right: 10px;
}
#btn_add_emoji_whatsapp {
    position: absolute;
    left: 60%;
    top: -45%;
}
.display-movil-responsive{
	display: none;
}
@media only screen and (max-width: 760px) and (min-width: 300px){
    .display-movil-responsive{
        display: none;
    }

}

@media only screen and (min-width: 300px) and (max-width: 767px) {
    .display-movil-responsive{
        display: none!important;
    }
}

@media(min-width: 1200px){

    .display-movil-responsive{
        display: inherit!important;
    }
}
</style>
<!-- Large modal -->
<div id="modal_whatsapp" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<input type="hidden" value="" id="txt_dominio_whatsapp" name="txt_dominio_whatsapp" />
			<input type="hidden" value="" id="txt_url_a4_whatsapp" name="txt_url_a4_whatsapp" />
			<input type="hidden" value="" id="txt_url_ticket_whatsapp" name="txt_url_ticket_whatsapp" />
			<input type="hidden" value="" id="txt_url_xml_whatsapp" name="txt_url_xml_whatsapp" />
			<input type="hidden" value="" id="txt_tipo_cpe_whatsapp" name="txt_tipo_cpe_whatsapp" />
			<input type="hidden" value="" id="txt_serie_cpe_whatsapp" name="txt_serie_cpe_whatsapp" />
			<input type="hidden" value="" id="txt_numero_cpe_whatsapp" name="txt_numero_cpe_whatsapp" />
			<input type="hidden" value="" id="txt_phone_cliente_whatsapp" name="txt_phone_cliente_whatsapp" />
			<input type="hidden" value="" id="txt_nombre_cliente_whatsapp" name="txt_nombre_cliente_whatsapp" />
			
			<div class="modal-header">
				<h5 class="modal-title">Envía un Mensaje de WhatsApp a Tus Clientes</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body position-relative">
				<div class="box-clientes text-center">
					<i class="fa fa-angle-right mr-3 fa-5x text-indigo"></i>
					<h5>Así lo verán <br>tus clientes</h5>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="box-content-text">
							<form action="">
								<label>Escribe el número de WhatsApp de tu Cliente:</label>
						
								<div class="input-group-1 input-group">
									<span class="input-select2">
										<select  class="select2" name="codigo_pais" id="codigo_pais_select">
											<option value="Peru" data-codigo="+51" selected>Perú <span class="codigo_pais">+51</span></option>
										</select>
									</span>
									<input type="phone" class="form-control form-text" id="celular_cliente_whatsapp">
								</div>
								<small>Recuerda confirmar el código de tu país</small>
								<p class="font-weight-bold mt-5">Mensaje: </p>
								<div class="row">
									<div class="col-lg-4">
										<div class="position-relative">
											<select  class="select_emoji form-text" name="select_emoji" id="select_emoji">
											</select>
											<button type="button" id="btn_add_emoji_whatsapp" class="btn btn-primary legitRipple" style="padding: 2.5px 9px;">+</button>
										</div>
									</div>
									<div class="col-lg-8">
										<div class="text-right icon-docs">
											<div class="btn-group">
												<button type="button" id="btn_xml_whatsapp" class="btn form-text legitRipple"><img src="/facturacionv8/img/svg/xml_cpe.svg" width="15px" alt=""> XML</button>
												<button type="button" id="btn_a4_whatsapp" class="btn form-text legitRipple"><img src="/facturacionv8/img/svg/pdf_cpe.svg" width="15px" alt=""> A4</button>
												<button type="button" id="btn_ticket_whatsapp" class="btn form-text legitRipple"><img src="/facturacionv8/img/svg/ticket_cpe.svg" width="15px" alt=""> TICKET</button>
											</div>
										</div>
									</div>
								</div>

								<textarea name="mensaje_whatsapp"  cols="30" rows="5" class="form-control form-text" id="mensaje_whatsapp">Gracias por tu confianza, descarga tus documentos desde los siguientes enlaces: {url_pdf_a4}, {url_xml}</textarea>
								<small>Por ejemplo: "Gracias por tu confianza, descarga tus documentos desde los siguientes enlaces: {url_pdf_a4}, {url_xml}"</small>
								<div class="text-center mt-5">
									<a href="" target="_blank" id="btn_enviar_whatsapp" class="btn btn-primary">Enviar mensaje por WhatsApp</a>
								</div>
								
							</form>
						</div>
					</div>
					<div class="col-lg-6 display-movil-responsive">
						<div class="content-phone">
							<div class="phone-main">
								<div class="single-icon-number">
									<img src="/facturacionv8/img/icon-user.svg" class="profile-img" width="50px"  alt="">
									<p id="codigo_pais"></p>
									<p id="number_phone_2"></p>
								</div>
								<div class="body-text-message">
								</div>
								<div class="footer-send-text">
									<div class="input-send-message">
										<input type="text" class="form-control">
									</div>
									<div class="icon-box-send text-center">
										<img src="/facturacionv8/img/send-icon.svg" width="30px" alt="" class="icon-send">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /large modal -->
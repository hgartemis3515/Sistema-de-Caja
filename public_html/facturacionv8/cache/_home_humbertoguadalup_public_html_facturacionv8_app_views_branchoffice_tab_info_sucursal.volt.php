<input type="hidden" name="idsucursal" id="idsucursal" value="<?php echo $idsucursal; ?>" />

<div class="col-lg-2">	
	<div class="form-group">
		<label title="Este campo sirve para consignar en los XML el Código asignado por SUNAT para su establecimiento anexo declarado en el RUC." for="ruc" class="label-form">
			<i class="fa fa-building mr-2"></i>
			<a href="https://youtu.be/Dlolwym7eac" target="_blank">Cod.L.Anexo:</a>
		</label>
		<input type="text" class="form-control form-control-sm" value="0000" name="codigo" id="txt_codigo" placeholder="Codigo Sucursal SUNAT:"> 
	</div>
</div>

<div class="col-lg-5">	
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="fa fa-building mr-2"></i>
			Nombre de Sucursal/Almacén
		</label>
		<input type="text" class="form-control form-control-sm" name="nombre_sucursal" id="nombre_sucursal" placeholder="Nombre de sucursal"> 
	</div>
</div>

<div class="col-lg-2">	
	<div class="form-group">
		<label for="ruc" class="label-form">
			<img style="width: 17px;" src="/facturacionv8/img/sunat_logo.png" class="position-left">
			IGV
		</label>
		<select class="select_igv" name="factor_igv_sucursal" id="factor_igv_sucursal">
			<option value='18'>18%</option>
			<option value='10'>10%</option>
			<option value='10.5'>10.5%</option>
		</select>
	</div>
</div>

<div class="col-lg-3">	
	<div class="form-group">
		<label class="label-form">
			<i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
			¿Mostrar Glosa Amazonía?
		</label>
		<select class="select_glosa_amazonia" name="glosa_amazonia" id="glosa_amazonia">
		    <option value="no">No</option>
		    <option value="si">Si</option>
		</select>
	</div>
</div>

<div class="col-lg-12" id="explicacion_reduccion_igv" style="display:none;">
	<div class="alert alert-styled-left alert-styled-custom alert-arrow-left alpha-primary alert-bordered">
		<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
		<span class="text-semibold">Reducción del IGV al 10% para Restaurantes y Hoteles, según <a href="https://busquedas.elperuano.pe/normaslegales/aprueban-texto-unico-ordenado-de-la-ley-de-impulso-al-desarr-decreto-supremo-n-013-2013-produce-1033071-5/" target="_blank">D.S. 013-2013-PRODUCE.</a>. <a href="https://emprender.sunat.gob.pe/tributando/temas-actualidad/reduccion-igv-para-restaurantes-hoteles" target="_blank">¡Haz Click Aquí para Ver más Información!</a></span>
		<br /><br />
		<ul class="media-list">

			<li class="media" style="margin-top: 0px !important;">
				<div class="media-left">
					<a href="#" class="btn text-primary btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
				</div>
				
				<div class="media-body">
					Esta referida ley entró en vigencia el 01-09-2022 y será válida hasta el 31-12-2024.
				</div>
			</li>

			<li class="media" style="margin-top: 0px !important;">
				<div class="media-left">
					<a href="#" class="btn text-primary btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
				</div>
				
				<div class="media-body">
					Solo Están Comprendidas las Micro y Pequeñas Empresas afectas al IGV.
				</div>
			</li>

			<li class="media" style="margin-top: 0px !important;">
				<div class="media-left">
					<a href="#" class="btn text-primary btn-flat btn-rounded btn-icon btn-xs legitRipple"><i class="icon-checkmark3"></i></a>
				</div>
				
				<div class="media-body">
					Los Contribuyentes NRUS no están afectos al IGV.
				</div>
			</li>

		</ul>
	</div>
</div>

<div class="col-lg-4">	
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="icon-address-book mr-2"></i> 
			Dirección
		</label>
		<input type="text" class="form-control form-control-sm" name="direccion" id="direccion" placeholder="Dirección">
	</div>	
</div>
<div class="col-lg-4">
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="icon-office mr-2"></i> 
			Urbanización
		</label>
		<input type="text" class="form-control form-control-sm" name="urbanizacion" id="urbanizacion" placeholder="Urbanizacion">
	</div>
</div>
<div class="col-lg-4">	
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
			Ubigeo
		</label>
		<select class="select_ubigeo" name="ubigeo" id="ubigeo">
			<?php                                    
				foreach ($ubigeo as $ubicacion) {
					echo "<option value='".$ubicacion->codigo_ubigeo."'>".$ubicacion->departamento.' - '.$ubicacion->provincia.' - '.$ubicacion->distrito."</option>";
				}
			?>
		</select>
	</div>
</div>
<div class="col-lg-4">
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="icon-phone2 mr-2"></i> 
			Teléfono
		</label>
		<input type="text" class="form-control form-control-sm" name="telefono" id="telefono"  placeholder="Telefono">
	</div>
</div>
<div class="col-lg-4">
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="icon-envelop mr-2"></i>
			Email
		</label>
		<input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="Email">
	</div>
</div>
<div class="col-lg-4">
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="icon-earth mr-2"></i>
			WebSite
		</label>
		<input type="text" class="form-control form-control-sm" name="sitioweb" id="sitioweb" placeholder="Sitio Web">
	</div>
</div>
<div class="col-lg-12">
	<div class="form-group">
		<label for="ruc" class="label-form">
			<i class="icon-pencil7 mr-2"></i>
			Información Adicional
		</label>
		<textarea class="form-control" name="info_adicional" id="info_adicional" cols="30" rows="4" maxlength="250"></textarea>
	</div>
</div>
<div class="col-lg-12 text-right">
	<button class="btn bg-indigo legitRipple btn_branchoffice" type="button">
		<i class="icon-floppy-disk mr-2"></i>
		Guardar Todos los Datos!
	</button>
</div>
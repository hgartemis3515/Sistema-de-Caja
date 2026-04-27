<style>
.items-explain {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
}

.items-explain li {
	float: left;
}

.items-explain li a {
	display: block;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
}

</style>
<h1 class="text-center">Guía de la reforma. By: Isaacnia Majano</h1>
<div class="content">
	<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 20px auto;">
		<div class="panel-body">
			<fieldset class="content-group">
				<legend class="text-bold">
					<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
					<span class="font-weight-bold text-uppercase">Botones</span>
				</legend>
			</fieldset>
			<p>A partir de ahora, todos los botones tienen degradados. Como te dije antes, ya no usarás más "btn bg-indigo", para usar el botón morado. Si sigue siendo el mismo color, pero ahora usarás el <code><span class="font-weight-bold">btn btn-primary, bg-primary</span></code></p>
			<h2>Botones</h2>
			<ul class="items-explain">
				<li><button class="btn btn-primary mr-2">btn btn-primary</button></li>
				<li><button class="btn btn-danger mr-2">btn btn-danger</button></li>
				<li><button class="btn btn-success  mr-2">btn btn-success</button></li>
				<li><button class="btn btn-warning mr-2">btn btn-warning</button></li>
				<li><button class="btn btn-info mr-2">btn btn-info</button></li>
			</ul>
			<hr>
			<h2>Background</h2>
			<ul class="items-explain">
				<li><span class="bg-primary mr-2 p-2">bg-primary</span></li>
				<li><span class="bg-danger mr-2 p-2">bg-danger</span></li>
				<li><span class="bg-success  mr-2 p-2">bg-success</span></li>
				<li><span class="bg-warning mr-2 p-2">bg-warning</span></li>
				<li><span class="bg-info mr-2 p-2">bg-info</span></li>
			</ul>
		</div>
	</div>
	<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 20px auto;">
		<div class="panel-body">
			<fieldset class="content-group">
				<legend class="text-bold">
					<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
					<span class="font-weight-bold text-uppercase">Botones con iconos</span>
				</legend>
			</fieldset>
			<p>Para estos grupos, tienes que agregar la clase <code>btn-options</code> al <code>div</code> que es el contenedor. Estos botones son más grandes, según el padding que tú les asignaste en la pantalla "Lista de productos", y lo hice una clase en el CSS para que no tenga que pegarse esa clase en todas las pantallas</p>
			<h2>Grupos de botones</h2>
			<div class="col-md-12 btn-options text-aling">
				<button type="button" class="btn btn-primary btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple mr-2">
					<b><i class="icon-pencil3"></i></b> btn btn-primary
				</button>
				<button type="button" class="btn btn-success btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple mr-2">
					<b><i class="icon-plus-circle2"></i></b> btn btn-success
				</button>
				<button type="button"  class="btn btn-danger btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple mr-2">
					<b><i class="icon-minus-circle2"></i></b> btn btn-danger
				</button>
				<button type="button" class="btn btn-info btn-labeled btn-xs mx-1 text-uppercase font-weight-bold legitRipple mr-2">
					<b><i class="icon-minus-circle2"></i></b> btn btn-info
				</button>
			</div>
		</div>
	</div>
	<div class="panel border-top-indigo"  style="max-width: 1120px;margin: 20px auto;">
		<div class="panel-body">
			<fieldset class="content-group">
				<legend class="text-bold">
					<i class="icon-pencil5 mr-2" aria-hidden="true"></i>
					<span class="font-weight-bold text-uppercase">Select + input</span>
				</legend>
			</fieldset>
			<p>Para estos grupos, tienes que agregar la clase <code>input-select2</code> al <code>div.input-group</code> que es el contenedor.</p>
			<div class="input-group input-select2">
				<select class="js-example-basic-single" name="ot1" id="ot1">
					<option selected value="">Opción 1</option>
					<option value="">Opción 2</option>
					<option value="">Opción 2</option>
					<option value="">Opción 3</option>
				</select>
				<span class="input-group-btn">
					<button class="btn bg-indigo legitRipple" type="button" data-toggle="modal" data-target="#new_cliente">
						<i class="icon-plus-circle2 mr-2"></i> Agregar Nuevo Cliente
					</button>
				</span>
			</div>
		</div>
	</div>
</div>

<script>
	$('.js-example-basic-single').select2();
</script>
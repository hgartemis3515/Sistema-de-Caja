<div class="col-lg-7">
	<div class="tabbable">
		<ul class="nav nav-xs nav-tabs nav-tabs-solid nav-tabs-component">
			<li class="active"><a href="#tab_info_adicional" data-toggle="tab">Información Adicional </a></li>
			<li><a href="#tab_detraccion" data-toggle="tab">Detracción</a></li>
			<li><a href="#tab_percepcion" data-toggle="tab">Percepción</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="tab_info_adicional">
				{{ partial('documentoelectronico/tab_informacion_adicional') }}
			</div>

			<div class="tab-pane" id="tab_detraccion">
				{{ partial('documentoelectronico/tab_detraccion') }}
			</div>
			
			<div class="tab-pane" id="tab_percepcion">
				{{ partial('documentoelectronico/tab_percepcion') }}
			</div>
		</div>
	</div>
</div>
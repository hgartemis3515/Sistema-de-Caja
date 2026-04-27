<?php
class RegistrarseController extends ControllerBase {
	
    public function indexAction() {
		$this->cookies->set('registro_negocio', 'servicio_facturacion', time() + 15 * 86400);
		header('Location: https://arpsystem.com.pe/facturacionv8/registro');
	}
}
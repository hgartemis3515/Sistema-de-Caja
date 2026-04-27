<?php
class PruebaguionController extends ControllerBase
{
	public function indexAction() {
		$this->view->disable();
		echo "hola";
		exit();
	}
}
?>
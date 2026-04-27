<?php
require $_SERVER["DOCUMENT_ROOT"]."/facturacionv8/apis/snappypdf/vendor/autoload.php";
use Knp\Snappy\Pdf;
class ThemespdfController extends ControllerBase {
	public function indexAction() {
		$this->view->setTemplateAfter('vacio_total');
		$this->view->template = 4;
	}
}
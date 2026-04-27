<?php
class RegisterController extends ControllerBase {
	
    public function indexAction() {
		header('Location: /facturacionv8/login?accion=register');
	}
}
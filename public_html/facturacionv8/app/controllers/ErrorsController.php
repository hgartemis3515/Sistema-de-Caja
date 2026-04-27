<?php

class ErrorsController extends ControllerBase
{
    public function show404Action() {
		$this->view->setTemplateAfter("vacio");
		$this->setTitle('Oops!');
    }

    public function show401Action()
    {
		$this->view->setTemplateAfter("vacio");
		$this->setTitle('Oops!');
    }

    public function show403Action()
    {
		$this->view->setTemplateAfter("vacio");
		$this->setTitle('Oops!');
		$this->view->disable();
		echo "Error 403";
		exit();
    }

    public function show500Action()
    {
		$this->view->setTemplateAfter("vacio");
		$this->setTitle('Oops!');
    }
}
?>
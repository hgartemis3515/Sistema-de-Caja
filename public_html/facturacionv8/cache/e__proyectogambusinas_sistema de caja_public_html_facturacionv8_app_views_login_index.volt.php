<?php
	if(isset($id_plantilla_login) && $id_plantilla_login == 1){ ?>
		<?= $this->partial('login/login_01') ?>
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 2){ ?>
		<?= $this->partial('login/login_03') ?>
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 3){ ?>
		<?= $this->partial('login/login_04') ?>
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 4){ ?>
		<?= $this->partial('login/login_05') ?>
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 5){ ?>
		<?= $this->partial('login/login_06') ?>
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 12){ ?>
		<?= $this->partial('login/login_07') ?> 
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 13){ ?>
		<?= $this->partial('login/login_02') ?> 
<?php	} else { ?>
		<?= $this->partial('login/login_01') ?>
<?php	} ?>
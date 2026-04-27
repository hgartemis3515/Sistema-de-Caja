
<?php
	if(isset($id_plantilla_registro) && $id_plantilla_registro == 14){ ?>
	<?= $this->partial('registro/registro_01') ?>
<?php }else if(isset($id_plantilla_registro) && $id_plantilla_registro == 6){ ?>
	<?= $this->partial('registro/registro_03') ?>
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 7){ ?>
	<?= $this->partial('registro/registro_02') ?>
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 8){ ?>
	<?= $this->partial('registro/registro_04') ?>
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 9){ ?>
	<?= $this->partial('registro/registro_05') ?>
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 10){ ?>
	<?= $this->partial('registro/registro_06') ?>
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 11){ ?>
	<?= $this->partial('registro/registro_07') ?> 
<?php	} else { ?>
	<?= $this->partial('registro/registro_01') ?>
<?php	} ?>
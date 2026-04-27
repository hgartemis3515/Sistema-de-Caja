
<?php
	if(isset($id_plantilla_registro) && $id_plantilla_registro == 14){ ?>
	{{ partial('registro/registro_01') }}
<?php }else if(isset($id_plantilla_registro) && $id_plantilla_registro == 6){ ?>
	{{ partial('registro/registro_03')}}
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 7){ ?>
	{{ partial('registro/registro_02')}}
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 8){ ?>
	{{ partial('registro/registro_04')}}
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 9){ ?>
	{{ partial('registro/registro_05')}}
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 10){ ?>
	{{ partial('registro/registro_06')}}
<?php	}else if(isset($id_plantilla_registro) && $id_plantilla_registro == 11){ ?>
	{{ partial('registro/registro_07')}} 
<?php	} else { ?>
	{{ partial('registro/registro_01') }}
<?php	} ?>
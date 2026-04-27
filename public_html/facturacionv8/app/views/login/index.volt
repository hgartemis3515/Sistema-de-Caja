<?php
	if(isset($id_plantilla_login) && $id_plantilla_login == 1){ ?>
		{{ partial('login/login_01')}}
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 2){ ?>
		{{ partial('login/login_03')}}
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 3){ ?>
		{{ partial('login/login_04')}}
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 4){ ?>
		{{ partial('login/login_05')}}
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 5){ ?>
		{{ partial('login/login_06')}}
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 12){ ?>
		{{ partial('login/login_07')}} 
<?php	}else if(isset($id_plantilla_login) && $id_plantilla_login == 13){ ?>
		{{ partial('login/login_02')}} 
<?php	} else { ?>
		{{ partial('login/login_01') }}
<?php	} ?>
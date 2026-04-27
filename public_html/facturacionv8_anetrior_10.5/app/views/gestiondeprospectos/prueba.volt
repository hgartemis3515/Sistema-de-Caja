<style>
    .hijo {
    width: 500px;
    background: #fff;
    box-shadow: 3px 3px 15px rgba(0,0,0,.1);
    border-radius: 10px;
    padding: 20px;
    }
    .float-right
    {
        float: right;
    }
    .card
        {
            background-color: #fff;
            padding: 2em;
            border-radius: 20px;
            margin: 1em;
            width: 530px;
        }
    
        .card-circle
        {
            background-color: #fff;
            padding: 2em;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin-top: 3em;
            margin-left: 5.5em;
            /*margin: 1em;
            margin-left: 6em;*/
            padding-top: 3em;
            padding-left: 5em;
            padding-right: 5em;
            width: 350px;
            height: 350px;
        }
        .card-circle-result
        {
            background-color: #fff;
            padding: 2em;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin-top: 3em;
            margin-left: 5.5em;
            /*margin: 1em;
            margin-left: 6em;*/
            padding-top: 3em;
            padding-left: 5em;
            padding-right: 5em;
            width: 400px;
            height: 400px;
        }
        .margin-top
        {
            margin-top: 10em;
        }
        .m-t-5
        {
            margin-top: 10px;
        }
        .card-circle-4
        {
            background-color: #fff;
            padding: 2em;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin: 1em;
            padding-top: 7em;
            padding-left: 5em;
            padding-right: 5em;
            width: 200px;
            height: 200px;
        }
        .card-circle-3
        {
            background-color: #563d7c;
            padding: 2em;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin: 1em;
            width: 150px;
            height: 150px;
        }
        .card-circle-2
        {
            background-color: #f480ef;
            padding: 2em;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin-left: 5em;
            margin: 1em;
            width: 100px;
            height: 100px;
        }
        .card-circle-1
        {
            background-color: #00BCD4;
            padding: 2em;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin: 1em;
            width: 50px;
            height: 50px;
        }
        .card-circle-0
        {
            background-color: #563d7c;
            padding: 2em;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin: 1em;
            width: 30px;
            height: 30px;
        }
        .body-card
        {
            display: flex;
              justify-content: center;
        }
        .card-circle-min
        {
            background-color: #f480ef;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            margin: 1em;
            width: 20px;
            height: 20px;
            margin: 0px;
            margin-bottom: 5px;
        }
        .btn-pink:focus, .btn-pink.focus, .btn-pink:hover {
        background-color: #f480ef;
        color: #fff;
        }
        .btn-pink {
        color: #fff;
        background-color: #f480ef;
        border-color: #f480ef;
    }
    .btn-sm, .btn-group-sm > .btn {
        padding: 5px 10px;
    }
    .btn-sm, .btn-group-sm > .btn {
        padding: 7px 14px;
        font-size: 10px;
        line-height: 1.6666667;
        border-radius: 2px;
    }
    .text-white
    {
        color: #fff;
    }
</style>
<hr>
<div class="container">	
    <div class="row">
        <div class="col-md-3">
            <div class="card-circle-1" style="margin-left: 13em;">
                <div class="card-body text-center"></div>
            </div>
            <div class="card-circle-0" style="margin-left: 13em;">
                <div class="card-body text-center"></div>
            </div>
            <div class="card-circle-min" style="margin-left: 13em;">
                <div class="card-body text-center"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="center_box box_insertar">
                <div class="hijo">
                    <h4 class="text-center">Introduce los siguientes datos</h4>
                    <form id="miform" method="post" name="miform" >
                        <table id="tblprod" class="table">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="form-group col-lg-4">
                                        <label>Examen</label>
                                        <input class="form-control validate[required]"name="examen[]" />
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Sexo</label>
                                        <select class="form-control validate[required]" name="sexo[]">
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Estado civil</label>
                                        <select class="form-control validate[required]" name="estadocivil[]">
                                            <option value="S">Soltero</option>
                                            <option value="C">Casado</option>
                                            <option value="D">Divorciado</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button id="btnadd" class="btn btn-pink">Agregar Nuevo</button>
                        <button id="btnsubmit" type="submit" class="btn btn-info float-right">Guardar</button>
                    </form>
                </div>
            </div>
            <div class="center_box box_resultado">
                <div class="hijo">
                    <h4 class="text-center num_f_m"></h4>
                    <hr>
                    <h4 class="text-center num_f_m_aprob"></h4>
                    <hr>
                    <h4 class="text-center num_m_ca"></h4>
                    <h4 class="text-center num_m_cd"></h4>
                    <hr>
                    <h4 class="text-center num_hca"></h4>
                    <hr>
                    <h4 class="text-center num_fs"></h4>
                    <hr>
                    <h4 class="text-center num_fd"></h4>
                    <hr>
                    <h4 class="text-center total_desapro"></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-circle-1">
                <div class="card-body text-center"></div>
            </div>
            <div class="card-circle-0">
                <div class="card-body text-center"></div>
            </div>
            <div class="card-circle-min" style="margin-left: 3em;">
                <div class="card-body text-center"></div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
	$(".box_resultado").hide();
    var count = 1;
    $(document).on("click","#btnadd",function( event ) {  
	  count++;
      $('#tblprod tr:last').after('<tr><td><div class="form-group col-lg-4"><label>Examen</label><input class="form-control validate[required]" name="examen[]" /></div><div class="form-group col-lg-4"><label>Sexo</label><select class="form-control validate[required]" name="sexo[]"><option value="M">Masculino</option><option value="F">Femenino</option></select></div><div class="form-group col-lg-4"><label>Estado civil</label><select class="form-control validate[required]" name="estadocivil[]"><option value="S">Soltero</option><option value="C">Casado</option><option value="D">Divorciado</option></select></div></td></tr>');
      event.preventDefault();
   });
   $( "#miform" ).submit(function( event ) {
	  var formulario = $(this).serialize();
			$.ajax({
				type: 'post',
				url : '/ejemplosphp/ejercicio32/ingresar',
				data: formulario,
				success: function(data){
					if(data.respuesta == 'ok')
					{
						$(".box_insertar").hide();
						$(".box_resultado").show(500);
						$(".num_f_m").html(data.num_f_m);
						$(".num_f_m_aprob").html(data.num_f_m_aprob);
						$(".num_m_ca").html(data.num_m_ca);
						$(".num_m_cd").html(data.num_m_cd);
						$(".num_hca").html(data.num_hca);
						$(".num_fs").html(data.num_fs);
						$(".num_fd").html(data.num_fd);
						$(".total_desapro").html(data.total_desapro);
					}
				
					else
					{
					   swal({   
							title: 'Error',   
							text: data.mensaje,
							html: true,
							type: "error",   
							confirmButtonColor: "#563d7c",   
							confirmButtonText: "OK"
						});
					}
					},
				dataType: "json"
			});
	  
	  event.preventDefault();
	});
});
</script>
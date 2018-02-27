<?php
/*
//Pagina de inicio
*/
	set_time_limit(900);
	$db="YOUR_DATABSE";
	$conexion=mysql_connect("localhost","YOU_USER","YOUR_PASSWORD") or die ("Error de conexion a la base de datos").mysql_error();
	mysql_select_db($db,$conexion) or die ("Error de conexion a la base de datos");
	date_default_timezone_set('America/Mexico_City');

	//query para traer todos los registros de la tabla documentos que seran mostrados en la tabla
	$archivos=mysql_query("SELECT * FROM documentos ORDER BY Id_documento DESC",$conexion); //consulta original
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Plataforma para apoyo de EVA</title>
	<meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href='https://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
 
  <!--plugin Datatable-->
  <link rel="stylesheet" href="tabla/css/dataTables.bootstrap.css">
</head>
<body>

	<div class="row" style="margin-top:50px;">
	  <div class="container">
	    <div class="col-md-12"> <!--contenedor principal-->
        <div class="panel panel-default">
	        <div class="panel-body">
            <h4 align="center"><strong>Plataforma EVA para Estudiantes y Docentes</strong></h4>
	            <form id="frmUpload" name="archivo" method="post" enctype="multipart/form-data">
	            	<div class="container">  <!--contenedor con botones-->
		           		<div class="col-md-5" style="margin-top:11px;">
		           			<input type="file" class="filestyle" data-icon="false" data-buttonText="Seleccionar archivo" name="image" id="archivo" multiple="multiple" style="margin-top:15px;">
		           		</div>
	            		<div class="col-md-1"></div>
	            		<div class="col-md-2">
	            			<input type="submit" name="btnEnviar" id="Enviar" class="btn btn-danger" value="Guadar archivo" style="margin-bottom:25px; margin-top:10px;">
	            		</div>
                  <div class="col-md-3" style="margin-top:10px;"> <!--Area de busqueda-->
                    <form class="search-form" > 
                      <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="txtBuscar" id="busqueda" placeholder="Buscar"/>
                      </div>
                    </form>
                  </div>
                </div>
                <div style="clear:both"></div>
            	</form>
            	<!--contenedor con la tabla de archivos -->
     					<div class="table-responsive" id="tableta">
     						<table class="table table-bordered table-hover" id="tablet">
     							<thead class="danger">
     								<tr>
     									<th class="active" width="10%">#</th>
     									<th class="active" width="50%">Nombre de archivo</th>
     									<th class="active" width="20%">Seleccionar</th>
     									<th class="active" width="20%">Eliminar</th>
     								</tr>
     							</thead>
   								<?php while ($tabla=mysql_fetch_array($archivos)) {?>
     								<tr>
     									<td><?php echo $tabla['0']; ?></td>
     									<td><?php echo $tabla['1']; ?></td>
     									<td><a href="procesados.php?ID=<?php echo $tabla['0'];?>">Seleccionar</a></td>
     									<td><a href="confirmacion.php?var=<?php echo $tabla['0'];?>">Eliminar</a></td>
     								</tr>
   								<?php } ?>
     						</table>
     					</div>
       		</div>
	     	</div>
	  	</div>
    </div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/notify.min.js"></script>
  <script src="js/bootstrap-filestyle.min.js"></script> <!--http://markusslima.github.io/bootstrap-filestyle/#Methods-->

  <!--plugin del data table-->
  <script src="tabla/js/jquery.dataTables.min.js"></script>
  <script src="tabla/js/dataTables.bootstrap.min.js"></script>

  	<script type="text/javascript">
  		$(document).ready(function(){
        $(":file").filestyle(); //Personalizando el boton de archivos
        $("#busqueda").focus(); 
  			$('#frmUpload').on('submit', function(e){
  				/*var archivos=document.getElementById("archivo");
  				//var numarchivos=archivos.files;
  				//for (var i=0;i<numarchivos.length;i++) {
  				//	archivos.append('numarchivos'+i,numarchivos[i]);
  				}*/
  				e.preventDefault();
  				$.ajax({
  					url:'procesar.php',
  					method:'POST',
  					data:new FormData(this),
  					contentType:false,
  					cache:false,
  					processData:false,
  					success:function(data){
  						if (data=="info1") {
                $.notify("Seleccione un archivo!!!", "error");
  							$('#archivo').focus();
  						}else if (data=="info2") {
  							$(":file").filestyle('clear'); //Limpiando el boton de archivos
  							$.notify("Tipo de archivo no valido!!", "warn");
  							$('#archivo').focus();
  						}else{
  							$(":file").filestyle('clear'); //Limpiando el boton de archivos
  							$.notify("Correcto!!!", "success");
                $("#busqueda").focus();
  							$('#tableta').html(data);
  						}
  					}
  				});
  			});

        //Seccion de busqueda
        var consulta;
        $("#busqueda").focus(); //campo de búsqueda
        $("#busqueda").keyup(function(e){ //comprobamos si se pulsa una tecla
        consulta = $("#busqueda").val(); //obtenemos el texto introducido en el campo de búsqueda
            $.ajax({ //enviarlos parametros
                type: "POST",
                url: "busqueda.php",
                data: "b="+consulta,
                dataType: "html",
                error: function(){
                    alert("error petición ajax");
                },
                success: function(data){
                    $("#tableta").empty();
                    $("#tableta").append(data);
                }
            });
        });

  		});
  	</script>
</body>
</html>

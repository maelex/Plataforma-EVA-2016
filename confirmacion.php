<?php
	set_time_limit(300);
	$db="Plataforma";
	$conexion=mysql_connect("localhost","root","showcandela") or die ("Error de conexion a la base de datos").mysql_error();
	mysql_select_db($db,$conexion) or die ("Error de conexion a la base de datos");


	/*echo $_REQUEST['$var'];
	$elemento=mysql_query("SELECT documentos.Nombre, documentos.id_documento, csv_registros.Fecha_doc,
		registros_viejos.Ip_direccion, registros_viejos.Fecha_reg from documentos
		INNER JOIN csv_registros ON documentos.Id_documento=csv_registros.id_documento
		INNER JOIN registros_viejos ON csv_registros.Id_dato=registros_viejos.Id_dato
	where documentos.Id_documento='$borrar'",$conexion);*/
	$recibo=$_GET["var"];
	if (is_null($recibo)) {
		echo "<script> alert('Acceso denegado, debe elegir un archivo!!!');</script>";
		mysql_close($conexion);
    	echo "<script>location.href='Index.php'</script>";
	}else{

	//select que llena la tabla con la info del registro seleccionado y que se guarda en la variable recibo
	$informante=mysql_query("SELECT documentos.Nombre, documentos.Id_documento, csv_registros.Fecha_doc,
	registros_viejos.Ip_direccion, registros_viejos.Fecha_reg, registros_viejos.Id_dato from documentos
	INNER JOIN csv_registros ON documentos.Id_documento=csv_registros.Id_documento
	INNER JOIN registros_viejos ON csv_registros.Id_dato=registros_viejos.Id_dato
	where documentos.Id_documento='$recibo' limit 600",$conexion);
	

	//Eliminar el registro de la base de datos en las tablas csv_registros, documentos y registros_viejos asi mismo el documento guardado en la carpeta DOcumentosCSV

	//primero y ultimo recogen tanto el primer como el ultimo ID de la tabla registros viejos para tener un rango exacto al momento de liminar en la tabla de registros_viejos, pero el valor real esta en las variables start y endd
	$primero=mysql_query("SELECT Id_dato from registros_viejos where Num_documento='$recibo' ORDER BY Id_dato ASC LIMIT 1",$conexion);
	$inicio=mysql_fetch_array($primero);
	$start=$inicio['Id_dato'];
	$ultimo=mysql_query("SELECT Id_dato from registros_viejos where Num_documento='$recibo' ORDER BY Id_dato DESC LIMIT 1",$conexion);
	$final=mysql_fetch_array($ultimo);
	$endd=$final['Id_dato'];
	//query para eliminar elarchivo en la carpeta DocumentosCSV
	$archivo=mysql_query("SELECT Nombre from documentos where Id_documento='$recibo'",$conexion);
	$dat=mysql_fetch_array($archivo);
	$requerido=$dat['Nombre'];
	$ruta="DocumentosCSV/".$requerido.".csv";
	if (isset($_POST['btnEliminar'])) {
		mysql_query("SET FOREIGN_KEY_CHECKS=0",$conexion);
		$docm=mysql_query("DELETE from documentos where Id_documento='$recibo'",$conexion);
		$csvs=mysql_query("DELETE from csv_registros where Id_documento='$recibo'",$conexion);
		$regs=mysql_query("DELETE from registros_viejos where Id_dato>='$start' and Id_dato<='$endd'",$conexion);
		unlink($ruta);
		echo "<script> alert('Correcto, hemos eliminado todo!!!');</script>";
		echo "<script>location.href='Index.php'</script>";
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Plataforma para apoyo de EVA: Eliminar</title>
	<meta charset="utf-8"/>
  	<meta name="viewport" content="width=device-width, initial-scale=1.0 user‐scalable=no">


  	<link href='https://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

	<!--plugin del data table-->
  	<link rel="stylesheet" href="tabla/css/dataTables.bootstrap.css">

</head>
<body>
	
	<div class="row" style="margin-top:50px;">
		<div class="container">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="form-group">
							<h4 align="center" class="lead"><strong><i class="fa fa-hand-paper-o" aria-hidden="true"></i> Eliminar elemento</strong></h4>
						</div>
						<form action="" method="post" align="center"> <!-- fomulario de confirmacion -->
							<div class="form-group">
								<label for="eliminar">¿Realmente desea eliminar el documento con el ID <?php echo $_GET["var"];?>?</label>
							</div>
							<div class="form-group">
								<input type="submit" name="btnEliminar" class="btn btn-success" value="Eliminar registro"></input>
								<a href="Index.php"  class="btn btn-warning" role="button">Regresar</a>
							</div>
						</form>
						<div class="form-group" align="center">
							<h4><small><strong>*Tal vez quieras dar un pequeño vitazo a la informacion que contiene este archivo</strong></small></h4>
						</div>
						<div class="form-group">
							<div class="table-responsive">
						        <table class="table table-bordered table-hover" id="contenido">
						           	<thead class="danger">
						            	<tr>
						               	<th class="active" width="10%"><strong># Documento</strong></th>
						               	<th class="active" width="30%"><strong>Documento</strong></th>
						               	<th class="active" width="30%"><strong>Direccion IP</strong></th>
						               	<th class="active" width="30%"><strong>Fecha</strong></th>
						           		</tr>
						         	</thead>
						         		<?php while ($vizta=mysql_fetch_array($informante)) {?>
										<tr>
											<td><?php echo $vizta['Id_documento']; ?></td>
											<td><?php echo $vizta['Nombre']; ?></td>
											<td><?php echo $vizta['Ip_direccion']; ?></td>
											<td><?php echo $vizta['Fecha_reg']; ?></td>
										</tr>
										<?php } ?>
						    	</table>
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  	<script src="js/jquery.js"></script>
  	<script src="js/bootstrap.min.js"></script>

  	<!--plugin del data table-->
  	<script src="tabla/js/jquery.dataTables.min.js"></script>
	<script src="tabla/js/dataTables.bootstrap.min.js"></script>

  	<script>
  		$(document).ready(function(){
    		$('#contenido').DataTable({
    			"lengthChange": false, //elimina un dropdown que permite elegir cuantos items se mostraran
    			"searching": false, //elimina el campo de busqueda
    			"bInfo": false, //elimina un texto debajo de la tabla que dice 10 de x
    			"ordering": false, //elimina poder reordenar
    			"language":{
    				"paginate":{
    					"next": "Siguiente",
    					"previous": "Anterior"
    				},
    			}
    		});
		});

		//https://www.youtube.com/watch?v=PN5p-f2W-3k
		/*Cursos de laravel
https://www.youtube.com/watch?v=togIjDT95wo&list=PLIddmSRJEJ0u-5Nv2k6W8Vhe0wUP_7H5W <-- Curso 1
https://www.youtube.com/watch?v=Zj0pshSSlEo&list=PLZPrWDz1MolrxS1uw-u7PrnK66DCFmhDR <-- Curso 2
		*/
  	</script>
</body>
</html>

<?php
	}
	mysql_close($conexion);
?>

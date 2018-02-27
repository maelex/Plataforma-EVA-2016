<html>
<?php
	set_time_limit(900);
	$db="YOUR_DATABASE";
	$conexion=mysql_connect("localhost","YOUR_USER","YOUR_PASSWORD") or die ("Error de conexion a la base de datos").mysql_error();
	mysql_select_db($db,$conexion) or die ("Error de conexion a la base de datos");
	date_default_timezone_set('America/Mexico_City');

	$buscar = $_POST['b'];

	if (!empty($buscar)) {
		buscar($buscar);
	}else{
		$archivos=mysql_query("SELECT * FROM documentos ORDER BY Id_documento DESC",$conexion); 	
		$out.='
			<table class="table table-bordered table-hover">
				<thead class="danger">
					<tr>
						<th class="active" width="10%">#</th>
						<th class="active" width="50%">Nombre de archivo</th>
						<th class="active" width="20%">Seleccionar</th>
						<th class="active" width="20%">Eliminar</th>
					</tr>
				</thead>
		';
			while ($traidos=mysql_fetch_array($archivos)) {
				$out.='
					<tr>
						<td>'.$traidos["0"].'</td>
						<td>'.$traidos["1"].'</td>
						<td><a href="procesados.php?ID='.$traidos[0].'">Seleccionar</a></td>
						<td><a href="confirmacion.php?var='.$traidos[0].'">Eliminar</a></td>
					</tr>
				';
			}
			$out.='</table>';
			echo $out;
	}

	function buscar($b){
		$con = mysql_connect('localhost','YOUR_USER','YOUR_PASSWORD');
        mysql_select_db('YOUR_DATABSE', $con);
        $archivos=mysql_query("SELECT * FROM documentos where Nombre like '%".$b."%' OR Id_documento like '%".$b."%' ORDER BY Id_documento DESC",$con);

        $contar = mysql_num_rows($archivos);
        $i=0; //contador
        if($contar == 0){
			echo '<div class="col-md-12">';
                echo '<div class="panel panel-default">';
                  echo '<div class="panel-body">';
                     echo "No se han encontrado resultados para '<b>". $b ."</b>'.";
                  echo '</div>';
                echo "</div>";
            echo "</div>";
        }else{	
		$out.='
			<table class="table table-bordered table-hover">
				<thead class="danger">
					<tr>
						<th class="active" width="10%">#</th>
						<th class="active" width="50%">Nombre de archivo</th>
						<th class="active" width="20%">Seleccionar</th>
						<th class="active" width="20%">Eliminar</th>
					</tr>
				</thead>
		';
			while ($traidos=mysql_fetch_array($archivos)) {
				$out.='
					<tr>
						<td>'.$traidos["0"].'</td>
						<td>'.$traidos["1"].'</td>
						<td><a href="procesados.php?ID='.$traidos[0].'">Seleccionar</a></td>
						<td><a href="confirmacion.php?var='.$traidos[0].'">Eliminar</a></td>
					</tr>
				';
			}
			$out.='</table>';
			echo $out;

        }

	}
?>
</html>

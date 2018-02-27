<?php
	set_time_limit(900);
	$db="YOUR_DATABASE";
	$conexion=mysql_connect("localhost","YOUR_USER","YOUR_PASSWORD") or die ("Error de conexion a la base de datos").mysql_error();
	mysql_select_db($db,$conexion) or die ("Error de conexion a la base de datos");
date_default_timezone_set('America/Mexico_City');
	//aqui debo crear la carpeta si no existe y guarda el documento  y si la carpeta existe solo guardar el archivo
	$dir = "DocumentosCSV/";
	if(!file_exists($dir)){
    	mkdir($dir);
	}

	if (empty($_FILES['image']['name'])) {
		mysql_close($conexion);
		echo "info1";
	}else{
		$out=''; //variable vacia que regresa informacion al documento html para sacar los registros de la tabla documentos
		$sel='Seleccionar';
		$eli='Eliminar';
		$nombre = $_FILES['image']['name'];
		$permitido = array("csv");
		$delimita=explode('.', $nombre);
		if (in_array($delimita[1],$permitido)) {
			$temporal=$_FILES['image']['tmp_name'];
			$assignado=fopen($temporal,'r');

			$blanco="_";//d(dia) m(mes) Y(año) G(hora en 24) i(minuto) s(segundo) A (AM/PM) -> Le doy un nuevo formato de nombre a los documentos
			$flow = $dir . date("d-m-Y-G-i-A") .$blanco. basename($_FILES['image']['name']);
			//echo $flow;
			$cortar=explode('/',$flow);
			$cortado=explode('.',$cortar[1]);
			move_uploaded_file($_FILES['image']['tmp_name'], $flow);
			//a la tabla de documentos y obteniendo ese registro para luego meterlo en registros_viejos
			$docu=mysql_query("INSERT INTO documentos values (Id_documento,'$cortado[0]','$delimita[1]')",$conexion);
			$docc=mysql_query("SELECT MAX(Id_documento) from documentos",$conexion);
			$doce=mysql_fetch_array($docc);
			$list=$doce['MAX(Id_documento)'];
			while (($dato=fgetcsv($assignado,1000,","))!==False) {
				//a la de registros_viejos
				$contador++;
				if ($contador>1) {
					$insertar=mysql_query("INSERT INTO registros_viejos values (Id_dato,'$dato[1]','$dato[0]','$list')",$conexion);
				}
			}
			//insertando en la relacion csv_registros
			$conto=mysql_query("SELECT COUNT(Id_dato) from registros_viejos where Num_documento='$list'",$conexion);
			$data=mysql_fetch_array($conto);
			$nume=$data['COUNT(Id_dato)'];
			//echo $nume;
			$dtos=mysql_query("SELECT * FROM registros_viejos where Num_documento='$list'",$conexion);
			$nume=$nume+1; //corregir esta insercion con la variable i
			$car=0;
			for ($i=1; $i < $nume; $i++) {
				$util=mysql_fetch_array($dtos);
				$arre=$util['Id_dato'];
				$csv=mysql_query("INSERT INTO csv_registros values ('$list','$arre',UTC_TIMESTAMP())",$conexion);
			}
			/*while ($kk<=$nume) {
				$csv=mysql_query("INSERT INTO csv_registros values ('$list','$arre',UTC_TIMESTAMP())",$conexion);
			}*/
			$documents=mysql_query("SELECT * FROM documentos ORDER BY Id_documento DESC",$conexion);//consulta original
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
			while ($traidos=mysql_fetch_array($documents)) {
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
		}else{
			echo "info2";
			mysql_close($conexion);
		}
	}
	/*$dinero = 10;
	function suma($dinero){
		$cantidad=7;
		$total=$cantidad*$dinero;
		return $total;
	}
echo suma($dinero);*/

?>

<?php
  set_time_limit(900);
  $db="Plataforma";
  $conexion=mysql_connect("localhost","root","showcandela") or die ("Error de conexion a la base de datos").mysql_error();
  mysql_select_db($db,$conexion) or die ("Error de conexion a la base de datos");
  date_default_timezone_set('America/Mexico_City');

	$recibo=$_GET["ID"]; //recibo guarda el ID del documento sobre el cual se esta trabajando

  $nom=mysql_query("SELECT documentos.Nombre,  documentos.Id_documento from documentos
    where documentos.Id_documento='$recibo'",$conexion);
 	$docu=mysql_fetch_array($nom);
 	$nomdoc=$docu['1']; //numero de documento

  //parte que recoge de la tabla registos_viejos para luego procesar e incertar en la tabla procesados
	$viejos=mysql_query("SELECT * from registros_viejos where Num_documento='$recibo'",$conexion);

	$contar=mysql_query("SELECT COUNT(Id_dato) from registros_viejos where Num_documento='$recibo'",$conexion);
	$numero=mysql_fetch_array($contar);
	$contados=$numero['COUNT(Id_dato)'];
	for ($i=0; $i < $contados; $i++) {
		for ($m=0; $m < $contados; $m++) {
			while ($informacion=mysql_fetch_array($viejos)) {
				$direccion[$i]=$informacion['Ip_direccion'];
				$dire=explode('.',$direccion[$i]);

				$var2[$m]=$informacion['Fecha_reg'];
				$aux=explode(" ", $var2[$m]);
				$dif2=explode(':', $aux[1]);
				//insertando en la tabla de procesados
				$insercion=mysql_query("INSERT INTO procesados VALUES (Id_procesado,'$dire[0]','$dif2[0]','$recibo')",$conexion);

			}
		}
	}

  //insertando en la relacion registros_procesados 
  $datos=mysql_query("SELECT * FROM registros_viejos where Num_documento='$recibo'",$conexion);
  $datos2=mysql_query("SELECT * FROM procesados where Documento_origen='$recibo'",$conexion);
  $maximo=mysql_query("SELECT MAX(Id_procesado) from procesados",$conexion);
  $numero=mysql_fetch_array($maximo);
  $ultimo=$numero['MAX(Id_procesado)'];
  $k=0;
  while ($k<=$contados) {
    $util=mysql_fetch_array($datos);
    $util2=mysql_fetch_array($datos2);
    
    $k=$k+1;
    $arre=$util['Id_dato'];
    $arre2=$util2['Id_procesado'];
    $arre3=$util2['Documento_origen'];

    $inser=mysql_query("INSERT INTO registros_procesados VALUES ('$arre','$arre2')",$conexion);
    
  }
  unset($k); //sgun php limpia la variable

  //query que recoge datos de la tabla Procesados para ser uilizados
  $datosprocesados=mysql_query("SELECT * from procesados where Documento_origen='$recibo'",$conexion);
  $doc=mysql_fetch_array($datosprocesados);
  $sheet=$doc['3'];
  $distinte=mysql_query("SELECT DISTINCT(Direccion_ip) from procesados where Documento_origen='$recibo'",$conexion);
  while ($hilo=mysql_fetch_array($distinte)) { 
    for ($h=0; $h <= 23; $h++) {
      $arreglo[$i]=$hilo['Direccion_ip'];
      $query=mysql_query("SELECT COUNT(Direccion_ip) from procesados where Direccion_ip='$arreglo[$i]' and Hora='$h' and Documento_origen='$recibo'",$conexion);
      $resultado=mysql_fetch_array($query);
      $guarda=$resultado['COUNT(Direccion_ip)'];
      
      //aqui solo estoy guardando en la tabla frecuencias
      if ($guarda>=1) {
      //si el resultado de la consulta es mayor a 1 quiere decir que tengo guardar el resultado
        $frecuency=mysql_query("INSERT INTO frecuencias values (Id_frecuencia,'$arreglo[$i]','$h','$guarda','$sheet')",$conexion);
      }
      unset($guarda);
    } 
  }

  //haciendo la comprobacion de los distintos tipos de ip´s paraser clasificados segun su tipo EXT-INT-MOVIL
  $frecuen=mysql_query("SELECT * FROM Frecuencias where Doc='$recibo'",$conexion);
  $total=mysql_query("SELECT COUNT(Id_frecuencia) from frecuencias where Doc='$recibo'",$conexion);
  $resultante=mysql_fetch_array($total);
  $residuo=$resultante['COUNT(Id_frecuencia)']; //variables que me permiten controlar la cantidad de repeticiones del ciclo para inertar en la relacion de tipo_dato
  $iddoc=$docu['Id_documento'];
  $n=0;
  while ($n<=$residuo) {
    $util=mysql_fetch_array($frecuen);
    $arre=$util['Id_frecuencia'];

    $dirip=$util['Ip'];
    if ($dirip!=148 and $dirip>=10) { //EXTERNAS
        $externa=mysql_query("INSERT INTO tipo_dato VALUES (1,'$arre')",$conexion);
    }elseif ($dirip==148 and $dirip>=10) { //INTERNAS
        $interna=mysql_query("INSERT INTO tipo_dato VALUES (2,'$arre')",$conexion);
    }else{ //MOVILES
        $movil=mysql_query("INSERT INTO tipo_dato VALUES (3,'$arre')",$conexion);
    }

    //insertando en la relacion documentos_frecuencias
    $caldi=mysql_query("INSERT INTO documentos_frecuencias values ('$arre','$iddoc')",$conexion);
    $n=$n+1;
  }
  unset($n);

  //parte de las tablas enlistados y documentos_listas
  for ($i=0; $i <= 23; $i++) {  //ciclo para el manejo de las horaas, posteriormente haciendo los calculos necesarios
    $ext=mysql_query("SELECT SUM(Cantidad) from frecuencias where Doc='$sheet' and hora='$i' and Ip>'10' and Ip!='148'",$conexion);
    $ex=mysql_fetch_array($ext);
    $externas=$ex['SUM(Cantidad)'];

    $int=mysql_query("SELECT SUM(Cantidad) from frecuencias where Doc='$sheet' and hora='$i' and Ip='148'",$conexion);
    $in=mysql_fetch_array($int);
    $internas=$in['SUM(Cantidad)'];

    $mov=mysql_query("SELECT SUM(Cantidad) from frecuencias where Doc='$sheet' and hora='$i' and Ip<='10'",$conexion);
    $mo=mysql_fetch_array($mov);
    $moviles=$mo['SUM(Cantidad)'];

    if ($externas>=1) { //insertando en enlistados
      $inserccion=mysql_query("INSERT INTO enlistados values(Id_enlistado,'$i','$externas','$internas','$moviles','$sheet')",$conexion);
    }
  }

  //obteniendo datos de la tala frecuencias
  $frecuen=mysql_query("SELECT * FROM enlistados where Document='$sheet'",$conexion);
  $contar=mysql_query("SELECT COUNT(Id_enlistado) from enlistados where Document='$sheet'",$conexion);
  $numero=mysql_fetch_array($contar);
  $contados=$numero['COUNT(Id_enlistado)'];         
  $v=0;
  while ($v<=$contados) {
    $util=mysql_fetch_array($frecuen);
    $arre=$util['Id_enlistado'];
              
    $v=$v+1; //incremento la variable

    $mari=mysql_query("INSERT INTO documentos_listas VALUES('$arre','$sheet')",$conexion);
  }
  unset($v);


  // botones para regresar y para trabajar donde elimino de registros_procesados procesados y tipo_procesado
  if(isset($_POST["btnCancelar"])){
    mysql_query("SET FOREIGN_KEY_CHECKS=0",$conexion);
    $elimin=mysql_query("DELETE from registros_procesados",$conexion);//falta agregar un campo que sea un identificador de a donde borrar
    $elim=mysql_query("DELETE FROM procesados where Documento_origen='$recibo'",$conexion);
    $quitar=mysql_query("DELETE from documentos_frecuencias where Id_documento='$recibo'",$conexion);
    $eliminar=mysql_query("DELETE from Frecuencias where Doc='$recibo'",$conexion);
    $eli=mysql_query("DELETE from tipo_dato",$conexion);//falta agregar un campo que sea un identificador de a donde borrar
    $elir=mysql_query("DELETE from documentos_listas",$conexion);//falta agregar un campo que sea un identificador de a donde borrar
    $elim=mysql_query("DELETE FROM enlistados where Document='$recibo'",$conexion);

    echo "<script> alert('¡Correcto!');</script>";
    echo "<script>location.href='Index.php'</script>";
    
  }

?>
<!DOCTYPE html>
<html>
<head>
	<title>Plataforma para apoyo de EVA: Editar</title>
	<meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link href='https://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	 
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  <!--data time picker-->
  <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  
  <!--plugin data table-->
  <link rel="stylesheet" href="tabla/css/dataTables.bootstrap.css">
</head>

<body>
 
  <div class="container" style="margin-top:50px;">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row"> <!--fila 1 que contendra el nombre con el select-->
          <p style="margin-left:15px; margin-bottom:20px;"><strong>Documento: <?php echo $docu['Nombre']; ?></strong></p>
          <form action="">
            <div class="col-md-3">
              <div class="form-group">
                <label for="name">Filtrar por: </label>
                <select class="form-control" id="selectTipo">
                  <option value="0">Mostrar todo</option>
                  <?php $tipo=mysql_query("SELECT * FROM tipos",$conexion);
                  while ($type=mysql_fetch_array($tipo)) { ?>
                  <option value="<?php echo $type['Id_tipo'] ?>"> <?php echo $type['Tipo']; ?></option>
                  <?php } ?>
                </select> 
              </div>
            </div>
          </form>
        </div>
        <div class="row"> <!--fila 2 que contendra la tabla-->
          <div class="col-md-8"> <!--primera columna-->
            <div class="table-responsive" id="tableta">
              <table class="table table-bordered table-hover" id="tabla">
                <thead>
                  <tr>
                    <th class="success" width="25%">Hora del dia</th>
                    <th class="success" width="25%">IP externas</th>
                    <th class="success" width="25%">IP internas</th>
                    <th class="success" width="25%">IP moviles</th>
                  </tr>
                </thead>
                <?php $frecuency=mysql_query("SELECT * from enlistados where Document='$recibo'",$conexion);
                while ($llenatabla=mysql_fetch_array($frecuency)) {?>
                  <tr>
                    <td><?php echo $llenatabla['1']; ?></td>
                    <td><?php echo $llenatabla['2']; ?></td>
                    <td><?php echo $llenatabla['3']; ?></td>
                    <td><?php echo $llenatabla['4']; ?></td>
                  </tr>
                <?php } ?>
              </table>
            <?php
            $t=mysql_query("SELECT COUNT(Id_procesado) FROM procesados where Documento_origen='$recibo'",$conexion);
            $resulton=mysql_fetch_array($t);
            $resi=$resulton['COUNT(Id_procesado)'];    ?>          
            <h4><small><strong><?php echo $resi." Registros analizados"; ?></strong></small></h4>
            </div>
          </div>
          <div class="col-md-4"> <!-- seccion de botones junto al calendario // segundacolumna-->
            <div class="form-group">
              <form class="" id="frmCancelar" action="" method="post" align="right" style="float: left;margin-right:10px;">
                <input type="submit" name="btnCancelar" id="Cancelar" class="btn btn-danger" value="Descartar">
              </form>
              <a href="documento.php?enviar=<?php echo $recibo; ?>" id="btnDescargar" class="btn btn-primary" role="button" style="float: left; margin-right:10px;">Descargar</a>
              <a href="Index.php"  class="btn btn-success" role="button">Ir a inicio</a> 
            </div>
            <div style="overflow:hidden;"><!--seccion del calendario-->
              <br> <div id="datetimepicker12"></div>
            </div> <!--fin del calendario-->
          </div> <!-- termina la seccion de botones junto al calendario-->
        </div>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/notify.min.js"></script>
  <!-- data time-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment-with-locales.js"></script>
  <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
  
  <!--plugin del data table-->
  <script src="tabla/js/jquery.dataTables.min.js"></script>
  <script src="tabla/js/dataTables.bootstrap.min.js"></script>
  
  <script>
    //selector para el cambio de la tabla
    $('#selectTipo').change(function() {
      var file = <?php echo $recibo ?>;
      var demo=$("#selectTipo").val();
      //alert('A las '+hora+' La hoja es la '+demo+' con '+file); 
      $.post("trabajardatos.php",{dato1: file, dato2: demo},function(htmlexterno){
        $("#tableta").html(htmlexterno);
      });
    });

    //script para el calendario
    $(function () {
      $('#datetimepicker12').datetimepicker({
        inline: true,
        toolbarPlacement:'bottom',
        locale:'es-do',
        sideBySide: false
      });
    });

    /*reportes
    http://www.codedrinks.com/crear-un-reporte-en-excel-con-php-y-mysql/
    http://comunidad.fware.pro/dev/php/como-crear-verdaderos-archivos-de-excel-usando-phpexcel/
    */
  </script>

</body>
</html>

<html>
<head>
  <title>sos</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href='https://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  
</head>
<body>
<?php
  set_time_limit(900);
  $db="Plataforma";
  $conexion=mysql_connect("localhost","YOUR_USER","YOUR_PASSWORD") or die ("Error de conexion a la base de datos").mysql_error();
  mysql_select_db($db,$conexion) or die ("Error de conexion a la base de datos");
  date_default_timezone_set('America/Mexico_City');

  //recibimos los parametros enviados desde procesados.php con el metodo post de jquery
  $numHoja = $_POST['dato1']; //variablke que guarda el numero de la hoja que se esta trabajando
  $numTipo = $_POST['dato2']; //propiedad Value del select 
  
  //haciendo los calculos de la frecuencia
  $distinte=mysql_query("SELECT DISTINCT(Direccion_ip) from procesados where Documento_origen='$numHoja'",$conexion);
  $distin=mysql_query("SELECT DISTINCT(Hora) from procesados where Documento_origen='$numHoja'",$conexion);
    
  $total=mysql_query("SELECT COUNT(Id_procesado) from procesados where Documento_origen='$numHoja'",$conexion);

?>

  <!--primera columna-->
  <?php if ($numTipo==1) { //Externas ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="success" width="25%">Hora del dia</th>
          <th class="success" width="25%">IP externas</th>
        </tr>
      </thead>
      <?php //$frecuency=mysql_query("SELECT * from frecuencias where Ip!='148' and Ip>='10' and Doc='$numHoja'",$conexion); antiguo query
      $frecuency=mysql_query("SELECT * from enlistados where Document='$numHoja'",$conexion);
      while ($llenatabla=mysql_fetch_array($frecuency)) {?>
        <tr>
          <td><?php echo $llenatabla['1']; ?></td>
          <td><?php echo $llenatabla['2']; ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>  
  <?php }elseif ($numTipo==2) { //Internas ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="success" width="25%">Hora del dia</th>
          <th class="success" width="25%">IP internas</th>
        </tr>
      </thead>
      <?php //$frecuency=mysql_query("SELECT * from frecuencias where Ip='148' and Ip>='10' and Doc='$numHoja'",$conexion);
      $frecuency=mysql_query("SELECT * from enlistados where Document='$numHoja'",$conexion);
      while ($llenatabla=mysql_fetch_array($frecuency)) {?>
        <tr>
          <td><?php echo $llenatabla['1']; ?></td>
          <td><?php echo $llenatabla['3']; ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>
  <?php }elseif ($numTipo==3) { //Moviles ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="success" width="25%">Hora del dia</th>
          <th class="success" width="25%">IP moviles</th>
        </tr>
      </thead>
      <?php 
      $frecuency=mysql_query("SELECT * from enlistados where Document='$numHoja'",$conexion);
      //$data=mysql_fetch_row($frecuency);
      //if ($data==0) {  ?>
       <!-- <tr>
          <td colspan="3">Cero coincidencias</td>
        </tr>  -->
      <?php // } ?>
      <?php
      while ($llenatabla=mysql_fetch_array($frecuency)) { ?>
          <tr>
            <td><?php echo $llenatabla['1']; ?></td>
            <td><?php echo $llenatabla['4']; ?></td>
          </tr>
        <?php  } ?>
    </table>
  </div>
  <?php }elseif ($numTipo==0) { //cuando es cero se muestra toda la tabla  de freucencias ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="success" width="25%">Hora del dia</th>
          <th class="success" width="25%">IP externas</th>
          <th class="success" width="25%">IP internas</th>
          <th class="success" width="25%">IP moviles</th>
        </tr>
      </thead>
      <?php $frecuency=mysql_query("SELECT * from enlistados where Document='$numHoja'",$conexion);
      while ($llenatabla=mysql_fetch_array($frecuency)) {?>
        <tr>
          <td><?php echo $llenatabla['1']; ?></td>
          <td><?php echo $llenatabla['2']; ?></td>
          <td><?php echo $llenatabla['3']; ?></td>
          <td><?php echo $llenatabla['4']; ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
  
       
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script>

  </script>

</body>
</html>

<?php
	//conexion
	$conexion = new mysqli('localhost','root','showcandela','plataforma');
	if (mysqli_connect_errno()) {
   		printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
   		exit();
	}
	set_time_limit(900);
	date_default_timezone_set('America/Mexico_City');
 
	$esencia=$_GET["enviar"]; //recibimos el ID y hacemos la consulta

	$consulta="SELECT * FROM enlistados where Document='$esencia' ORDER BY Hour ASC";
	$resultado=$conexion->query($consulta);
	//segunda consulta a documentos
	$configuracion="SELECT * FROM documentos where Id_documento='$esencia'";
	$rows=$conexion->query($configuracion);
	$nombre=$rows->fetch_array(MYSQLI_NUM);
	$nomcompleto="Reporte_".$nombre[1].".xlsx"; //nombre del archivo final que se descargará
	//referencia -> http://php.net/manual/en/mysqli-result.fetch-array.php

	require_once 'Excel/PHPExcel/Classes/PHPExcel.php';

	//objeto de la clase
	$miobjexcel = new PHPExcel();

	//propiedades del libro
	$miobjexcel->getProperties()->setCreator("PlataformaEVA")//Autor del libro
	->setTitle("Reporte de frecuencias")
	->setDescription("Reporte con frecuencias de uso de IP´s"); 

	$titulo="Reporte con frecuencias de IP";
	$tituloColumnas= array('Hora de registro','IP externas','IP internas','IP moviles');

	//Combinando de las celdas A1 a la C1
	$miobjexcel ->setActiveSheetIndex(0)->mergeCells('A1:D1');

	//Agregando los titulos a las columnas
	$miobjexcel ->setActiveSheetIndex(0)
	->setCellValue('A1',$titulo) //asignando el contenido a la celdas
	->setCellValue('A2',$tituloColumnas[0])
	->setCellValue('B2',$tituloColumnas[1])
	->setCellValue('C2',$tituloColumnas[2])
	->setCellValue('D2',$tituloColumnas[3]);

	$i = 3; //numero de fila a partir de la cual vamos a escribir
	while ($fila = $resultado->fetch_array()) {
		$miobjexcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,  $fila['1'])
		->setCellValue('B'.$i,  $fila['2'])
        ->setCellValue('C'.$i,  $fila['3'])
        ->setCellValue('D'.$i,  $fila['4']);
		$i++;
	}
	
	// Se asigna el nombre a la hoja
	$miobjexcel->getActiveSheet()->setTitle('Resultados de frecuencias');
	// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
	$miobjexcel->setActiveSheetIndex(0);
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment;filename=$nomcompleto");
	header('Cache-Control: max-age=0');
	$miobjcreador = PHPExcel_IOFactory::createWriter($miobjexcel, 'Excel2007');
	$miobjcreador->save('php://output');
	
?>
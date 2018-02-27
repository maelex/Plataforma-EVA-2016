create database Plataforma;
	use Plataforma;

	create table Documentos(
		Id_documento int (6) AUTO_INCREMENT,
		Nombre varchar (30),
		Extension varchar (15),
	PRIMARY KEY (Id_documento));

	create table Registros_viejos(
		Id_dato bigint (9) AUTO_INCREMENT,
		Ip_direccion varchar (30),
		Fecha_reg varchar (30),
		Num_documento int (6),
	PRIMARY KEY (Id_dato));	

	create table Procesados(
		Id_procesado bigint(9) AUTO_INCREMENT,
		Direccion_ip int (6),
		Hora int (6),
		Documento_origen int (6),
		Cantidad bigint(6),
	PRIMARY KEY (Id_procesado));

	create table Tipo(
		Id_tipo bigint (9) AUTO_INCREMENT,
		Tipo varchar (30),
	PRIMARY KEY (Id_tipo));

	create table Frecuencias(
		Id_frecuencia bigint(9) AUTO_INCREMENT,
		Ip int(6),
		Hora int(6),
		Cantidad bigint(9),
		Doc int(6),
	PRIMARY KEY (Id_frecuencia));

	create table Csv_registros(
		Id_documento int (6),
		Id_dato bigint (9),
		Fecha_doc TIMESTAMP,
	FOREIGN KEY (Id_documento) REFERENCES Documentos (Id_documento),
	FOREIGN KEY (Id_dato) REFERENCES Registros_viejos (Id_dato));

	create table Registros_procesados(
		Id_dato bigint (9),
		Id_procesado bigint(9),
	FOREIGN KEY (Id_dato) REFERENCES Registros_viejos (Id_dato),
	FOREIGN KEY (Id_procesado) REFERENCES Procesados (Id_procesado));

	create table Tipo_procesado(
		Id_tipo bigint (9),
		Id_procesado bigint (9),
	FOREIGN KEY (Id_tipo) REFERENCES Tipo (Id_tipo),
	FOREIGN KEY (Id_procesado) REFERENCES Procesados (Id_procesado));

	create table Resultados(
		Id_procesado bigint(9),
		Id_frecuencia bigint(9),
	FOREIGN KEY (Id_procesado) REFERENCES Procesados (Id_procesado),
	FOREIGN KEY (Id_frecuencia) REFERENCES Frecuencias (Id_frecuencia));

	/* Plataforma V 2.0  6 de enero 2017*/
	create table Documentos(
		Id_documento int (6) AUTO_INCREMENT,
		Nombre varchar (30),
		Extension varchar (15),
	PRIMARY KEY (Id_documento));

	create table Registros_viejos(
		Id_dato bigint (9) AUTO_INCREMENT,
		Ip_direccion varchar (30),
		Fecha_reg varchar (30),
		Num_documento int (6),
	PRIMARY KEY (Id_dato));	

	create table Procesados(
		Id_procesado bigint(9) AUTO_INCREMENT,
		Direccion_ip int (6),
		Hora int (6),
		Documento_origen int (6),
		Cantidad bigint(6),
	PRIMARY KEY (Id_procesado));

	create table Tipos(
		Id_tipo bigint (9) AUTO_INCREMENT,
		Tipo varchar (30),
	PRIMARY KEY (Id_tipo));

	create table Frecuencias(
		Id_frecuencia bigint(9) AUTO_INCREMENT,
		Ip int(6),
		Hora int(6),
		Cantidad bigint(9),
		Doc int(6),
	PRIMARY KEY (Id_frecuencia));

	create table Csv_registros(
		Id_documento int (6),
		Id_dato bigint (9),
		Fecha_doc TIMESTAMP,
	FOREIGN KEY (Id_documento) REFERENCES Documentos (Id_documento),
	FOREIGN KEY (Id_dato) REFERENCES Registros_viejos (Id_dato));

	create table Registros_procesados(
		Id_dato bigint (9),
		Id_procesado bigint(9),
	FOREIGN KEY (Id_dato) REFERENCES Registros_viejos (Id_dato),
	FOREIGN KEY (Id_procesado) REFERENCES Procesados (Id_procesado));

	create table Tipo_dato(
		Id_tipo bigint (9),
		Id_frecuencia bigint (9),
	FOREIGN KEY (Id_tipo) REFERENCES Tipos (Id_tipo),
	FOREIGN KEY (Id_frecuencia) REFERENCES Frecuencias (Id_frecuencia));

	create table Documentos_frecuencias(
		Id_frecuencia bigint(9),
		Id_documento int(6),
	FOREIGN KEY (Id_frecuencia) REFERENCES Frecuencias (Id_frecuencia),
	FOREIGN KEY (Id_documento) REFERENCES Documentos (Id_documento));

	/* Plataforma V 2.1  21 de enero 2017 VERSION FINAL*/
	create table Documentos(
		Id_documento int (6) AUTO_INCREMENT,
		Nombre varchar (30),
		Extension varchar (15),
	PRIMARY KEY (Id_documento));

	create table Registros_viejos(
		Id_dato bigint (9) AUTO_INCREMENT,
		Ip_direccion varchar (30),
		Fecha_reg varchar (30),
		Num_documento int (6),
	PRIMARY KEY (Id_dato));	

	create table Procesados(
		Id_procesado bigint(9) AUTO_INCREMENT,
		Direccion_ip int (6),
		Hora int (6),
		Documento_origen int (6),
		Cantidad bigint(6),
	PRIMARY KEY (Id_procesado));

	create table Tipos(
		Id_tipo bigint (9) AUTO_INCREMENT,
		Tipo varchar (30),
	PRIMARY KEY (Id_tipo));

	create table Frecuencias(
		Id_frecuencia bigint(9) AUTO_INCREMENT,
		Ip int(6),
		Hora int(6),
		Cantidad bigint(9),
		Doc int(6),
	PRIMARY KEY (Id_frecuencia));

	create table Enlistados(
		Id_enlistado bigint(9) AUTO_INCREMENT,
		Hour int(6),
		Externas bigint(9),
		Internas bigint(9),
		Mobiles bigint(9),
		Document int(6),
	PRIMARY KEY (Id_enlistado));

	create table Csv_registros(
		Id_documento int (6),
		Id_dato bigint (9),
		Fecha_doc TIMESTAMP,
	FOREIGN KEY (Id_documento) REFERENCES Documentos (Id_documento),
	FOREIGN KEY (Id_dato) REFERENCES Registros_viejos (Id_dato));

	create table Registros_procesados(
		Id_dato bigint (9),
		Id_procesado bigint(9),
	FOREIGN KEY (Id_dato) REFERENCES Registros_viejos (Id_dato),
	FOREIGN KEY (Id_procesado) REFERENCES Procesados (Id_procesado));

	create table Tipo_dato(
		Id_tipo bigint (9),
		Id_frecuencia bigint (9),
	FOREIGN KEY (Id_tipo) REFERENCES Tipos (Id_tipo),
	FOREIGN KEY (Id_frecuencia) REFERENCES Frecuencias (Id_frecuencia));

	create table Documentos_frecuencias(
		Id_frecuencia bigint(9),
		Id_documento int(6),
	FOREIGN KEY (Id_frecuencia) REFERENCES Frecuencias (Id_frecuencia),
	FOREIGN KEY (Id_documento) REFERENCES Documentos (Id_documento));

	create table documentos_listas(
		Id_enlistado bigint(9),
		Id_documento int(6),
	FOREIGN KEY (Id_enlistado) REFERENCES Enlistados(Id_enlistado),
	FOREIGN KEY (Id_documento) REFERENCES Documentos(Id_documento));

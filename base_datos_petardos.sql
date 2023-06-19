create database Pirotecnia;
use Pirotecnia;

-- TABLAS
create table TIENDA(
codtie INT AUTO_INCREMENT PRIMARY KEY,
correo VARCHAR(90) NOT NULL UNIQUE,
clave VARCHAR(45) NOT NULL,
pais VARCHAR(45) NOT NULL,
cp INT(5) NOT NULL,
ciudad VARCHAR(45),
direccion VARCHAR(200),
saldo FLOAT(5.2)
);

create table PEDIDOS(
codped INT AUTO_INCREMENT PRIMARY KEY ,
fecha DATE,
enviado VARCHAR(25),
tienda INT
);

create table PEDIDOSPRODUCTOS(
codpedprod INT AUTO_INCREMENT,
pedido INT,
producto INT,
unidades INT,
PRIMARY KEY(codpedprod, pedido, producto)
);

create table PRODUCTOS(
codprod INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(45),
descripcion VARCHAR(90),
peso REAL,
stock INT,
categoria INT,
precio FLOAT(4.2)
);

create table CATEGORIAS(
codcat INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(45) UNIQUE,
descripcion VARCHAR(200)
);

 -- RELACIONES
ALTER TABLE PEDIDOS ADD CONSTRAINT FK_PED_RES FOREIGN KEY(tienda) REFERENCES TIENDA(CodTie);
ALTER TABLE PEDIDOSPRODUCTOS ADD CONSTRAINT FK_PED_PED FOREIGN KEY(pedido) REFERENCES PEDIDOS(codped);
ALTER TABLE PEDIDOSPRODUCTOS ADD CONSTRAINT FK_PED_PRO FOREIGN KEY(producto) REFERENCES PRODUCTOS(codprod);
ALTER TABLE PRODUCTOS ADD CONSTRAINT FK_PRO_CAT FOREIGN KEY(categoria) REFERENCES CATEGORIAS(codcat);

-- Restricción para el estado del pedido
ALTER TABLE PEDIDOS ADD CONSTRAINT CHECK_ESTADO CHECK (ENVIADO IN('ENVIADO','PENDIENTE'));

-- inserciones

-- TABLA CATEGORIAS
insert into CATEGORIAS values(1, 'PETARDOS', 'Petardos clasicos');
insert into CATEGORIAS values(null,'FUENTES','Artefacto pirotecnico que produce un efecto tipo bengala');
insert into CATEGORIAS values(null,'TRACAS','Conjunto de petardos unidos por una misma mecha');

-- TABLA PRODUCTOS
insert into PRODUCTOS values(1,'Petardos Chinos', 'Produce una explosion de mediana intensidad',0.177, 2000,1, 1.50);
insert into PRODUCTOS values(null,'Cobra','Produce una explosion de mediana intensidad',0.225,2000,1, 2);
insert into PRODUCTOS values(null,'Trueno Especial','Produce una explosion de gran intensidad',50,2000,1, 12);
insert into PRODUCTOS values(null,'Gatito','Produce una reducida explosion',80,2000,1, 10);
insert into PRODUCTOS values(null,'Dinamita','Produce una llama de color, cracker, silbato y trueno',50,2000,1, 1.85);

insert into PRODUCTOS values(null,'Fuente Chupito','Siete reducidas fuentes con luces de colores y finaliza con cracker',500,2000,2, 2.60);
insert into PRODUCTOS values(null,'Fuente Pollo Loco','Una fuente con efecto de silbido cracker y color',550,2000,2, 1.60);
insert into PRODUCTOS values(null,'Fuente Furia','Volcan de intenso chorro plateado que supera los dos metros de altura',350,2000,2, 4);
insert into PRODUCTOS values(null,'Gran King','Espectacular chorro de luces blancas de gran intensidad',800,2000,2, 20);
insert into PRODUCTOS values(null, 'La font de Can Rull','Produce efecto sauce azul, destello verde, sauce oro, blanco',1000,2000,2, 30);

insert into PRODUCTOS values(null,'Traca 1000 carpinteros','1000 petardos encendidos en cadena',330,2000,3, 15.95);
insert into PRODUCTOS values(null,'Saltarines','Produce la salida de doce torbellinos de colores rojo y verde',250,2000,3, 1);
insert into PRODUCTOS values(null,'Traca valenciana de 20 metros','Tipica traca valenciana con gran detonacion final',1000,2000,3, 15);
insert into PRODUCTOS values(null,'Traca valenciana de 30 metros','Tipica traca valenciana con gran detonacion final',2000,2000,3, 20);
insert into PRODUCTOS values(null,'Traca valenciana de 50 metros','Tipica traca valenciana con gran detonacion final',3000,2000,3, 35);

-- TABLA TIENDA
insert into TIENDA values(1, 'rocket_factory1@cohetes.pum','catapum','España','11304','Granada','c/paulo coello nº4', 10000);

-- drop database Pirotecnia;
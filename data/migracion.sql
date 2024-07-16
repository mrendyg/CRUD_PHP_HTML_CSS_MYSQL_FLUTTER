CREATE DATABASE IF NOT EXISTS manitosdecolores;
USE manitosdecolores;

CREATE TABLE padre_estudiante (
    id_padre BIGINT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15),
    nombres VARCHAR(50),
    apellido_paterno VARCHAR(50),
    apellido_materno VARCHAR(50),
    edad INTEGER,
    telefono INTEGER
);

CREATE TABLE madre_estudiante (
    id_madre BIGINT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15),
    nombres VARCHAR(50),
    apellido_paterno VARCHAR(50),
    apellido_materno VARCHAR(50),
    edad INTEGER,
    telefono INTEGER
);

CREATE TABLE apoderado_alumno (
    id_apoderado BIGINT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20),
    nombres VARCHAR(50),
    apellido_paterno VARCHAR(50),
    apellido_materno VARCHAR(50),
    edad INTEGER,
    telefono INTEGER
);


CREATE TABLE parbularia (
    id_parbularia BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    edad INT
);


CREATE TABLE curso (
    id_curso BIGINT AUTO_INCREMENT PRIMARY KEY,
    grado VARCHAR(10),
    id_parbularia BIGINT,
    CONSTRAINT fk_parbularia 
        FOREIGN KEY (id_parbularia)
        REFERENCES parbularia(id_parbularia) 
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE alumnos (
    id_alumno BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellido_paterno VARCHAR(50),
    apellido_materno VARCHAR(50),
    edad INTEGER,
    CI FLOAT,
    peso FLOAT,
    vacunas_al_dia VARCHAR(50),
    id_padre BIGINT NOT NULL,
    id_madre BIGINT NOT NULL,
    id_apoderado BIGINT,
    id_curso BIGINT,
    CONSTRAINT fk_padre 
        FOREIGN KEY (id_padre)
        REFERENCES padre_estudiante(id_padre) 
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_madre 
        FOREIGN KEY (id_madre)
        REFERENCES madre_estudiante(id_madre) 
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_apoderado 
        FOREIGN KEY (id_apoderado)
        REFERENCES apoderado_alumno(id_apoderado) 
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_curso 
        FOREIGN KEY (id_curso)
        REFERENCES curso(id_curso) 
        ON DELETE CASCADE
        ON UPDATE CASCADE
);



INSERT INTO `parbularia`(`nombre`, `apellido`, `edad`) VALUES 
('Andrea','Gonzales','32'),
('josefina','Martinez','27'),
('Penelope','Rosales','43'),
('Maria','Lipa','31'),
('Carolina','Gutierrez','25');


INSERT INTO `apoderado_alumno`(`dni`, `nombres`, `apellido_paterno`, `apellido_materno`, `edad`, `telefono`) VALUES 
('12345678-9','Juan Jose','Martinez','Calvario','27','912345678'),
('22112233-3','Marcela Maria','Gonzales','Pereira','32','90123281'),
('33445566-2','Carla Josefa','Retamal','Castillo','27','12312312'),
('12383141-4','Marcela Carolina','Castillo','Perez','57','934342312'),
('99887766-k','Mario Andres','Solteno','Valdovino','22','123098234');


INSERT INTO `padre_estudiante`(`dni`, `nombres`, `apellido_paterno`, `apellido_materno`, `edad`, `telefono`) VALUES 
('12345678-9','Juan Jose','Martinez','Calvario','27','912345678'),
('12222222-9','Pedro Alberto','Donoso','Galaz','32','123456789'),
('22222221-1','Tomas Marcelo','Galvarino','Gonzales','28','12332112'),
('33333333-2','Carlos Antonio','Moreno','Vieytes','30','934323135'),
('99887766-k','Mario Andres','Solteno','Valdovino','22','123098234');


INSERT INTO `madre_estudiante`(`dni`, `nombres`, `apellido_paterno`, `apellido_materno`, `edad`, `telefono`) VALUES 
('20333222-1','Andrea Mariana','Salinas','Vieytes','25','92345678'),
('22112233-3','Marcela Maria','Gonzales','Pereira','32','90123281'),
('33445566-2','Carla Josefa','Retamal','Castillo','27','12312312'),
('23456789-4','Tamara Daniela','Astorga','Navarro','29','90129012'),
('23134256-1','Giselle Constanza','Mariano','Soprano','20','09812392');


INSERT INTO `curso`(`grado`, `id_parbularia`) VALUES 
('Preescolar','1'),
('Párvulos','2'),
('Pre-jardín','5'),
('Jardín','3'),
('Transición','4');

INSERT INTO `alumnos`
(`nombre`, `apellido_paterno`, `apellido_materno`, `edad`, `CI`, `peso`, `vacunas_al_dia`, `id_padre`, `id_madre`, `id_apoderado`, `id_curso`) VALUES 
('Jose Andres','Martinez','Salinas','2','','12.6','Si','1','1','1','2'),
('Maria Jesus','Donoso','Gonzales','1','','10.7','Si','2','2','2','1'),
('Constanza Andrea','Galvarino','Retamal','3','','14.2','Si','3','3','3','3'),
('Pablo Martin','Moreno','Astorga','4','','14.2','Si','4','4','4','4'),
('Mario Gabriel','Solteno','Mariano','4','','14.2','Si','5','5','5','5');


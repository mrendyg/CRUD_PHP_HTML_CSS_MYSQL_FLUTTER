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
    id_padre BIGINT,
    id_madre BIGINT,
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


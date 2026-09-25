-- cv.sql — esquema v2 (rediseño), sin ENUM, con catálogos y restricciones de integridad correctas
-- Sustituye por completo al dump original de 2024.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS cv
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE cv;

-- --------------------------------------------------------
-- Catálogos (sin dependencias de otras tablas)
-- --------------------------------------------------------

CREATE TABLE rol (
  id_rol INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  rol VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO rol (id_rol, rol) VALUES
(1, 'administrador'),
(2, 'usuario');

-- Sustituye al ENUM `estado` de la tabla oferta
CREATE TABLE estado_oferta (
  id_estado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO estado_oferta (id_estado, nombre) VALUES
(1, 'abierto'),
(2, 'cerrado'),
(3, 'guardado');

-- Sustituye al ENUM `tipo_trabajo` de la tabla oferta
CREATE TABLE tipo_trabajo (
  id_tipo_trabajo INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO tipo_trabajo (id_tipo_trabajo, nombre) VALUES
(1, 'presencial'),
(2, 'híbrido'),
(3, 'remoto');

-- --------------------------------------------------------
-- Entidades propias, sin datos de prueba
-- --------------------------------------------------------

CREATE TABLE contacto (
  id_contacto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre_contacto VARCHAR(255) DEFAULT NULL,
  cargo VARCHAR(255) DEFAULT NULL,
  correo VARCHAR(100) DEFAULT NULL,
  telefono VARCHAR(9) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE empresa (
  id_empresa INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre_empresa VARCHAR(255) NOT NULL,
  web VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE envio (
  id_envio INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tipo VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- password: VARCHAR(255), tamaño estándar recomendado para password_hash() de PHP
CREATE TABLE usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(255) NOT NULL UNIQUE,
  correo VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol) REFERENCES rol (id_rol)
) ENGINE=InnoDB;

-- oferta: id_empresa e id_envio obligatorios; id_contacto opcional
CREATE TABLE oferta (
  id_oferta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE DEFAULT NULL,
  nombre_puesto VARCHAR(255) NOT NULL,
  experiencia_anios INT DEFAULT NULL,
  requiere_ingles TINYINT(1) NOT NULL DEFAULT 0,
  tecnologia VARCHAR(255) DEFAULT NULL,
  id_envio INT NOT NULL,
  id_empresa INT NOT NULL,
  id_estado INT NOT NULL DEFAULT 1,
  id_tipo_trabajo INT NOT NULL,
  id_usuario INT NOT NULL,
  id_contacto INT DEFAULT NULL,
  fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_oferta_envio FOREIGN KEY (id_envio) REFERENCES envio (id_envio)
    ON DELETE RESTRICT,
  CONSTRAINT fk_oferta_empresa FOREIGN KEY (id_empresa) REFERENCES empresa (id_empresa)
    ON DELETE RESTRICT,
  CONSTRAINT fk_oferta_estado FOREIGN KEY (id_estado) REFERENCES estado_oferta (id_estado),
  CONSTRAINT fk_oferta_tipo_trabajo FOREIGN KEY (id_tipo_trabajo) REFERENCES tipo_trabajo (id_tipo_trabajo),
  CONSTRAINT fk_oferta_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
    ON DELETE CASCADE,
  CONSTRAINT fk_oferta_contacto FOREIGN KEY (id_contacto) REFERENCES contacto (id_contacto)
    ON DELETE SET NULL
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
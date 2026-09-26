CREATE DATABASE IF NOT EXISTS cv
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE cv;

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Catálogos (compartidos por toda la aplicación: rol/estado/tipo_trabajo/envio
-- son taxonomías fijas del sistema, no datos propios de cada usuario)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS rol (
  id_rol INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  rol VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT IGNORE INTO rol (id_rol, rol) VALUES
(1, 'Administrador'),
(2, 'Usuario');

CREATE TABLE IF NOT EXISTS estado_oferta (
  id_estado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT IGNORE INTO estado_oferta (id_estado, nombre) VALUES
(1, 'Abierta'),
(2, 'Cerrada'),
(3, 'Guardada');

CREATE TABLE IF NOT EXISTS tipo_trabajo (
  id_tipo_trabajo INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT IGNORE INTO tipo_trabajo (id_tipo_trabajo, nombre) VALUES
(1, 'Presencial'),
(2, 'Híbrido'),
(3, 'Remoto');

CREATE TABLE IF NOT EXISTS envio (
  id_envio INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tipo VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Entidades
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(255) NOT NULL UNIQUE,
  correo VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol) REFERENCES rol (id_rol)
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- Entidades privadas por usuario (catálogo propio, no compartido)
-- ---------------------------------------------------------------

CREATE TABLE IF NOT EXISTS contacto (
  id_contacto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  nombre_contacto VARCHAR(255) DEFAULT NULL,
  cargo VARCHAR(255) DEFAULT NULL,
  correo VARCHAR(100) DEFAULT NULL,
  telefono VARCHAR(9) DEFAULT NULL,
  CONSTRAINT fk_contacto_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS empresa (
  id_empresa INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  nombre_empresa VARCHAR(255) NOT NULL,
  web VARCHAR(255) DEFAULT NULL,
  CONSTRAINT fk_empresa_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- OFERTA
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS oferta (
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

  CONSTRAINT fk_oferta_envio FOREIGN KEY (id_envio) REFERENCES envio (id_envio) ON DELETE RESTRICT,
  CONSTRAINT fk_oferta_empresa FOREIGN KEY (id_empresa) REFERENCES empresa (id_empresa) ON DELETE RESTRICT,
  CONSTRAINT fk_oferta_estado FOREIGN KEY (id_estado) REFERENCES estado_oferta (id_estado),
  CONSTRAINT fk_oferta_tipo_trabajo FOREIGN KEY (id_tipo_trabajo) REFERENCES tipo_trabajo (id_tipo_trabajo),
  CONSTRAINT fk_oferta_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  CONSTRAINT fk_oferta_contacto FOREIGN KEY (id_contacto) REFERENCES contacto (id_contacto) ON DELETE SET NULL
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
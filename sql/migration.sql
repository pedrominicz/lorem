CREATE DATABASE IF NOT EXISTS lorem;

USE lorem;

DROP TABLE IF EXISTS cadastros;

CREATE TABLE cadastros (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  nome          VARCHAR(127) NOT NULL,
  email         VARCHAR(127) NOT NULL UNIQUE,
  -- Telefones são guardados em uma string para evitar problemas como falta de
  -- zeros iniciais caso se use números (e.g. 00123 seria guardado como 123).
  telefone      VARCHAR(15) NOT NULL UNIQUE,
  idade         INT NOT NULL,
  data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

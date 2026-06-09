-- Banco de dados para o aplicativo CRUD Flamengo
-- Este arquivo cria o database e as tabelas necessárias para rodar a aplicação.

DROP DATABASE IF EXISTS ingresso;
CREATE DATABASE ingresso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ingresso;

-- Tabela de usuários para login
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de adversários
DROP TABLE IF EXISTS adversarios;
CREATE TABLE adversarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    estado CHAR(2) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de ingressos
DROP TABLE IF EXISTS ingressos;
CREATE TABLE ingressos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    partida VARCHAR(255) NOT NULL,
    setor VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de pagamentos
DROP TABLE IF EXISTS pagamentos;
CREATE TABLE pagamentos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    cliente VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    metodo VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL,
    data_pagamento DATE NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados iniciais de exemplo
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@flamengo.com', 'admin123');

INSERT INTO adversarios (nome, estado) VALUES
('Vasco da Gama', 'RJ'),
('Fluminense', 'RJ'),
('Cruzeiro', 'MG');

INSERT INTO ingressos (partida, setor, preco, quantidade) VALUES
('Flamengo x Fluminense', 'Norte', 150.00, 5600),
('Flamengo x Vasco', 'Sul', 180.00, 6200);

INSERT INTO pagamentos (cliente, valor, metodo, status, data_pagamento) VALUES
('Gabriel Barbosa', 150.00, 'Pix', 'Aprovado', '2026-06-01'),
('Bruno Henrique', 220.00, 'Cartão de Crédito', 'Pendente', '2026-06-05');

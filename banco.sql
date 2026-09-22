create database if not exists media_escolar;
use media_escolar; 

CREATE TABLE medias_escolares (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno VARCHAR(100) NOT NULL,
    nota1 DECIMAL(4,2) NOT NULL,
    nota2 DECIMAL(4,2) NOT NULL,
    nota3 DECIMAL(4,2) NOT NULL,
    media DECIMAL(4,2) NOT NULL,
    situacao VARCHAR(20) NOT NULL
);

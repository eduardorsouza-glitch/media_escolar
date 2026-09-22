CREATE TABLE medias_escolares (
    id SERIAL PRIMARY KEY,
    aluno VARCHAR(100) NOT NULL,
    nota1 NUMERIC(4,2) NOT NULL,
    nota2 NUMERIC(4,2) NOT NULL,
    nota3 NUMERIC(4,2) NOT NULL,
    media NUMERIC(4,2) NOT NULL,
    situacao VARCHAR(20) NOT NULL
);

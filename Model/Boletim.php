<?php

namespace Model;

use Exception;
use Model\Connection;
use PDO;
use PDOException;

class Boletim
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = Connection::getInstance();
    }

    public function cadastrar(string $aluno, float $nota1, float $nota2, float $nota3): int
    {
        try {
            $media = ($nota1 + $nota2 + $nota3) / 3;
            $situacao = $media >= 7 ? 'Aprovado' : 'Reprovado';

            $comando = $this->conexao->prepare(
                "INSERT INTO medias_escolares (aluno, nota1, nota2, nota3, media, situacao)
                 VALUES (:aluno, :nota1, :nota2, :nota3, :media, :situacao)"
            );

            $comando->execute([
                'aluno' => $aluno,
                'nota1' => $nota1,
                'nota2' => $nota2,
                'nota3' => $nota3,
                'media' => $media,
                'situacao' => $situacao
            ]);

            return (int) $this->conexao->lastInsertId();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Erro ao cadastrar média escolar");
        }
    }

    public function buscar(int $id): ?array
    {
        try {
            $comando = $this->conexao->prepare("SELECT * FROM medias_escolares WHERE id = :id");
            $comando->execute(['id' => $id]);

            return $comando->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Erro ao buscar média escolar");
        }
    }

    public function listar(): array
    {
        try {
            return $this->conexao->query("SELECT * FROM medias_escolares ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Erro ao listar médias escolares");
        }
    }

    public function atualizar(int $id, float $nota1, float $nota2, float $nota3): bool
    {
        try {
            $media = ($nota1 + $nota2 + $nota3) / 3;
            $situacao = $media >= 7 ? 'Aprovado' : 'Reprovado';

            $comando = $this->conexao->prepare(
                "UPDATE medias_escolares
                 SET nota1 = :nota1, nota2 = :nota2, nota3 = :nota3, media = :media, situacao = :situacao
                 WHERE id = :id"
            );

            return $comando->execute([
                'nota1' => $nota1,
                'nota2' => $nota2,
                'nota3' => $nota3,
                'media' => $media,
                'situacao' => $situacao,
                'id' => $id
            ]);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Erro ao atualizar média escolar");
        }
    }

    public function excluir(int $id): bool
    {
        try {
            $comando = $this->conexao->prepare("DELETE FROM medias_escolares WHERE id = :id");
            $comando->execute(['id' => $id]);

            return $comando->rowCount() > 0;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Erro ao excluir média escolar");
        }
    }
}

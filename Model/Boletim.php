```php
<?php

require_once __DIR__ . '/Connection.php';

class Boletim
{
    public static function listar()
    {
        $db = Connection::getConnection();

        $sql = "SELECT * FROM boletins ORDER BY id";
        $result = pg_query($db, $sql);

        return pg_fetch_all($result) ?: [];
    }

    public static function cadastrar()
    {
        $dados = json_decode(file_get_contents("php://input"), true);

        $aluno = $dados['aluno'];
        $disciplina = $dados['disciplina'];
        $nota1 = $dados['nota1'];
        $nota2 = $dados['nota2'];
        $nota3 = $dados['nota3'];

        $media = ($nota1 + $nota2 + $nota3) / 3;
        $situacao = $media >= 7 ? 'Aprovado' : 'Reprovado';

        $db = Connection::getConnection();

        $sql = "INSERT INTO boletins
                (aluno, disciplina, nota1, nota2, nota3, media, situacao)
                VALUES ($1, $2, $3, $4, $5, $6, $7)
                RETURNING *";

        $result = pg_query_params($db, $sql, [
            $aluno,
            $disciplina,
            $nota1,
            $nota2,
            $nota3,
            $media,
            $situacao
        ]);

        return pg_fetch_assoc($result);
    }

    public static function buscar($id)
    {
        $db = Connection::getConnection();

        $result = pg_query_params(
            $db,
            "SELECT * FROM boletins WHERE id = $1",
            [$id]
        );

        return pg_fetch_assoc($result);
    }

    public static function atualizar($id)
    {
        $dados = json_decode(file_get_contents("php://input"), true);

        $nota1 = $dados['nota1'];
        $nota2 = $dados['nota2'];
        $nota3 = $dados['nota3'];

        $media = ($nota1 + $nota2 + $nota3) / 3;
        $situacao = $media >= 7 ? 'Aprovado' : 'Reprovado';

        $db = Connection::getConnection();

        $sql = "UPDATE boletins
                SET nota1 = $1,
                    nota2 = $2,
                    nota3 = $3,
                    media = $4,
                    situacao = $5
                WHERE id = $6
                RETURNING *";

        $result = pg_query_params($db, $sql, [
            $nota1,
            $nota2,
            $nota3,
            $media,
            $situacao,
            $id
        ]);

        return pg_fetch_assoc($result);
    }

    public static function excluir($id)
    {
        $db = Connection::getConnection();

        $result = pg_query_params(
            $db,
            "DELETE FROM boletins WHERE id = $1",
            [$id]
        );

        return ['mensagem' => 'Boletim excluído com sucesso'];
    }
}
```

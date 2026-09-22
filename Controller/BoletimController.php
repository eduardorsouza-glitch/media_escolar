<?php

namespace Controller;

use Model\Boletim;
use Exception;
use OpenApi\Attributes as OA;

class BoletimController
{
    public function __construct(private Boletim $boletim)
    {
    }

    public function tratarRequisicao(string $metodo, ?string $id): void
    {
        if ($id === null) {
            match ($metodo) {
                "GET" => $this->listar(),
                "POST" => $this->cadastrar(),
                default => $this->metodoInvalido(["GET", "POST"])
            };
            return;
        }

        match ($metodo) {
            "GET" => $this->exibir((int) $id),
            "PUT" => $this->atualizar((int) $id),
            "DELETE" => $this->excluir((int) $id),
            default => $this->metodoInvalido(["GET", "PUT", "DELETE"])
        };
    }

    #[OA\Get(
    path: "/medias",
    summary: "Lista todas as médias escolares",
    tags: ["Médias"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Lista de médias escolares"
        ),
        new OA\Response(
            response: 500,
            description: "Erro interno"
        )
    ]
)]
private function listar(): void
{
    try {
        http_response_code(200);
        echo json_encode($this->boletim->listar());
    } catch (Exception $e) {
        $this->erro(500, $e->getMessage());
    }
}
    
    #[OA\Post(
    path: "/medias",
    summary: "Cadastra uma nova média escolar",
    tags: ["Médias"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(
                    property: "aluno",
                    type: "string",
                    example: "Eduardo"
                ),
                new OA\Property(
                    property: "nota1",
                    type: "number",
                    format: "float",
                    example: 8.5
                ),
                new OA\Property(
                    property: "nota2",
                    type: "number",
                    format: "float",
                    example: 7.0
                ),
                new OA\Property(
                    property: "nota3",
                    type: "number",
                    format: "float",
                    example: 9.0
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: "Média cadastrada com sucesso"
        ),
        new OA\Response(
            response: 422,
            description: "Dados inválidos"
        ),
        new OA\Response(
            response: 500,
            description: "Erro interno"
        )
    ]
)]

    private function cadastrar(): void
    {
        $dados = $this->lerCorpo();
        $erros = $this->validarNotas($dados);

        if (empty($dados["aluno"])) {
            $erros[] = "O campo 'aluno' é obrigatório.";
        }

        if ($erros) {
            $this->erro(422, $erros);
            return;
        }

        try {
            $id = $this->boletim->cadastrar(
                $dados["aluno"],
                (float) $dados["nota1"],
                (float) $dados["nota2"],
                (float) $dados["nota3"]
            );

            http_response_code(201);
            echo json_encode($this->boletim->buscar($id));
        } catch (Exception $e) {
            $this->erro(500, $e->getMessage());
        }
    }

    #[OA\Get(
    path: "/medias/{id}",
    summary: "Busca uma média escolar pelo ID",
    tags: ["Médias"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID da média escolar",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Média encontrada"
        ),
        new OA\Response(
            response: 404,
            description: "Média não encontrada"
        ),
        new OA\Response(
            response: 500,
            description: "Erro interno"
        )
    ]
)]

    private function exibir(int $id): void
    {
        try {
            $registro = $this->boletim->buscar($id);

            if (!$registro) {
                $this->erro(404, "Média escolar não encontrada!");
                return;
            }

            http_response_code(200);
            echo json_encode($registro);
        } catch (Exception $e) {
            $this->erro(500, $e->getMessage());
        }
    }

    #[OA\Put(
    path: "/medias/{id}",
    summary: "Atualiza uma média escolar",
    tags: ["Médias"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID da média escolar",
            schema: new OA\Schema(type: "integer")
        )
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(
                    property: "aluno",
                    type: "string",
                    example: "Eduardo"
                ),
                new OA\Property(
                    property: "nota1",
                    type: "number",
                    format: "float",
                    example: 8.5
                ),
                new OA\Property(
                    property: "nota2",
                    type: "number",
                    format: "float",
                    example: 7.0
                ),
                new OA\Property(
                    property: "nota3",
                    type: "number",
                    format: "float",
                    example: 9.0
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: "Média atualizada com sucesso"
        ),
        new OA\Response(
            response: 404,
            description: "Média não encontrada"
        ),
        new OA\Response(
            response: 422,
            description: "Dados inválidos"
        ),
        new OA\Response(
            response: 500,
            description: "Erro interno"
        )
    ]
)]

    private function atualizar(int $id): void
    {
        try {
            $registro = $this->boletim->buscar($id);

            if (!$registro) {
                $this->erro(404, "Média escolar não encontrada!");
                return;
            }

            $dados = $this->lerCorpo();
            $nota1 = $dados["nota1"] ?? $registro["nota1"];
            $nota2 = $dados["nota2"] ?? $registro["nota2"];
            $nota3 = $dados["nota3"] ?? $registro["nota3"];

            $erros = $this->validarNotas(["nota1" => $nota1, "nota2" => $nota2, "nota3" => $nota3]);

            if ($erros) {
                $this->erro(422, $erros);
                return;
            }

            $this->boletim->atualizar($id, (float) $nota1, (float) $nota2, (float) $nota3);

            http_response_code(200);
            echo json_encode($this->boletim->buscar($id));
        } catch (Exception $e) {
            $this->erro(500, $e->getMessage());
        }
    }

    #[OA\Delete(
    path: "/medias/{id}",
    summary: "Exclui uma média escolar",
    tags: ["Médias"],
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            description: "ID da média escolar",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "Média excluída com sucesso"
        ),
        new OA\Response(
            response: 404,
            description: "Média não encontrada"
        ),
        new OA\Response(
            response: 500,
            description: "Erro interno"
        )
    ]
)]

    private function excluir(int $id): void
    {
        try {
            if (!$this->boletim->buscar($id)) {
                $this->erro(404, "Média escolar não encontrada!");
                return;
            }

            $this->boletim->excluir($id);
            http_response_code(204);
        } catch (Exception $e) {
            $this->erro(500, $e->getMessage());
        }
    }

    private function lerCorpo(): array
    {
        $dados = json_decode(file_get_contents("php://input"), true);
        return is_array($dados) ? $dados : [];
    }

    private function validarNotas(array $dados): array
    {
        $erros = [];

        foreach (["nota1", "nota2", "nota3"] as $campo) {
            if (!isset($dados[$campo]) || !is_numeric($dados[$campo])) {
                $erros[] = "O campo '$campo' é obrigatório e precisa ser numérico.";
            }
        }

        return $erros;
    }

    private function metodoInvalido(array $permitidos): void
    {
        header("Allow: " . implode(", ", $permitidos));
        $this->erro(405, "Método não permitido");
    }

    private function erro(int $codigo, mixed $mensagem): void
    {
        http_response_code($codigo);
        echo json_encode(is_array($mensagem) ? ["errors" => $mensagem] : ["error" => $mensagem]);
    }
}

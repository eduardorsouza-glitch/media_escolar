<?php
$mediaFinal = null;
$situacao = "";
$notas = [];
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $totalUnidade = (int) ($_POST["totalUnidade"] ?? 0);
    $mediaAprovacao = (float) ($_POST["mediaAprovacao"] ?? 7);
    $notasRecebidas = $_POST["notas"] ?? [];

    if ($totalUnidade <= 0) {
        $erro = "Digite um número válido de unidades.";
    } else {
        $soma = 0;
        $notasValidas = true;

        for ($i = 0; $i < $totalUnidade; $i++) {
            $nota = isset($notasRecebidas[$i]) ? (float) $notasRecebidas[$i] : null;

            if ($nota === null || $nota < 0 || $nota > 10) {
                $notasValidas = false;
                $erro = "Todas as notas devem estar entre 0 e 10.";
                break;
            }

            $notas[] = $nota;
            $soma += $nota;
        }

        if ($notasValidas) {
            $mediaFinal = $soma / $totalUnidade;

            if ($mediaFinal >= $mediaAprovacao) {
                $situacao = "Parabéns! Você foi aprovado(a)!";
            } else {
                $situacao = "Estude mais! Você foi reprovado(a).";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Média</title>
</head>
<body>

    <h2>Calculadora de Média</h2>

    <form method="POST">
        <p>Quantas unidades tem?</p>
        <input type="number" name="totalUnidade" id="quantidade" min="1" required>
        <br><br>

        <p>Qual a média mínima para aprovação?</p>
        <input type="number" name="mediaAprovacao" step="0.1" value="7" required>
        <br><br>

        <!-- Campos das notas vão aparecer aqui -->
        <div id="area_notas"></div>

        <button type="submit">Calcular a média</button>
    </form>

    <!-- Mostra erro -->
    <?php if ($erro != ""): ?>
        <p><?php echo $erro; ?></p>
    <?php endif; ?>

    <!-- Mostra resultado -->
    <?php if ($mediaFinal !== null): ?>
        <h3>Resultado:</h3>

        <?php
        $numero = 1;
        foreach ($notas as $nota) {
            echo "Unidade " . $numero . ": " . $nota . "<br>";
            $numero++;
        }
        ?>

        <p>Média final foi: <strong><?php echo number_format($mediaFinal, 2); ?></strong></p>
        <p><?php echo $situacao; ?></p>
    <?php endif; ?>

    <script>
        const campoQuantidade = document.getElementById("quantidade");
        const areaNotas = document.getElementById("area_notas");

        function criarCamposDeNota() {
            const total = parseInt(campoQuantidade.value) || 0;
            areaNotas.innerHTML = "";

            for (let i = 1; i <= total; i++) {
                areaNotas.innerHTML += `
                    <p>Nota da unidade ${i}:</p>
                    <input type="number" name="notas[]" step="0.1" min="0" max="10" required>
                    <br>
                `;
            }
        }

        campoQuantidade.addEventListener("input", criarCamposDeNota);
    </script>

</body>
</html>
// Criação das variáveis iniciais
let totalUnidade = Number(document.getElementById("totalUnidades").value);
let mediaAprovacao = Number(document.getElementById("mediaAprovacao").value);
let somaNotas = 0;

// Simulação das unidades
if (totalUnidade <= 0 || isNaN(totalUnidade)) {
    console.log("Digite um número válido de unidades.");
} else {
    for (let unidade = 1; unidade <= totalUnidade; unidade++) {
        // Pede a nota da unidade atual para o usuário
        let notaUnidade = Number(prompt(`Digite a nota da unidade ${unidade}:`));

        // Validação simples da nota
        while (isNaN(notaUnidade) || notaUnidade < 0 || notaUnidade > 10) {
            notaUnidade = Number(prompt(`Nota inválida! Digite a nota da unidade ${unidade} (entre 0 e 10):`));
        }

        // Atualização da soma
        somaNotas += notaUnidade;

        // Exibição intermediária
        console.log(`Nota da unidade ${unidade}: ${notaUnidade}`);
    }

    // Cálculo final (só acontece se tiver unidades válidas)
    let mediaFinal = somaNotas / totalUnidade;
    console.log(`Média final: ${mediaFinal.toFixed(2)}`);

    // Saída e condição
    if (mediaFinal >= mediaAprovacao) {
        console.log("Aprovado(a)!");
    } else {
        console.log("Reprovado(a).");
    }
}
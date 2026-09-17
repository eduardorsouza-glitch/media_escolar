// Criação das constantes e variáveis iniciais
const totalBimestres = 0;
const mediaAprovacao = 0;
let somaNotas = 0;

// Laço FOR para simulação dos semestres
for(let bimestre = 1; bimestre <= totalBimestres; bimestre++){
    // Soma acumulada
        let notaBimestral;
    if (bimestre === 1) {
        notaBimestral = 8.5;
    } else if (bimestre === 2) {
        notaBimestral = 7.9;
    } else if (bimestre === 3) {
        notaBimestral = 6.3;
    }else {
        notaBimestral = 9.8;
    }

    // Atualização da soma
    somaNotas += notaBimestral;

    //Exebibição intermediária
    console.log("Nota do bimestre ${bimestre}: ${notaBimestral}");
}

// Cálculo final
let mediaFinal = somaNotas/totalBimestres;

// Saída e condição
console.log("Média final: ${mediaFinal}");
if (mediaFinal >= mediaAprovacao) {
    console.log("Parabéns! Você foi aprovado(a)!");
} else {
    console.log("Estude mais! Você foi reprovado(a).");
}
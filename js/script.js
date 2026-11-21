function mudarExame(caixa) {
    var linhas = document.getElementsByClassName("exame_" + caixa.value);

    for (var i = 0; i < linhas.length; i++) {
        if (caixa.checked) {
            linhas[i].style.display = "list-item";
        } else {
            linhas[i].style.display = "none";
        }
    }

    somarTotais();
}

function somarTotais() {
    var laboratorios = document.getElementsByClassName("laboratorio");

    for (var i = 0; i < laboratorios.length; i++) {
        var total = 0;
        var exames = laboratorios[i].getElementsByTagName("li");

        for (var j = 0; j < exames.length; j++) {
            if (exames[j].style.display != "none") {
                var preco = exames[j].getElementsByClassName("preco")[0].innerHTML;
                total = total + parseFloat(preco);
            }
        }

        laboratorios[i].getElementsByClassName("total")[0].innerHTML = total.toFixed(2);
    }
}
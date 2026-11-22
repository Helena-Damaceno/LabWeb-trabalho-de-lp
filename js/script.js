function atualizarTotais() {

    let exames = document.querySelectorAll('.exames input');
    let precos = document.querySelectorAll('.preco');

    precos.forEach(function(preco) {
        let id = preco.dataset.exame;

        exames.forEach(function(exame) {

            if (exame.value == id) {
                if (!exame.checked) {
                    preco.dataset.ativo = "nao";
                } else {
                    preco.dataset.ativo = "sim";
                }
            }
        });

    });

    let laboratorios = document.querySelectorAll('.lab');

    laboratorios.forEach(function(lab) {
        let total = 0;

        lab.querySelectorAll('.preco').forEach(function(preco) {

            if (preco.dataset.ativo != "nao") {
                total += parseFloat(preco.textContent);
            }

        });

        lab.querySelector('.total').textContent =
            total.toFixed(2).replace('.', ',');

    });
}

document.querySelectorAll('.exames input').forEach(function(exame) {
    exame.addEventListener('change', atualizarTotais);
});
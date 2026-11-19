<?php
require_once('./conexao.php');

$titulo = "";

if (isset($_POST['titulo'])) {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $id = isset($_GET['condicao']) ? (int) $_GET['condicao'] : 0;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LabWeb</title>
</head>

<body>

    <div id="cabecalho">

        <h1>LabWeb</h1>

        <p>Guia de Sintomas, Exames e Laboratórios</p>

    </div>

    <div class="pesquisa">

        <form method="POST">
            Informe seu sintoma:
            <input type="text" name="titulo">
            <input class="procurar" type="submit" value="Procurar">
        </form>

    </div>
    </div>

    <?php

    $sql = "SELECT * FROM condicoes_saude WHERE nome LIKE '%$titulo%' OR descricao LIKE '%$titulo%'";

    $res = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));

    $num = mysqli_num_rows($res);

    if ($num > 0) {

        while ($linha = mysqli_fetch_assoc($res)) {
            $id_condicao = $linha['id'];

            echo "<h2>" . $linha['nome'] . "</h2>";
            echo "<p>" . $linha['descricao'] . "</p>";
            echo "<p>Orientações: " . $linha['orientacoes_preparo'] . "</p>";

            $sql2 = "SELECT * FROM exames
         JOIN condicao_exame ON condicao_exame.exame_id = exames.id
         WHERE condicao_exame.condicao_id = $id_condicao";
            $res2 = mysqli_query($conn, $sql2);

            echo "<h4>Exames recomendados:</h4>";
            while ($exame = mysqli_fetch_assoc($res2)) {
                echo "<p><strong>" . $exame['nome'] . "</strong><br>";
                echo "O que mede: " . $exame['descricao'] . "<br>";
                echo "Preparo: " . $exame['instrucoes_preparo'] . "</p>";
            }

            $sql3 = "SELECT DISTINCT laboratorios.* FROM laboratorios
         JOIN laboratorio_exame ON laboratorio_exame.laboratorio_id = laboratorios.id
         JOIN condicao_exame ON condicao_exame.exame_id = laboratorio_exame.exame_id
         WHERE condicao_exame.condicao_id = $id_condicao";
            $res3 = mysqli_query($conn, $sql3);

            echo "<h4>Laboratórios:</h4>";
            while ($lab = mysqli_fetch_assoc($res3)) {
                echo "<p><strong>" . $lab['nome'] . "</strong><br>";
                echo $lab['endereco'] . " - " . $lab['bairro'] . "<br>";
                echo "Telefone: " . $lab['telefone'] . "<br>";
                echo "Prazo do laudo: " . $lab['tempo_entrega_dias'] . " dia(s)</p>";
            }

            echo "<hr>";
        }
    } else {
        echo "<p> Nenhuma condição de saúde cadastrada. </p>";

    }

    ?>

    <h2>Condições de saúde cadastradas</h2>
    <?php

    $sql = "SELECT * FROM condicoes_saude";
    ?>

</body>

</html>
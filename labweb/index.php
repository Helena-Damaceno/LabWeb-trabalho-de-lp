<?php
require_once('./conexao.php');

$titulo = "";

if (isset($_POST['titulo'])) {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
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
            echo "<p>";
            echo $linha['nome'];
            echo "</p>";
            echo "<p>";
            echo $linha['descricao'];
            echo "</p>";
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
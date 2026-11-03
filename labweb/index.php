<?php
require_once('./conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LabWeb</title>
</head>

<body>

    <h1>LabWeb</h1>
    <p>Guia de Sintomas, Exames e Laboratórios</p>

    <h2>Condições de saúde cadastradas</h2>

    <?php

    $sql = "SELECT * FROM condicoes_saude";

    $res = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));

    $num = mysqli_num_rows($res);

    if ($num > 0)
    {
        while ($linha = mysqli_fetch_assoc($res))
        {
            echo "<p>";
            echo $linha['nome'];
            echo "</p>";
            echo "<p>";
            echo $linha['descricao'];
            echo "</p>";
            echo "<hr>";
        }
    }
    else
    {
        echo "<p>Nenhuma condição de saúde cadastrada.</p>";
    }

    ?>

</body>
</html>
<?php
require_once('./conexao.php');

$titulo = "";
$convenio = 0;
$bairro = "";

if (isset($_POST['titulo'])) {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $convenio = (int) $_POST['convenio'];
    $bairro = mysqli_real_escape_string($conn, $_POST['bairro']);
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

    <div id="cabeçalho">

        <h1>LabWeb</h1>

        <p>Guia de Sintomas, Exames e Laboratórios</p>

    </div>

    <div class="pesquisa">

        <form method="POST">
            Sintoma: <input type="text" name="titulo">

            Bairro: <select name="bairro">
                <option value=""> Todos </option>
                <?php
                $r = mysqli_query($conn, "SELECT DISTINCT bairro FROM laboratorios");
                while ($b = mysqli_fetch_assoc($r)) {
                    echo "<option value='" . $b['bairro'] . "'>" . $b['bairro'] . "</option>";
                }
                ?>
            </select>
            Convênio: <select name="convenio">
                <option value="0">Todos</option>
                <?php
                $r = mysqli_query($conn, "SELECT * FROM convenios");
                while ($c = mysqli_fetch_assoc($r)) {
                    echo "<option value='" . $c['id'] . "'>" . $c['nome'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Filtrar">
        </form>

    </div>
    </div>

    <hr>
    <?php

    $sql = "SELECT * FROM condicoes_saude WHERE nome LIKE '%$titulo%' OR descricao LIKE '%$titulo%'";
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    while ($c = mysqli_fetch_assoc($res)) {
        echo "<a href='condicao.php?id=" . $c['id'] . "'>" . $c['nome'] . "</a><br>";
    }
    ?>
</body>

</html>
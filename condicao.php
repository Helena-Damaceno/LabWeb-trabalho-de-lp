<?php
require_once('./conexao.php');

$idcondicao =  $_GET['id'];

if (isset($_GET['bairro'])) {
    $bairro = $_GET['bairro'];
} else {
    $bairro = "";
}

if (isset($_GET['convenio'])) {
    $convenio = (int) $_GET['convenio'];
} else {
    $convenio = 0;
}

$sqlcondicao = "SELECT * FROM condicoes_saude WHERE id = $idcondicao";
$res_condicao = mysqli_query($conn, $sqlcondicao);
$condicao = mysqli_fetch_assoc($res_condicao);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LabWeb</title>
</head>

<body>

    <a href="index.php">Voltar</a>
    <script src="js/script.js"></script>

    <h2><?php echo $condicao['nome']; ?></h2>
    <p><?php echo $condicao['descricao']; ?></p>
    <p>Orientações: <?php echo $condicao['orientacoes_preparo']; ?></p>

    <hr>

    <h2>Exames que você precisa fazer para tratar sua condição:</h2>

    <?php
    $sql_exames = "SELECT exames.* FROM exames
                   JOIN condicao_exame ON condicao_exame.exame_id = exames.id
                   WHERE condicao_exame.condicao_id = $idcondicao";
    $res_exames = mysqli_query($conn, $sql_exames);

    while ($exame = mysqli_fetch_assoc($res_exames)) {
        echo "<p><input type='checkbox' value='" . $exame['id'] . "' checked onchange='mudarExame(this)'> ";
        echo "<strong>" . $exame['nome'] . "</strong><br>";
        echo "O que faz: " . $exame['descricao'] . "<br>";
        echo "Instruções: " . $exame['instrucoes_preparo'] . "</p>";
    }
    ?>

    <hr>

    <h2>Laboratórios:</h2>


    <form method="GET" action="condicao.php">
        <input type="hidden" name="id" value="<?php echo $idcondicao; ?>">
        Bairro:
        <select name="bairro">
            <option value="">Todos</option>
            <?php
            $resulbairros = mysqli_query($conn, "SELECT DISTINCT bairro FROM laboratorios");
            while ($b = mysqli_fetch_assoc($resulbairros)) {
                echo "<option>" . $b['bairro'] . "</option>";
            }
            ?>
        </select>

        Convênio:
        <select name="convenio">
            <option value="0">Todos</option>
            <?php
            $resulconvenios = mysqli_query($conn, "SELECT * FROM convenios");
            while ($c = mysqli_fetch_assoc($resulconvenios)) {
                echo "<option value='" . $c['id'] . "'>" . $c['nome'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Filtrar">
    </form>


    <?php

    function labuscar($conn, $id_condicao, $bairro, $convenio)
    {
        $sqllabs = "SELECT laboratorios.*, SUM(laboratorio_exame.preco_particular) AS total
            FROM laboratorios
            JOIN laboratorio_exame ON laboratorio_exame.laboratorio_id = laboratorios.id
            JOIN condicao_exame ON condicao_exame.exame_id = laboratorio_exame.exame_id
            LEFT JOIN laboratorio_convenio ON laboratorio_convenio.laboratorio_id = laboratorios.id
            WHERE condicao_exame.condicao_id = $id_condicao";

        if ($bairro != "") {
            $sqllabs .= " AND laboratorios.bairro = '$bairro'";
        }

        if ($convenio > 0) {
            $sqllabs .= " AND laboratorio_convenio.convenio_id = '$convenio'";
        }

        $sqllabs .= " GROUP BY laboratorios.id";
        return mysqli_query($conn, $sqllabs);
    }

    $reslabs = labuscar($conn, $idcondicao, $bairro, $convenio);

    if (mysqli_num_rows($reslabs) == 0) {
        echo "<p>Nenhum laboratório encontrado com essas condiçoes</p>";
    }


    while ($lab = mysqli_fetch_assoc($reslabs)) {
        echo "<p>";
        echo "<strong>" . $lab['nome'] . "</strong><br>";
        echo "Endereço: " . $lab['endereco'] . "<br>";
        echo "Bairro: " . $lab['bairro'] . "<br>";
        echo "Tempo de entrega: " . $lab['tempo_entrega_dias'] . " dia(s)<br>";
        echo "Telefone: " . $lab['telefone'] . "<br>";
        echo "Total: R$ " . $lab['total'];
        echo "</p>";
    }

    ?>

</body>

</html>
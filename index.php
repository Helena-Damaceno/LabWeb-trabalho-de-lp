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

            echo "<hr>";

            echo "<h4>Exames que você tem que fazer:</h4>";
            while ($exame = mysqli_fetch_assoc($res2)) {
                echo "<p><strong>" . $exame['nome'] . "</strong><br>";
                echo "O que mede: " . $exame['descricao'] . "<br>";
                echo "Preparo: " . $exame['instrucoes_preparo'] . "</p>";
        
        $sql3 = "SELECT laboratorios.*, SUM(laboratorio_exame.preco_particular) AS total
         FROM laboratorios
         JOIN laboratorio_exame ON laboratorio_exame.laboratorio_id = laboratorios.id
         JOIN condicao_exame ON condicao_exame.exame_id = laboratorio_exame.exame_id
         WHERE condicao_exame.condicao_id = $id_condicao";
            }

        if ($bairro != "") {
            $sql3 .= " AND laboratorios.bairro = '$bairro'";
        }
        if ($convenio > 0) {
            $sql3 .= " AND laboratorios.id IN (SELECT laboratorio_id FROM laboratorio_convenio WHERE convenio_id = $convenio)";
        }

        $sql3 .= " GROUP BY laboratorios.id";   
    
        $res3 = mysqli_query($conn, $sql3) or die(mysqli_error($conn));

        echo "<h4>Laboratórios:</h4>";
        while ($lab = mysqli_fetch_assoc($res3)) {
            echo "<p><strong>" . $lab['nome'] . "</strong><br>";
            echo $lab['endereco'] . " - " . $lab['bairro'] . "<br>";
            echo "Telefone: " . $lab['telefone'] . "<br>";
            echo "Total (particular): R$ " . number_format($lab['total'], 2, ',', '.') . "<br>";
            echo "Prazo do laudo: " . $lab['tempo_entrega_dias'] . " dia(s)</p>";
        }
    }
    }

    $sql = "SELECT * FROM condicoes_saude";
    ?>

</body>

</html>
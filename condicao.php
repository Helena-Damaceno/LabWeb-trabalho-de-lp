<?php
require_once('./conexao.php');

$idcondicao = $_GET['id'];

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
    <link rel="stylesheet" href="css/condicao.css">

</head>

<body>
    <div class="começo">
        <a href="index.php">Voltar</a>
        <script src="js/script.js"></script>
    </div>

    <div class="condicoes">
        <h2><?php echo $condicao['nome']; ?></h2>
        <p><?php echo $condicao['descricao']; ?></p>
        <p>Orientações: <?php echo $condicao['orientacoes_preparo']; ?></p>
    </div>

    <h2 class="titulo-exames">Exames que você precisa fazer para tratar sua condição:</h2>
    
  <div class="exames">
    <?php
    $sql_exames = "SELECT exames.* FROM exames
                   JOIN condicao_exame ON condicao_exame.exame_id = exames.id
                   WHERE condicao_exame.condicao_id = $idcondicao";
    $resulexames = mysqli_query($conn, $sql_exames);

    while ($exame = mysqli_fetch_assoc($resulexames)) {
        echo "<p><input type='checkbox' value='" . $exame['id'] . "' checked onchange='mudarExame(this)'> ";
        echo "<strong>" . $exame['nome'] . "</strong><br>";
        echo "O que faz: " . $exame['descricao'] . "<br>";
        echo "Instruções: " . $exame['instrucoes_preparo'] . "</p>";
    }
    ?>
 </div>


    <div class="cartao">

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
            <p> </p>

        </form>
    </div>

    <?php
    function labuscar($conn, $bairro, $convenio)
    {
        $sqllabs = "SELECT laboratorios.* FROM laboratorios";

        if ($convenio > 0) {
            $sqllabs .= " JOIN laboratorio_convenio
                  ON laboratorio_convenio.laboratorio_id = laboratorios.id
                  AND laboratorio_convenio.convenio_id = $convenio";
        }

        if ($bairro != "") {
            $bairro = mysqli_real_escape_string($conn, $bairro);
            $sqllabs .= " WHERE laboratorios.bairro = '$bairro'";
        }

        return mysqli_query($conn, $sqllabs);
    }

    $reslabs = labuscar($conn, $bairro, $convenio);

    if (mysqli_num_rows($reslabs) == 0) {
        echo "<p>Nenhum laboratório encontrado com essas condições</p>";
    }

    while ($lab = mysqli_fetch_assoc($reslabs)) {
        echo "<div class='lab'>";
        echo "<strong>" . $lab['nome'] . "</strong><br>";
        echo "Endereço: " . $lab['endereco'] . "<br>";
        echo "Bairro: " . $lab['bairro'] . "<br>";
        echo "Tempo de entrega: " . $lab['tempo_entrega_dias'] . " dia(s)<br>";
        echo "Telefone: " . $lab['telefone'] . "<br>";
        echo "<br>";
        $sqlprecos = "SELECT exames.id, exames.nome, laboratorio_exame.preco_particular
                      FROM laboratorio_exame
                      JOIN exames ON exames.id = laboratorio_exame.exame_id
                      JOIN condicao_exame ON condicao_exame.exame_id = exames.id
                      WHERE laboratorio_exame.laboratorio_id = " . $lab['id'] . "
                      AND condicao_exame.condicao_id = $idcondicao";
        $resprecos = mysqli_query($conn, $sqlprecos);



        $total = 0;
        while ($p = mysqli_fetch_assoc($resprecos)) {
            echo $p['nome'] . ": R$ <span class='preco' data-exame='" . $p['id'] . "'>"
                . $p['preco_particular'] . "</span><br>";
            $total += $p['preco_particular'];
        }

        echo "<strong>Total: R$ <span class='total'>" . number_format($total, 2, ',', '.') . "</span></strong>";
        echo "</div>";
    }
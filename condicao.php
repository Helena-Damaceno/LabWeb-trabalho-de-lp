<?php
require_once('./conexao.php');

$id_condicao = (int) $_GET['id'];

$bairro = "";
if (isset($_GET['bairro'])) {
    $bairro = mysqli_real_escape_string($conn, $_GET['bairro']);
}

$convenio = 0;
if (isset($_GET['convenio'])) {
    $convenio = (int) $_GET['convenio'];
}

$sql_condicao = "SELECT * FROM condicoes_saude WHERE id = $id_condicao";
$res_condicao = mysqli_query($conn, $sql_condicao);
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

    <h2><?php echo $condicao['nome']; ?></h2>
    <p><?php echo $condicao['descricao']; ?></p>
    <p>Orientações: <?php echo $condicao['orientacoes_preparo']; ?></p>

    <hr>

    <h4>Exames que você precisa fazer:</h4>

    <?php
    $sql_exames = "SELECT exames.* FROM exames
                   JOIN condicao_exame ON condicao_exame.exame_id = exames.id
                   WHERE condicao_exame.condicao_id = $id_condicao";
    $res_exames = mysqli_query($conn, $sql_exames);

    while ($exame = mysqli_fetch_assoc($res_exames)) {
        echo "<p><strong>" . $exame['nome'] . "</strong><br>";
        echo "O que mede: " . $exame['descricao'] . "<br>";
        echo "Preparo: " . $exame['instrucoes_preparo'] . "</p>";
    }
    ?>

    <hr>

    <h4>Laboratórios:</h4>

    <form method="GET" action="condicao.php">
        <input type="hidden" name="id" value="<?php echo $id_condicao; ?>">

        Bairro:
        <select name="bairro">
            <option value="">Todos</option>
            <?php
            $res_bairros = mysqli_query($conn, "SELECT DISTINCT bairro FROM laboratorios");
            while ($b = mysqli_fetch_assoc($res_bairros)) {
                echo "<option>" . $b['bairro'] . "</option>";
            }
            ?>
        </select>

        Convênio:
        <select name="convenio">
            <option value="0">Todos</option>
            <?php
            $res_convenios = mysqli_query($conn, "SELECT * FROM convenios");
            while ($c = mysqli_fetch_assoc($res_convenios)) {
                echo "<option value='" . $c['id'] . "'>" . $c['nome'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" value="Filtrar">
    </form>

    <?php
    $sql_labs = "SELECT laboratorios.*, SUM(laboratorio_exame.preco_particular) AS total
                 FROM laboratorios
                 JOIN laboratorio_exame ON laboratorio_exame.laboratorio_id = laboratorios.id
                 JOIN condicao_exame ON condicao_exame.exame_id = laboratorio_exame.exame_id
                 WHERE condicao_exame.condicao_id = $id_condicao";

    if ($bairro != "") {
        $sql_labs = $sql_labs . " AND laboratorios.bairro = '$bairro'";
    }

    if ($convenio > 0) {
        $sql_labs = $sql_labs . " AND laboratorios.id IN
                    (SELECT laboratorio_id FROM laboratorio_convenio WHERE convenio_id = $convenio)";
    }

    $sql_labs = $sql_labs . " GROUP BY laboratorios.id";

    $res_labs = mysqli_query($conn, $sql_labs) or die(mysqli_error($conn));

    while ($lab = mysqli_fetch_assoc($res_labs)) {
        echo "<p><strong>" . $lab['nome'] . "</strong><br>";
        echo $lab['endereco'] . " - " . $lab['bairro'] . "<br>";
        echo "Telefone: " . $lab['telefone'] . "<br>";
        echo "Total (particular): R$ " . number_format($lab['total'], 2, ',', '.') . "<br>";
        echo "Prazo do laudo: " . $lab['tempo_entrega_dias'] . " dia(s)</p>";
    }
    ?>

</body>

</html>
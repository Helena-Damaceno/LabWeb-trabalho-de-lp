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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div id="cabeçalho">

        <h1>LabWeb</h1>

        <h2>guia de sintomas, exames e laboratórios</h2>

    </div>

    <p class="informacoes">Encontre informações sobre sintomas, exames e laboratórios pertos de você!</p>

    <div class="pesquisa">

        <form method="POST">
            Sintoma: <input type="text" name="titulo">

            </select>
            <input type="submit" value="Filtrar">
        </form>

    </div>
    </div>

    </p>

    <div class="respostacondicoes">
     
        <?php
        $sqlpesquisa = "SELECT * FROM condicoes_saude WHERE nome LIKE '%$titulo%' OR descricao LIKE '%$titulo%'";
        $resultado = mysqli_query($conn, $sqlpesquisa) or die(mysqli_error($conn));

        while ($c = mysqli_fetch_assoc($resultado)) {
            echo "<a href='condicao.php?id=" . $c['id'] . "'>" . $c['nome'] . "</a><br>";
            echo "<p>clique para saber mais</p>      ";
        }

       
        if (!$resultado == false && mysqli_num_rows($resultado) == 0) {
            echo "<p>Nenhum resultado encontrado para seu sintoma</p>";
        }
        ?>


</body>

</html>
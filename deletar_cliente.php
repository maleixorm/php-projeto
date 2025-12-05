<?php
    include('lib/conexao.php');
    if (isset($_POST['confirmar'])) {
        $id = intval($_GET['id']);
        $sql = "DELETE FROM clientes WHERE id = '$id'";
        $query = $mysqli->query($sql) or die ($mysqli-> error);

        if ($query) { ?>
            <h1>Cliente deletado com sucesso!</h1>
            <p><a href="clientes.php">Clique aqui</a> para retornar à lista de clientes</p>
        <?php 
        die();
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
</head>
<body>
    <h1>Confirmação para deletar clientes</h1>
    <h2>Tem certeza que deseja deletar este cliente?</h2>
    <a href="clientes.php">Não</a>
    <form action="" method="post">
        <button type="submit" name="confirmar" value="">Sim</button>
    </form>
</body>
</html>
<?php 
    include('lib/conexao.php');

    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
        die();
    }

    $sql = "SELECT * FROM clientes";
    $query_clientes = $mysqli->query($sql) or die($mysqli->error);
    $num_clientes = $query_clientes->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
</head>
<body>
    <h1>Lista de Usuários</h1>
    <?php if ($_SESSION['admin']) { ?>
        <p><a href="cadastrar_cliente.php">Cadastrar</a></p>
    <?php } ?>
    <p>Estes são os usuários cadastrados no sistema:</p>
    <table border="1" cellpadding="10">
        <thead>
            <th>ID</th>
            <th>É admin?</th>
            <th>Foto</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Data de Nascimento</th>
            <th>Data de Cadastro</th>
            <?php if ($_SESSION['admin']) { ?>
                <th>Ações</th>
            <?php } ?>
        </thead>
        <tbody>
            <?php if($num_clientes == 0) { ?>
                <tr>
                    <td colspan="<?php if ($_SESSION['admin']) echo 9; else echo 8; ?>">Nenhum usuário cadastrado!</td>
                </tr>
            <?php } else { 
                while($cliente = $query_clientes->fetch_assoc()) {
                    $telefone = "Não Informado.";
                    if (!empty($cliente['telefone'])) {
                        $ddd = substr($cliente['telefone'], 0, 2);
                        $parte1 = substr($cliente['telefone'], 2, 5);
                        $parte2 = substr($cliente['telefone'], 7);
                        $telefone = "($ddd) $parte1-$parte2";
                    }
                    $nascimento = "Não Informada.";
                    if (!empty($cliente['nascimento'])) {
                        $tmp = explode('-', $cliente['nascimento']);
                        $tmp = array_reverse($tmp);
                        $nascimento = implode('/', $tmp);
                    }
                    $data_cadastro = date("d/m/Y - H:i", strtotime($cliente['data']));
                ?>
                <tr>
                    <td><?= $cliente['id'] ?></td>
                    <td><?php if($cliente['admin']) echo "Sim"; else echo "Não"; ?></td>
                    <td><img src="<?= $cliente['foto'] ?>" width="50"></td>
                    <td><?= $cliente['nome'] ?></td>
                    <td><?= $cliente['email'] ?></td>
                    <td><?= $telefone ?></td>
                    <td><?= $nascimento ?></td>
                    <td><?= $data_cadastro ?></td>
                    <?php if ($_SESSION['admin']) { ?>
                        <td>
                            <a href="editar_cliente.php?id=<?= $cliente['id'] ?>">Editar</a> | 
                            <a href="deletar_cliente.php?id=<?= $cliente['id'] ?>">Deletar</a>
                        </td>
                    <?php } ?>
                </tr>
            <?php }} ?>
        </tbody>
    </table>
</body>
</html>
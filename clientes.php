<?php 
    include('lib/conexao.php');
    $sql = "SELECT * FROM clientes";
    $query_clientes = $mysqli->query($sql) or die($mysqli->error);
    $num_clientes = $query_clientes->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
</head>
<body>
    <h1>Lista de Clientes</h1>
    <p><a href="/cadastrar_cliente.php">Cadastrar</a></p>
    <p>Estes são os clientes cadastrados no sistema:</p>
    <table border="1" cellpadding="10">
        <thead>
            <th>ID</th>
            <th>Foto</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Data de Nascimento</th>
            <th>Data de Cadastro</th>
            <th>Ações</th>
        </thead>
        <tbody>
            <?php if($num_clientes == 0) { ?>
                <tr>
                    <td colspan="7">Nenhum cliente cadastrado!</td>
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
                    <td><img src="<?= $cliente['foto'] ?>" width="50"></td>
                    <td><?= $cliente['nome'] ?></td>
                    <td><?= $cliente['email'] ?></td>
                    <td><?= $telefone ?></td>
                    <td><?= $nascimento ?></td>
                    <td><?= $data_cadastro ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?= $cliente['id'] ?>">Editar</a> | 
                        <a href="deletar_cliente.php?id=<?= $cliente['id'] ?>">Deletar</a>
                    </td>
                </tr>
            <?php }} ?>
        </tbody>
    </table>
</body>
</html>
<?php 
    
    include('lib/conexao.php');
    include('lib/functions.php');

    $id = intval($_GET['id']);
    
    if(count($_POST) > 0) {

        $erro = false;
        
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nascimento = $_POST['nascimento'];

        if(empty($nome)) {
            $erro = "Preencha o nome!";
        }
        
        if(empty($email) || !filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $erro = "Preencha com um e-mail válido!";
        }

        if(!empty($nascimento)) {
            if(strlen($nascimento) == 10) {
                $nascimento = (string)$nascimento;
            } else {
                $erro = "A data de nascimento deve seguir o padrão dia/mes/ano.";
            }
        }

        if (!empty($telefone)) {
            $telefone = filter_var($telefone, FILTER_SANITIZE_NUMBER_INT);
            if (strlen($telefone) != 11) {
                $erro = "O telefone deve seguir o padrão (xx) 999999999.";
            }
        }

        if ($erro) {
            echo "<p><b>Erro: $erro</b></p>";
        } else {
            $sql = "UPDATE clientes SET nome = '$nome', email = '$email', telefone = '$telefone', nascimento = '$nascimento' WHERE id = '$id'";
            $deu_certo = $mysqli->query($sql) or die($mysqli->error);
            if($deu_certo) {
                echo "<p><b>Cliente atualizado com sucesso!</b></p>";
                unset($_POST);
            }
        }        
    }
    $sql_cliente = "SELECT * FROM clientes WHERE id = '$id'";
    $query_cliente = $mysqli->query($sql_cliente) or die($mysqli->error);
    $cliente = $query_cliente->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
</head>
<body>
    <main>
        <a href="clientes.php">Voltar para a lista de clientes</a>
        <form action="" method="post">
            <div class="form-control">
                <label for="nome">Nome: </label>
                <input type="text" name="nome" id="" value="<?= $cliente['nome']; ?>">
            </div>
            <div class="form-control">
                <label for="email">Email: </label>
                <input type="email" name="email" id="" value="<?= $cliente['email']; ?>">
            </div>
            <div class="form-control">
                <label for="telefone">Telefone: </label>
                <input type="text" name="telefone" id="" value="<?= formatarTelefone($cliente['telefone']); ?>">
            </div>
            <div class="form-control">
                <label for="nascimento">Data de Nascimento: </label>
                <input type="date" name="nascimento" id="" value="<?= $cliente['nascimento']; ?>">
            </div>
            <input type="submit" value="Atualizar">
        </form>
    </main>
</body>
</html>
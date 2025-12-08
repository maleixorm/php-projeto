<?php 
    
    include('lib/conexao.php');
    include('lib/upload.php');
    include('lib/functions.php');

    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("Location: clientes.php");
        die();
    }

    $id = intval($_GET['id']);
    
    if(count($_POST) > 0) {

        $erro = false;
        
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nascimento = $_POST['nascimento'];
        $senha = $_POST['senha'];
        $sql_extra = "";
        $admin = $_POST['admin'];

        if (!empty($senha)) {
            if (strlen($senha) < 6 && strlen($senha) > 16) {
                $erro = "A senha deve ter entre 6 e 16 caracteres";
            } else {
                $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
                $sql_extra = "senha = '$senha_criptografada',";
            }
        }

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

        if (isset($_FILES['foto'])) {
            $arq = $_FILES['foto'];
            $path = enviarArquivo($arq['error'], $arq['size'], $arq['name'], $arq['tmp_name']);
            if ($path == false) {
                $erro = "Falha ao enviar o arquivo. Tente novamente!";
            } else {
                $sql_extra .= "foto = '$path', ";
            }
            if (!empty($_POST['foto_antiga'])) {
                unlink($_POST['foto_antiga']);
            }
        }

        if ($erro) {
            echo "<p><b>Erro: $erro</b></p>";
        } else {
            $sql = "UPDATE clientes SET nome = '$nome', email = '$email', $sql_extra telefone = '$telefone', nascimento = '$nascimento', admin = '$admin' WHERE id = '$id'";
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
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-control">
                <label for="nome">Nome: </label>
                <input type="text" name="nome" id="" value="<?= $cliente['nome']; ?>">
            </div>
            <div class="form-control">
                <label for="email">Email: </label>
                <input type="email" name="email" id="" value="<?= $cliente['email']; ?>">
            </div>
            <div class="form-control">
                <label for="senha">Senha: </label>
                <input type="text" name="senha">
            </div>
            <div class="form-control">
                <label for="telefone">Telefone: </label>
                <input type="text" name="telefone" id="" value="<?= formatarTelefone($cliente['telefone']); ?>">
            </div>
            <div class="form-control">
                <label for="nascimento">Data de Nascimento: </label>
                <input type="date" name="nascimento" id="" value="<?= $cliente['nascimento']; ?>">
            </div>
            <input type="hidden" name="foto_antiga" value="<?= $cliente['foto']; ?>">
            <?php if ($cliente['foto']) { ?>
                <div class="form-control">
                    <label for="foto">Foto Atual: </label>
                    <img src="<?= $cliente['foto']; ?>" width="50">
                </div>
            <?php } ?>
            <div class="form-control">
                <label for="foto">Nova foto do Usuário: </label>
                <input type="file" name="foto">
            </div>
            <div class="form-control">
                <label for="admin">Tipo de Usuário: </label>
                <input type="radio" value="1" name="admin">Administrador
                <input type="radio" value="0" name="admin" checked>Cliente
            </div>
            <input type="submit" value="Atualizar">
        </form>
    </main>
</body>
</html>
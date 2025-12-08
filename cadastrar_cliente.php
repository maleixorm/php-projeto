<?php 
    if(count($_POST) > 0) {

        include('lib/conexao.php');
        include('lib/upload.php');
        include('lib/mail.php');

        $erro = false;
        
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nascimento = $_POST['nascimento'];
        $senha_descriptografada = $_POST['senha'];
        $admin = $_POST['admin'];
        
        if (strlen($senha_descriptografada) < 6 && strlen($senha_descriptografada) > 16) {
            $erro = "A senha deve ter entre 6 e 16 caracteres";
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

        $path = "";
        if (isset($_FILES['foto'])) {
            $arq = $_FILES['foto'];
            $path = enviarArquivo($arq['error'], $arq['size'], $arq['name'], $arq['tmp_name']);
            if ($path == false) {
                $erro = "Falha ao enviar o arquivo. Tente novamente!";
            }
        }
        
        if ($erro) {
            echo "<p><b>Erro: $erro</b></p>";
        } else {
            $senha = password_hash($senha_descriptografada, PASSWORD_DEFAULT);
            $sql = "INSERT INTO clientes (nome, email, senha, telefone, nascimento, data, foto, admin) 
                    VALUES ('$nome', '$email', '$senha','$telefone', '$nascimento', NOW(), '$path', '$admin')";
            $deu_certo = $mysqli->query($sql) or die($mysqli->error);
            if($deu_certo) {
                enviar_email($email, "Conta criada no site.", "
                <h1>Parabéns!</h1>
                <p>Sua conta foi criada com sucesso!</p>
                <p>
                    <strong>Login: </strong> $email<br>
                    <strong>Senha: </strong> $senha_descriptografada
                </p>
                ");
                echo "<p><b>Cliente cadastrado com sucesso!</b></p>";
                unset($_POST);
            }
        }        
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
</head>
<body>
    <main>
        <a href="clientes.php">Voltar para a lista de clientes</a>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-control">
                <label for="nome">Nome: </label>
                <input type="text" name="nome" id="" value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>">
            </div>
            <div class="form-control">
                <label for="email">Email: </label>
                <input type="email" name="email" id="" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
            </div>
            <div class="form-control">
                <label for="telefone">Telefone: </label>
                <input type="text" name="telefone" id="" placeholder="(xx) 99999-9999" value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?>">
            </div>
            <div class="form-control">
                <label for="nascimento">Data de Nascimento: </label>
                <input type="date" name="nascimento" id="" value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>">
            </div>
            <div class="form-control">
                <label for="senha">Senha: </label>
                <input type="password" name="senha" id="" value="<?php if(isset($_POST['senha'])) echo $_POST['senha']; ?>">
            </div>
            <div class="form-control">
                <label for="foto">Foto do Usuário: </label>
                <input type="file" name="foto">
            </div>
            <div class="form-control">
                <label for="admin">Tipo de Usuário: </label>
                <input type="radio" value="1" name="admin">Administrador
                <input type="radio" value="0" name="admin" checked>Cliente
            </div>
            <input type="submit" value="Cadastrar">
        </form>
    </main>
</body>
</html>
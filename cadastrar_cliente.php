<?php 
    if(count($_POST) > 0) {

        include('conexao.php');
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
            $sql = "INSERT INTO clientes (nome, email, telefone, nascimento, data) VALUES ('$nome', '$email', '$telefone', '$nascimento', NOW())";
            $deu_certo = $mysqli->query($sql) or die($mysqli->error);
            if($deu_certo) {
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
        <form action="" method="post">
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
            <input type="submit" value="Cadastrar">
        </form>
    </main>
</body>
</html>
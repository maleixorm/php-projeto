<?php 

    if (isset($_POST['email']) && isset($_POST['senha'])) {
        include('lib/conexao.php');
        $email = $mysqli->escape_string($_POST['email']);
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM clientes WHERE email = '$email'";
        $query = $mysqli->query($sql) or die($mysqli->error);


        if ($query->num_rows == 0) {
            echo "O e-mail informado está incorreto!";
        } else {
            $usuario = $query->fetch_assoc();
            if (!password_verify($senha, $usuario['senha'])) {
                echo "A senha informada está incorreta!";
            } else {
                if (!isset($_SESSION)) {
                    session_start();
                    $_SESSION['usuario'] = $usuario['id'];
                    $_SESSION['admin'] = $usuario['admin'];
                    header("Location: clientes.php");
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Clientes - Entrar</title>
</head>
<body>
    <h1>Sistema de Clientes</h1>
    <form action="" method="post">
        <div class="form-control">
            <label for="email">Email: </label>
            <input type="email" name="email">
        </div>
        <div class="form-control">
            <label for="senha">Senha: </label>
            <input type="password" name="senha">
        </div>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
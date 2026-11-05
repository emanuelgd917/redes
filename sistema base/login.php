<?php
session_start();
include("conexao.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha']; 
    
    $sql = "SELECT id, nome, senha FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            header("Location: painel.php"); 
            exit();
        } else {
            $erro = "E-mail ou senha incorretos!";
        }
    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Entrar na Conta</title>
</head>
<body>
    <h2>Entrar na Conta</h2>
    <?php if(isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    
    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Digite seu e-mail cadastrado" required><br><br>
        
        <input type="password" name="senha" placeholder="Digite sua senha" required><br><br>
        
        <button type="submit">Entrar</button>
    </form>
    <br>
    <a href="index.php">Crie uma conta</a>
</body>
</html>

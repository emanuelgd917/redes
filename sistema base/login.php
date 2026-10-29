<?php
session_start();
include("conexao.php"); // Certifique-se de que seu arquivo de conexão usa a variável $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Busca o usuário na tabela 'clientes' pelo e-mail
    $sql = "SELECT id, nome FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        // Guarda os dados na sessão para saber quem está logado
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        
        // Redireciona para o painel de listas e metas
        header("Location: painel.php"); 
        exit();
    } else {
        $erro = "E-mail não encontrado no sistema!";
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
        <button type="submit">Entrar</button>
    </form>
    <br>
    <a href="index.php">Não tem conta? Cadastre-se aqui</a>
</body>
</html>
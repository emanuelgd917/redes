<?php
include 'conexao.php'; 

// Inicia a sessão no topo do arquivo para garantir o login automático
session_start();

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

// 1. Pega a senha enviada pelo novo campo do formulário e criptografa
$senha_pura = $_POST['senha'];
$senha_criptografada = password_hash($senha_pura, PASSWORD_DEFAULT);

// 2. Atualiza a query SQL para incluir a coluna e o valor da senha
$sql = "INSERT INTO clientes (nome, email, telefone, senha) VALUES ('$nome', '$email', '$telefone', '$senha_criptografada')";

if ($conn->query($sql) === TRUE) {
    
    // 3. Salva os dados do novo usuário na sessão para ele já entrar logado
    $usuario_id = $conn->insert_id; 
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['usuario_nome'] = $nome;

    // 4. Mostra o alerta de sucesso e envia direto para o painel.php via JavaScript
    // (Isso resolve o conflito de redirecionamento do PHP)
    echo "<script>
            alert('Sucesso! Cadastro realizado com sucesso.');
            window.location.href = 'painel.php';
          </script>";
    exit();

} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>

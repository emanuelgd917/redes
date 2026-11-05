<?php
include 'conexao.php'; 

session_start();

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$senha_pura = $_POST['senha'];
$senha_criptografada = password_hash($senha_pura, PASSWORD_DEFAULT);

$sql = "INSERT INTO clientes (nome, email, telefone, senha) VALUES ('$nome', '$email', '$telefone', '$senha_criptografada')";

if ($conn->query($sql) === TRUE) {
    
    $usuario_id = $conn->insert_id; 
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['usuario_nome'] = $nome;

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

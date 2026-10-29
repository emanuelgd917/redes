<?php
$servidor = "localhost";
$usuario = "root";
$senha = ""; 
$banco = "sistema_base"; 

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}
?>
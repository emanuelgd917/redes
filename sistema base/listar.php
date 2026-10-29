<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listagem - Sistema Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container">
    <h3 class="mb-4">Cadastros no Banco de Dados</h3>
    
    <table class="table table-striped table-hover shadow bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM clientes";
            $resultado = $conn->query($sql);

            if ($resultado->num_rows > 0) {
                while($linha = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $linha['id'] . "</td>
                            <td>" . $linha['nome'] . "</td>
                            <td>" . $linha['email'] . "</td>
                            <td>" . $linha['telefone'] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>Nenhum registo encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <a href="index.php" class="btn btn-primary">Voltar</a>
</div>

</body>
</html>
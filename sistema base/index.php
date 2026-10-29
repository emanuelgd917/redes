<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Sistema Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5"> 
<div class="container">
    <div class="card shadow p-4 mb-4">
        <h3 class="mb-3">Novo Cadastro</h3>
        
        <a href="login.php" style="font-weight: bold; font-size: 1.1em; color: blue;">👉 Já tem uma conta? Clique aqui para entrar!</a>

        <form action="salvar.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" class="form-control" name="telefone">
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" class="form-control" name="senha" required>
            </div>
            <div class="mb-3 text-center">
                <a href="login.php" class="fw-bold text-decoration-none">Já tem uma conta? Clique aqui para entrar!</a>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
    <a href="listar.php" class="btn btn-warning">Dados Cadastrados</a>
</div>

</body>
</html>
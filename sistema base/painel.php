<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];


if (isset($_POST['acao']) && $_POST['acao'] == 'salvar_meta') {
    $meta_livros = intval($_POST['meta_livros']);

    $stmt = $conn->prepare("INSERT INTO metas (usuario_id, meta_livros) VALUES (?, ?) ON DUPLICATE KEY UPDATE meta_livros = ?");
    $stmt->bind_param("iii", $usuario_id, $meta_livros, $meta_livros);
    $stmt->execute();
    
    header("Location: painel.php");
    exit();
}

if (isset($_POST['acao']) && $_POST['acao'] == 'remover_meta') {
    $stmt = $conn->prepare("DELETE FROM metas WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    
    header("Location: painel.php");
    exit();
}

if (isset($_POST['acao']) && $_POST['acao'] == 'adicionar_livro') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $status = $_POST['status']; 

    $stmt = $conn->prepare("INSERT INTO livros (usuario_id, titulo, autor, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $usuario_id, $titulo, $autor, $status);
    $stmt->execute();
    
    header("Location: painel.php");
    exit();
}

if (isset($_POST['acao']) && $_POST['acao'] == 'marcar_lido') {
    $id_livro = intval($_POST['id_livro']);

    $stmt = $conn->prepare("UPDATE livros SET status = 'lido' WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id_livro, $usuario_id);
    $stmt->execute();
    
    header("Location: painel.php");
    exit();
}

if (isset($_POST['acao']) && $_POST['acao'] == 'remover_livro') {
    $id_livro = intval($_POST['id_livro']);

    $stmt = $conn->prepare("DELETE FROM livros WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id_livro, $usuario_id);
    $stmt->execute();
    
    header("Location: painel.php");
    exit();
}


$lista_quero = $conn->query("SELECT * FROM livros WHERE usuario_id = $usuario_id AND status = 'quero_ler'");
$lista_lidos = $conn->query("SELECT * FROM livros WHERE usuario_id = $usuario_id AND status = 'lido'");

$meta_query = $conn->query("SELECT meta_livros FROM metas WHERE usuario_id = $usuario_id");
$meta_dados = $meta_query->fetch_assoc();
$meta_definida = $meta_dados ? $meta_dados['meta_livros'] : 0;

$total_lidos = $lista_lidos->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Painel de Leitura</title>
    <style>
        .listas-container { display: flex; gap: 40px; margin-top: 20px; }
        .coluna { flex: 1; border: 1px solid #ccc; padding: 15px; border-radius: 5px; }
        .meta-box { background: #f4f4f4; padding: 15px; margin-bottom: 25px; border-radius: 5px; }
        .meta-acoes { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .botoes-acoes { display: flex; align-items: center; gap: 10px; }
    </style>
</head>
<body>
    <h1>Olá, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
    <a href="logout.php">Sair da Conta</a>
    <hr>

    <div class="meta-box">
        <h3>🏆 Minha Meta de Leitura</h3>
        
        <div class="meta-acoes">
            <form method="POST" action="painel.php" style="display: inline;">
                <input type="hidden" name="acao" value="salvar_meta">
                Definir meta: <input type="number" name="meta_livros" value="<?php echo $meta_definida; ?>" min="1" required> livros.
                <button type="submit">Salvar Meta</button>
            </form>

            <?php if ($meta_definida > 0): ?>
                <form method="POST" action="painel.php" style="display: inline;">
                    <input type="hidden" name="acao" value="remover_meta">
                    <button type="submit" style="background-color: #ff4d4d; color: white; border: none; padding: 4px 10px; border-radius: 3px; cursor: pointer;" onclick="return confirm('Tem certeza que deseja apagar sua meta de leitura?')">Excluir Meta</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($meta_definida > 0): ?>
            <p>Progresso: Você leu <strong><?php echo $total_lidos; ?></strong> de <strong><?php echo $meta_definida; ?></strong> livros!</p>
            <progress value="<?php echo $total_lidos; ?>" max="<?php echo $meta_definida; ?>"></progress>
        <?php else: ?>
            <p style="color: #666; font-style: italic;">Você ainda não definiu uma meta de leitura.</p>
        <?php endif; ?>
    </div>

    <div>
        <h3>🔍 Adicionar Livro</h3>
        <form method="POST" action="painel.php">
            <input type="hidden" name="acao" value="adicionar_livro">
            <input type="text" name="titulo" placeholder="Título do Livro" required>
            <input type="text" name="autor" placeholder="Autor">
            <select name="status">
                <option value="quero_ler">Quero Ler</option>
                <option value="lido">Já Li</option>
            </select>
            <button type="submit">Adicionar à Lista</button>
        </form>
    </div>

    <div class="listas-container">
        <div class="coluna">
            <h3>❤️ Quero Ler (<?php echo $lista_quero->num_rows; ?>)</h3>
            <ul>
                <?php while($livro = $lista_quero->fetch_assoc()): ?>
                    <li style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            <strong><?php echo $livro['titulo']; ?></strong> - <?php echo $livro['autor']; ?>
                        </span>
                        
                        <div class="botoes-acoes">
                            <form method="POST" action="painel.php" style="display:inline;">
                                <input type="hidden" name="acao" value="marcar_lido">
                                <input type="hidden" name="id_livro" value="<?php echo $livro['id']; ?>">
                                <button type="submit" style="background: none; border: none; cursor: pointer; color: green; font-size: 1.1em;" title="Marcar como lido">✔</button>
                            </form>

                            <form method="POST" action="painel.php" style="display:inline;">
                                <input type="hidden" name="acao" value="remover_livro">
                                <input type="hidden" name="id_livro" value="<?php echo $livro['id']; ?>">
                                <button type="submit" style="background: none; border: none; cursor: pointer; color: red;" onclick="return confirm('Deseja mesmo remover este livro da sua lista?')">❌</button>
                            </form>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="coluna">
            <h3>🏅 Já Lidos (<?php echo $total_lidos; ?>)</h3>
            <ul>
                <?php while($livro = $lista_lidos->fetch_assoc()): ?>
                    <li style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            <strong><?php echo $livro['titulo']; ?></strong> - <?php echo $livro['autor']; ?>
                        </span>
                        <form method="POST" action="painel.php" style="display:inline; margin-left: 10px;">
                            <input type="hidden" name="acao" value="remover_livro">
                            <input type="hidden" name="id_livro" value="<?php echo $livro['id']; ?>">
                            <button type="submit" style="background: none; border: none; cursor: pointer; color: red;" onclick="return confirm('Deseja mesmo remover este livro da sua lista?')">❌</button>
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</body>
</html>

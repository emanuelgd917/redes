<?php
session_start();
include("conexao.php");

// Proteção: Se o usuário tentar acessar direto sem logar, joga ele para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// ==========================================
// PROCESSAMENTO: SALVAR METAS E LIVROS (NA PRÓPRIA PÁGINA)
// ==========================================

// 1. Se a ação for Salvar Meta
if (isset($_POST['acao']) && $_POST['acao'] == 'salvar_meta') {
    $meta_livros = intval($_POST['meta_livros']);

    $stmt = $conn->prepare("INSERT INTO metas (usuario_id, meta_livros) VALUES (?, ?) ON DUPLICATE KEY UPDATE meta_livros = ?");
    $stmt->bind_param("iii", $usuario_id, $meta_livros, $meta_livros);
    $stmt->execute();
    
    // Recarrega a página para atualizar os dados na tela
    header("Location: painel.php");
    exit();
}

// 2. Se a ação for Adicionar Livro
if (isset($_POST['acao']) && $_POST['acao'] == 'adicionar_livro') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $status = $_POST['status']; 

    $stmt = $conn->prepare("INSERT INTO livros (usuario_id, titulo, autor, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $usuario_id, $titulo, $autor, $status);
    $stmt->execute();
    
    // Recarrega a página para atualizar os dados na tela
    header("Location: painel.php");
    exit();
}

// ==========================================
// BUSCA DE DADOS PARA EXIBIR NA TELA
// ==========================================

// 1. Busca os livros "Quero Ler" do usuário logado
$lista_quero = $conn->query("SELECT * FROM livros WHERE usuario_id = $usuario_id AND status = 'quero_ler'");

// 2. Busca os livros "Já Li" do usuário logado
$lista_lidos = $conn->query("SELECT * FROM livros WHERE usuario_id = $usuario_id AND status = 'lido'");

// 3. Busca a Meta de leitura do usuário
$meta_query = $conn->query("SELECT meta_livros FROM metas WHERE usuario_id = $usuario_id");
$meta_dados = $meta_query->fetch_assoc();
$meta_definida = $meta_dados ? $meta_dados['meta_livros'] : 0;

// Conta quantos livros já foram lidos para calcular a barra de progresso
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
    </style>
</head>
<body>
    <h1>Olá, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
    <a href="logout.php">Sair da Conta</a>
    <hr>

    <!-- SEÇÃO DA META -->
    <div class="meta-box">
        <h3>🎯 Minha Meta de Leitura</h3>
        <form method="POST" action="painel.php">
            <input type="hidden" name="acao" value="salvar_meta">
            Definir meta: <input type="number" name="meta_livros" value="<?php echo $meta_definida; ?>" min="1" required> livros.
            <button type="submit">Salvar Meta</button>
        </form>

        <?php if ($meta_definida > 0): ?>
            <p>Progresso: Você leu <strong><?php echo $total_lidos; ?></strong> de <strong><?php echo $meta_definida; ?></strong> livros!</p>
            <progress value="<?php echo $total_lidos; ?>" max="<?php echo $meta_definida; ?>"></progress>
        <?php else: ?>
            <p>Você ainda não definiu uma meta de leitura.</p>
        <?php endif; ?>
    </div>

    <!-- SEÇÃO DE ADICIONAR LIVRO -->
    <div>
        <h3>➕ Adicionar Livro</h3>
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

    <!-- SEÇÃO DAS LISTAS -->
    <div class="listas-container">
        <div class="coluna">
            <h3>📚 Quero Ler (<?php echo $lista_quero->num_rows; ?>)</h3>
            <ul>
                <?php while($livro = $lista_quero->fetch_assoc()): ?>
                    <li><strong><?php echo $livro['titulo']; ?></strong> - <?php echo $livro['autor']; ?></li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="coluna">
            <h3>✅ Já Lidos (<?php echo $total_lidos; ?>)</h3>
            <ul>
                <?php while($livro = $lista_lidos->fetch_assoc()): ?>
                    <li><strong><?php echo $livro['titulo']; ?></strong> - <?php echo $livro['autor']; ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

$usuario_id = $_SESSION['user_id'];
$file_id = isset($_GET['file']) ? intval($_GET['file']) : 0;

// Verifica se o arquivo pertence ao usuário
$query = "SELECT * FROM arquivos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $file_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$arquivo = $result->fetch_assoc();

if (!$arquivo) {
    echo "Arquivo não encontrado ou você não tem permissão para editá-lo.";
    exit;
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = $_POST['nome_arquivo'];
    $novo_conteudo = $_POST['conteudo'];
    
    $update_query = "UPDATE arquivos SET nome_arquivo = ?, conteudo = ? WHERE id = ? AND usuario_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssii", $novo_nome, $novo_conteudo, $file_id, $usuario_id);

    if ($update_stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Erro ao atualizar o arquivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Arquivo</title>
    <link rel="stylesheet" href="styleedit.css">
</head>
<body>
    <div class="edit-container">
        <h2>Editar Arquivo</h2>
        <form action="edit_file.php?file=<?php echo $file_id; ?>" method="POST">
            <label for="nome_arquivo">Nome do Arquivo:</label>
            <input type="text" name="nome_arquivo" id="nome_arquivo" value="<?php echo htmlspecialchars($arquivo['nome_arquivo']); ?>" required>

            <label for="conteudo">Conteúdo do Arquivo:</label>
            <textarea name="conteudo" id="conteudo" rows="10" required><?php echo htmlspecialchars($arquivo['conteudo']); ?></textarea>

            <div class="buttons">
                <button type="submit" class="btn-save">Salvar Alterações</button>
                <a href="dashboard.php" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

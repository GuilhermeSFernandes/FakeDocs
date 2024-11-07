<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

$usuario_id = $_SESSION['user_id'];

// Processa o formulário de criação de arquivo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_arquivo = $_POST['nome_arquivo'];
    $conteudo = $_POST['conteudo'];
    
    $query = "INSERT INTO arquivos (usuario_id, nome_arquivo, conteudo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $usuario_id, $nome_arquivo, $conteudo);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Erro ao criar o arquivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Novo Arquivo</title>
    <link rel="stylesheet" href="stylecreate.css">
</head>
<body>
    <div class="create-container">
        <h2>Criar Novo Arquivo</h2>
        <form action="create_file.php" method="POST">
            <label for="nome_arquivo">Nome do Arquivo:</label>
            <input type="text" name="nome_arquivo" id="nome_arquivo" required>

            <label for="conteudo">Conteúdo do Arquivo:</label>
            <textarea name="conteudo" id="conteudo" rows="10" required></textarea>

            <div class="buttons">
                <button type="submit" class="btn-create">Criar Arquivo</button>
                <a href="dashboard.php" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

$usuario_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];

    // Verifica se houve erro no upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Erro ao fazer upload do arquivo.");
    }

    // Define o diretório onde os arquivos serão armazenados
    $uploadDir = 'uploads/';
    $uploadFilePath = $uploadDir . basename($file['name']);

    // Move o arquivo para o diretório de uploads
    if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
        // Prepare a consulta para inserir os dados na tabela
        $query = "INSERT INTO arquivos (usuario_id, nome_arquivo, conteudo, data_criacao) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $usuario_id, $file['name'], $uploadFilePath); // Ajuste conforme necessário
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Arquivo enviado com sucesso!";
        } else {
            echo "Erro ao salvar no banco de dados.";
        }

        $stmt->close();
    } else {
        echo "Erro ao mover o arquivo para o diretório.";
    }
}

// Redirect após a operação
header("Location: dashboard.php");
exit;
?>

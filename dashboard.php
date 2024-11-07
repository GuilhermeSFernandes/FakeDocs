<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

$usuario_id = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Usuário';

// Função para listar arquivos do usuário
function listarArquivos($usuario_id) {
    global $conn;
    $query = "SELECT * FROM arquivos WHERE usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $arquivos = [];
    while ($row = $result->fetch_assoc()) {
        $arquivos[] = $row;
    }
    $stmt->close();
    return $arquivos;
}

// Chama a função para listar arquivos
$arquivos = listarArquivos($usuario_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle</title>
    <link rel="stylesheet" href="styledash.css">
    <script>
        function confirmDeletion(fileId) {
            if (confirm("Tem certeza de que deseja excluir este arquivo?")) {
                window.location.href = "file_crud.php?delete=" + fileId;
            }
        }

        function toggleUserMenu() {
            document.getElementById("user-menu").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.user-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Painel de Controle</h1>
            <div class="user-profile">
                <div class="user-icon" onclick="toggleUserMenu()">⚪</div>
                <div id="user-menu" class="dropdown-content">
                    <a href="edit_user.php">Editar Perfil</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </header>

        <h2>Bem-vindo, <?php echo $username; ?>!</h2>

        <div class="upload-section">
    <h3>Upload de Arquivos</h3>
    <form action="upload_files.php" method="POST" enctype="multipart/form-data">
        <!-- Label para simular o botão de seleção de arquivo -->
        <label for="file-upload">Escolher Arquivo</label>
        <input type="file" name="file" id="file-upload" required>
        <button type="submit">Upload</button>
    </form>
</div>

        <h3>Seus Arquivos</h3>
        <ul class="file-list">
    <?php if (empty($arquivos)): ?>
        <li>Você não possui arquivos.</li>
    <?php else:
        foreach ($arquivos as $arquivo): ?>
            <li>
                <?php echo htmlspecialchars($arquivo['nome_arquivo']); ?>
                <div class="btn-container">
                    <a href="edit_file.php?file=<?php echo urlencode($arquivo['id']); ?>" class="btn btn-editar">Editar</a>
                    <button onclick="confirmDeletion('<?php echo urlencode($arquivo['id']); ?>')" class="btn btn-delete">Remover</button>
                </div>
            </li>
    <?php endforeach;
    endif; ?>
</ul>

        <!-- Botão para criar novo arquivo -->
        <div class="create-file">
            <a href="create_file.php" class="btn-create">Criar Novo Arquivo</a>
        </div>
    </div>

</body>
</html>

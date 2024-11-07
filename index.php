<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="welcome-box">
            <h1>Bem-vindo</h1>
            <?php if (isset($_SESSION['username'])): ?>
                <p>Olá, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Bem-vindo de volta.</p>
                <ul class="menu">
                    <li><a href="dashboard.php">Painel de Controle</a></li>
                    <li><a href="file_crud.php">Gerenciamento de Arquivos</a></li>
                    <li><a href="upload_files.php">Upload de Arquivos</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            <?php else: ?>
                <p>Por favor, faça login ou cadastre-se para acessar o sistema.</p>
                <ul class="menu">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Cadastro</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

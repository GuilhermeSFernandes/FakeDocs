<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("config.php"); // Inclua o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta SQL para selecionar o usuário
    $query = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($query);

    // Verifique se a instrução foi preparada com sucesso
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Vincule os parâmetros e execute a instrução
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifique se um usuário foi encontrado
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifique a senha
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Defina a variável de sessão
            $_SESSION['username'] = $username; // Defina o nome de usuário na sessão

            // Depuração: Confirme se a sessão foi criada
            echo "Sessão iniciada para o usuário ID: " . $_SESSION['user_id'];

            // Redirecionar para o dashboard
            header("Location: dashboard.php");
            exit; // Garanta que nenhum outro código seja executado
        } else {
            $error_message = "Senha incorreta."; // Mensagem de senha incorreta
        }
    } else {
        $error_message = "Usuário não encontrado."; // Mensagem de usuário não encontrado
    }

    $stmt->close(); // Feche a instrução
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label>Usuário:</label>
            <input type="text" name="username" required>
            <label>Senha:</label>
            <input type="password" name="password" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>

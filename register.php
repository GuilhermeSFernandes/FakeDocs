<?php
include("config.php");

$error_message = ""; // Variável para armazenar mensagens de erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar se o nome de usuário já existe
    $check_query = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nome de usuário já existe
        $error_message = "Nome de usuário já está em uso. Escolha outro nome.";
    } else {
        // Nome de usuário não existe, então prossegue com o cadastro
        $query = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "Usuário registrado com sucesso!";
        } else {
            $error_message = "Erro ao registrar o usuário.";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <label>Usuário:</label>
            <input type="text" name="username" required>
            <label>Senha:</label>
            <input type="password" name="password" required>
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("config.php");
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_username = $_POST['username'];
    $nova_senha = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "UPDATE usuarios SET username = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $novo_username, $nova_senha, $user_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['username'] = $novo_username;
    header("Location: dashboard.php");
    exit;
}

$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Editar Usuário</h2>
        <form method="POST" action="edit_user.php">
            <label>Novo Nome de Usuário:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label>Nova Senha:</label>
            <input type="password" name="password" required>
            <button type="submit">Salvar</button>
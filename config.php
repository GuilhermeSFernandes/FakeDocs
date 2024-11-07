<?php
// Configurações do banco de dados
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'fake_docs';

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se a conexão falhou e exibe mensagem de erro
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}
?>

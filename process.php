<?php
// Dados do banco
$host = 'localhost';
$db = 'meu_banco';
$user = 'meu_usuario';
$pass = 'minha_senha';

// Conexão com MariaDB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recebe os dados
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';

// Validação simples
if (empty($nome) || empty($email)) {
    echo "Por favor, preencha todos os campos.";
    exit;
}

// Prepara e executa o insert
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
$stmt->bind_param("ss", $nome, $email);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

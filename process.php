<?php
header('Content-Type: application/json; charset=utf-8');

// Dados do banco
$host = 'localhost';
$db = 'meu_banco';
$user = 'meu_usuario';
$pass = 'minha_senha';

// Conexão
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Erro de conexão: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe dados do POST
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    header('Content-Type: text/plain; charset=utf-8');

    // Validação simples
    if (empty($nome) || empty($email)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Inserir no banco
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $email);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit;
}

// Se não for POST, retorna a lista de usuários (GET)
$result = $conn->query("SELECT id, nome, email FROM usuarios ORDER BY id DESC");
$usuarios = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

echo json_encode($usuarios);

$conn->close();

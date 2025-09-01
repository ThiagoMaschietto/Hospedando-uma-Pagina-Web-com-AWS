<?php
// NÃO coloque o header aqui

// Dados do banco
$host = 'localhost';
$db = 'meu_banco';
$user = 'meu_usuario';
$pass = 'minha_senha';

// Conexão
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    // Para erros de conexão, é bom retornar JSON com um status de erro
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8'); // Informa que o erro vem em JSON
    echo json_encode(["error" => "Erro de conexão: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    // Define o header como TEXTO, pois o JS espera texto.
    header('Content-Type: text/plain; charset=utf-8');

    if (empty($nome) || empty($email)) {
        http_response_code(400); // Bad Request
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $email);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Erro ao cadastrar: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit;
}

// Se chegou até aqui, é um GET.
// Define o header como JSON, pois o JS espera JSON.
header('Content-Type: application/json; charset=utf-8');

$result = $conn->query("SELECT id, nome, email FROM usuarios ORDER BY id DESC");
$usuarios = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

echo json_encode($usuarios);

$conn->close();
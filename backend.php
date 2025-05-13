<?php
session_start();

header('Content-Type: application/json');

// Conexão com banco
$db = new PDO('sqlite:banco.sqlite');

// Criação da tabela (caso não exista)
$db->exec("CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT,
    email TEXT UNIQUE,
    senha TEXT
)");

// Verifica se é cadastro ou login
$action = $_POST['action'] ?? '';

// === CADASTRO ===
if ($action !== 'login') {
    $nome = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (strlen($nome) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos.']);
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senhaHash]);
        echo json_encode(['status' => 'ok']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Email já cadastrado ou erro no banco.']);
    }
    exit;
}

// === LOGIN ===
$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Credenciais inválidas.']);
    exit;
}

$stmt = $db->prepare("SELECT nome, senha FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario'] = [
        'email' => $email,
        'nome' => $usuario['nome']
        ];
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Email ou senha incorretos.']);
}

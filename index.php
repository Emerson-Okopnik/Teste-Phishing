<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "ALO";
// Validação segura das entradas
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if (!$email) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos ou ausentes.']);
    exit;
}

$scriptUrl = 'https://script.google.com/macros/s/AKfycbw5R4SX0qVbo8mvYS6DVkXCfur8fogTuoZEiKpZCz6opFEbpRJ6id8BkB31Cb-WP1gA/exec';

$dados = [
    'email' => $email
];

if (!function_exists('curl_init')) {
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
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao conectar com o Google Apps Script: ' . curl_error($ch)]);
} else {
    echo json_encode(['status' => 'ok', 'mensagem' => 'Dados enviados com sucesso.', 'retorno_google' => $response]);
}

curl_close($ch);
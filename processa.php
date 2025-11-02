<?php
// processa.php

// Ativar relatórios de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir configuração do banco e chave da API
include 'config.php';
include 'config.local.php';

// Inicializar variáveis
$resumo = "";
$erro = "";
$historico = null;

// Processar formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = trim($_POST['texto']);
    
    if (empty($texto)) {
        $erro = "Erro: O texto não pode estar vazio.";
    } elseif (strlen($texto) > 5000) {
        $erro = "Erro: Texto muito longo (máximo 5000 caracteres).";
    } else {
        // Código de requisição à API
        $url = "https://api-inference.huggingface.co/models/facebook/bart-large-cnn";
        $data = json_encode([
            "inputs" => $texto,
            "parameters" => ["max_length" => 100, "min_length" => 30]
        ]);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json"
            ]
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            $erro = "Erro de conexão: $curlError";
        } elseif ($httpCode != 200) {
            $erro = "Erro HTTP $httpCode: $response";
        } else {
            $result = json_decode($response, true);
            if (isset($result[0]['summary_text'])) {
                $resumo = $result[0]['summary_text'];
                
                // Salvar no banco
                $stmt = $conn->prepare("INSERT INTO historico (texto_original, resumo) VALUES (?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ss", $texto, $resumo);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $erro = "Erro na resposta da API.";
            }
        }
    }
}

// Buscar histórico
$historico = $conn->query("SELECT * FROM historico ORDER BY id DESC LIMIT 5");

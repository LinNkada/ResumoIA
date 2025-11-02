<?php
// Ativar relatórios de erro (para debug)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir configuração do banco e chave da API
include 'config.php';
include 'config.local.php';

// Inicializar variáveis
$resumo = "";
$erro = "";

// Processar formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = trim($_POST['texto']);

    if (empty($texto)) {
        $erro = "Erro: O texto não pode estar vazio.";
    } elseif (strlen($texto) > 5000) {
        $erro = "Erro: Texto muito longo (máximo 5000 caracteres).";
    } else {
        // URL da API Hugging Face
        $url = "https://api-inference.huggingface.co/models/facebook/bart-large-cnn";

        // Dados da requisição
        $data = json_encode([
            "inputs" => $texto,
            "parameters" => [
                "max_length" => 100,
                "min_length" => 30
            ]
        ]);

        // Requisição cURL
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json"
            ],
            CURLOPT_TIMEOUT => 30
        ]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); // tempo para conectar (segundos)
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // tempo máximo total da operação

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            $erro = "Erro de conexão cURL: " . $curlError;
        } elseif ($httpCode != 200) {
            $erro = "Erro HTTP $httpCode: " . $response;
        } else {
            $result = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $erro = "Erro ao decodificar JSON: " . json_last_error_msg();
            } elseif (isset($result['error'])) {
                $erro = "Erro da API: " . $result['error'];
            } elseif (isset($result[0]['summary_text'])) {
                $resumo = $result[0]['summary_text'];

                // Salvar no banco
                $stmt = $conn->prepare("INSERT INTO historico (texto_original, resumo) VALUES (?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ss", $texto, $resumo);
                    if (!$stmt->execute()) {
                        $erro = "Erro ao salvar no banco: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $erro = "Erro na preparação da query: " . $conn->error;
                }
            } else {
                $erro = "Erro: Resposta inesperada da API.";
            }
        }
    }
}

// Buscar histórico
$historico = $conn->query("SELECT * FROM historico ORDER BY id DESC LIMIT 5");
if (!$historico) {
    $erro = "Erro ao buscar histórico: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Corretor de Texto com IA</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Corretor de Texto com IA</h1>

<?php if($erro): ?>
<p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="post">
    <textarea name="texto" placeholder="Digite seu texto aqui"><?= isset($texto) ? htmlspecialchars($texto) : '' ?></textarea>
    <button type="submit">Gerar Resumo</button>
</form>

<?php if($resumo): ?>
<h2>Resumo:</h2>
<p><?= htmlspecialchars($resumo) ?></p>
<?php endif; ?>

<?php if($historico && $historico->num_rows > 0): ?>
<h2>Últimos Resumos:</h2>
<ul>
<?php while($row = $historico->fetch_assoc()): ?>
    <li><strong>Original:</strong> <?= htmlspecialchars($row['texto_original']) ?><br>
        <strong>Resumo:</strong> <?= htmlspecialchars($row['resumo']) ?></li>
<?php endwhile; ?>
</ul>
<?php endif; ?>
</body>
</html>

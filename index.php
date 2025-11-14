<?php
include 'processa.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corretor de Texto com IA</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- Fonte moderna -->
</head>
<body>
    <header class="header">
        <h1>Corretor de Texto com IA</h1>
        <p>Insira seu texto e gere um resumo inteligente!</p>
        <div class="site-description">
            <p>Este site utiliza inteligência artificial para resumir textos acadêmicos de forma rápida e precisa, ajudando estudantes e profissionais a economizar tempo e focar no essencial.</p>
        </div>
    </header>

    <main class="container">
        <?php if($erro): ?>
        <div class="erro">
            <p><?= htmlspecialchars($erro) ?></p>
        </div>

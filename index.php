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
        <?php endif; ?>

        <form method="post" id="form-texto">
            <label for="texto">Digite seu texto aqui:</label>
            <textarea id="texto" name="texto" placeholder="Cole ou digite o texto acadêmico que deseja resumir..."><?= isset($texto) ? htmlspecialchars($texto) : '' ?></textarea>
            <button type="submit" id="btn-submit">Gerar Resumo</button>
        </form>

        <?php if($resumo): ?>
        <section class="resumo">
            <h2>Resumo Gerado:</h2>
            <p class="resumo-texto"><?= htmlspecialchars($resumo) ?></p>
        </section>
        <?php endif; ?>

        <?php if($historico && $historico->num_rows > 0): ?>
        <section class="historico">
            <h2>Últimos Resumos:</h2>
            <ul>
                <?php while($row = $historico->fetch_assoc()): ?>
                <li>
                    <strong>Original:</strong> <span class="original"><?= htmlspecialchars($row['texto_original']) ?></span><br>
                    <strong>Resumo:</strong> <span class="resumo-item"><?= htmlspecialchars($row['resumo']) ?></span>
                </li>
                <?php endwhile; ?>
            </ul>
        </section>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <p>&copy; 2023 Corretor de Texto com IA. Desenvolvido com PHP e IA.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>

<?php
include 'processa.php';
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
<p class="erro"><?= htmlspecialchars($erro) ?></p>
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

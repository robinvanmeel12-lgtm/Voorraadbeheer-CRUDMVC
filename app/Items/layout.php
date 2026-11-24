<?php
function escape(string|int|float $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escape($data['title']) ?> | Voorraadbeheer</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a class="brand" href="/">Voorraadbeheer</a>
        </div>
    </header>
    <main class="container">
        <?php require __DIR__ . '/' . $view . '.php'; ?>
    </main>
</body>
</html>

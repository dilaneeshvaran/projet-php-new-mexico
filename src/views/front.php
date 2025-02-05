<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title ?? "Titre de ma page"); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description ?? "Ceci est la description de la page"); ?>">
    <link rel="stylesheet" href="/assets/main.css">
    <script src="/assets/main.js"></script>
    </head>

<body>
    <h1 class="button">Template du front</h1>
    <div class="container">
    <?php echo $content; ?>
    </div>
   </body>

</html>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($title ?? "Titre de ma page"); ?></title>
        <meta name="description" content="<?php echo htmlspecialchars($description ?? "Ceci est la description de la page"); ?>">
    </head>
    <body>
        <h1>Template du back</h1>
        <?php echo $content; ?>
    </body>
</html>
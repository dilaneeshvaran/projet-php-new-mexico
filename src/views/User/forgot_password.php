<?php
$errors = $errors ?? [];
?>

<?php if (!empty($errors)): ?>
    <div style="background-color: red">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<form method="POST" action="/forgot-password/submit">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    
    <label for="email">Entrez votre email:</label>
    <input type="email" name="email" id="email" placeholder="votre.email@exemple.com" required>
    <button type="submit">Envoyer</button>
</form>

<a href="/login">Retour à la page de connexion</a>
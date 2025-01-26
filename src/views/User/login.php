<?php
$errors = $errors ?? [];
$formData = $formData ?? [];
?>

<h1><?php echo htmlspecialchars($title); ?></h1>

<?php if (!empty($errors)): ?>
    <div style="background-color: red">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/login/submit">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <input type="email" required name="email" placeholder="Votre Email"><br>
    <input type="password" required name="password" placeholder="Votre mot de passe"><br>
    <input type="submit" value="Se connecter">
</form>

<a href=/forgot-password> Mot de passe oublié ?</a>
<a href=/register> Créer un compte</a>
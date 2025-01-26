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

<form method="post" action="/register/submit">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
    <input type="text" name="firstname" required placeholder="Votre nom" value="<?php echo htmlspecialchars($formData['firstname'] ?? ''); ?>"><br>
    <input type="text" name="lastname" required placeholder="Votre prénom" value="<?php echo htmlspecialchars($formData['lastname'] ?? ''); ?>"><br>
    <input type="email" name="email" required placeholder="Votre email" value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"><br>
    <input type="password" name="password" required placeholder="Votre mot de passe"><br>
    <input type="password" name="passwordConfirm" required placeholder="Confirmation"><br>
    <input type="submit" value="S'inscrire"><br>
</form>

<a href="/login">Déjà inscrit ? Connectez-vous</a>

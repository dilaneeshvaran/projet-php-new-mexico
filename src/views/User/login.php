<?php
$errors = $errors ?? [];
$formData = $formData ?? [];
?>

<div class="form">
    <div class="form__container">
        <h1 class="form__title"><?php echo htmlspecialchars($title); ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form__area" method="POST" action="/login/submit">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input class="form__input" type="email" required name="email" placeholder="Votre Email">
            <input class="form__input" type="password" required name="password" placeholder="Votre mot de passe">
            <input class="form__submit" type="submit" value="Se connecter">
        </form>

        <div class="form__links">
            <a href="/forgot-password">Mot de passe oublié ?</a>
            <a href="/register">Créer un compte</a>
        </div>
    </div>
</div>

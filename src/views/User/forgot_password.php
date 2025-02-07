<?php
$errors = $errors ?? [];
?>

<div class="form">
    <div class="form__container">
        <h1 class="form__title">Mot de passe oublié</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form__area" method="POST" action="/forgot-password/submit">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <input class="form__input" type="email" name="email" id="email" placeholder="Votre email" required>

            <input class="form__submit" type="submit" value="Envoyer">
        </form>

        <div class="form__links">
            <a href="/login">Retour à la page de connexion</a>
        </div>
    </div>
</div>

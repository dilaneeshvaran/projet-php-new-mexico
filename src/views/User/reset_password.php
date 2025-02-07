<?php
$errors = $errors ?? [];
?>

<div class="form">
    <div class="form__container">
        <h1 class="form__title">Réinitialisation du mot de passe</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form__area" method="POST" action="/reset-password/<?= htmlspecialchars($token ?? 'token-not-found'); ?>/submit">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? 'token-input-not-found') ?>">

            <input class="form__input" type="password" name="password" id="password" placeholder="Nouveau mot de passe" required>

            <input class="form__input" type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe" required>

            <input class="form__submit" type="submit" value="Changer le Mot de Passe">
        </form>

        <div class="form__links">
            <a href="/login">Retour à la page de connexion</a>
        </div>
    </div>
</div>

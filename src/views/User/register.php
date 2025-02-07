<?php
$errors = $errors ?? [];
$formData = $formData ?? [];
?>

<div class="form">
    <div class="form__container">
        <h1 class="form__title"><?php echo htmlspecialchars($title); ?></h1>

        <?php if (!empty($errors)): ?>
    <div class="errors">
        <div class="errors__list">
            <?php foreach ($errors as $error): ?>
                <div class="errors__item"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

        <form class="form__area" method="POST" action="/register/submit">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input class="form__input" type="text" required name="firstname" placeholder="Votre nom" value="<?= htmlspecialchars($formData['firstname'] ?? '') ?>">
            <input class="form__input" type="text" required name="lastname" placeholder="Votre prénom" value="<?= htmlspecialchars($formData['lastname'] ?? '') ?>">
            <input class="form__input" type="email" required name="email" placeholder="Votre email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
            <input class="form__input" type="password" required name="password" placeholder="Votre mot de passe">
            <input class="form__input" type="password" required name="passwordConfirm" placeholder="Confirmation du mot de passe">
            <input class="form__submit" type="submit" value="S'inscrire">
        </form>

        <div class="form__links">
            <a href="/login">Déjà inscrit ? Connectez-vous</a>
        </div>
    </div>
</div>

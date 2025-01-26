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

<form method="POST" action="/reset-password/<?= htmlspecialchars($token ?? 'token-not-found'); ?>/submit">

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? 'token-input-not-found') ?>">

    <label for="password" aria-label="Nouveau Mot de Passe">Nouveau Mot de Passe:</label>
    <input type="password" name="password" id="password" required>
    
    <label for="password_confirm">Confirmez le Nouveau Mot de Passe:</label>
    <input type="password" name="password_confirm" id="password_confirm" required>
    
    <button type="submit">Changer le Mot de Passe</button>
</form>
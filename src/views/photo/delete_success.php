<?php $groupId = $this->data['groupId'] ?? null; ?>
<?php $errors = $this->data['errors'] ?? null; ?>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <div class="errors__title">Erreur</div>
        <ul class="errors__list">
            <?php foreach ($errors as $error): ?>
                <li class="errors__item"><?= htmlspecialchars(is_array($error) ? json_encode($error) : $error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <div class="success">
        <div class="success__container">
            <div class="success__icon">✓</div>
            <p class="success__message">L'image a bien été supprimée</p>
            <a href="/group/<?=htmlspecialchars($groupId)?>/photos" class="success__link">Retour à l'accueil</a>
        </div>
    </div>
<?php endif; ?>
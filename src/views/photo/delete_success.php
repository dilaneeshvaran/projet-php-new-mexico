<?php $groupId = $this->data['groupId'] ?? null; ?>
<?php $errors = $this->data['errors'] ?? null; ?>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars(is_array($error) ? json_encode($error) : $error) ?></p>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    L'image a bien été supprimé.
<?php endif; ?>

<br>
<a href="/group/<?=htmlspecialchars($groupId)?>/photos">Retour</a>
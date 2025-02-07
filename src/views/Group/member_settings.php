<?php $groupId = $this->data['groupId'] ?? null; ?>
<?php $memberId = $this->data['memberId'] ?? null; ?>

<div class="form">
    <?php if (!empty($errors)): ?>
    <div class="error-messages">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form class="form__container" method="POST" action="/group/leave">
        <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
        <input type="hidden" name="memberId" value="<?= htmlspecialchars($memberId) ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button class="button button--danger button--lg" type="submit">Quitter le Groupe</button>
    </form>
    <br>
    <a class="button button--secondary button__top" href="/group/<?= htmlspecialchars($groupId) ?>">Retour</a>
</div>
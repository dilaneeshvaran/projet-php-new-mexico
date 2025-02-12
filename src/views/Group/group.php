<div class="group-view">
    <div class="group-view__container">
        <?php if ($group): ?>
            <div class="group-view__header">
    <div class="group-view__title">
        <h1><?= htmlspecialchars($group->getName()) ?></h1>
        <?php if ($groupRole === 'admin'): ?>
            <img src="/assets/images/crown.svg" alt="Description of the image" class="crown-icon" width="20" height="20">
        <?php endif; ?>
    </div>
    <p><?= htmlspecialchars($group->getDescription()) ?></p>
</div>
<?php
    $createdAt = new DateTime($group->getCreatedAt());
    $formattedDate = $createdAt->format('d/m/Y à H:i');
?>
        <div class="group-view__meta">
        Crée le: <?= htmlspecialchars($formattedDate) ?>
        </div>
        <?php else: ?>
        <div class="group-view__header">
            <h1>Aucun groupe trouvé</h1>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="group-view__actions">
            <?php if ($groupAccess === 'writer'): ?>
            <a href="/group/<?= $group->getId() ?>/photos" class="link link--primary">Voir et Publier des photos</a>
            <?php else: ?>
            <a href="/group/<?= $group->getId() ?>/photos" class="link link--secondary">Voir les
                photos</a>
            <?php endif; ?>

            <?php if ($groupRole === 'admin'): ?>
            <a href="/group/<?= $group->getId() ?>/members" class="link link--primary">Voir et
                Gérer les membres</a>
            <a href="/group/<?= $group->getId() ?>/settings" class="link link--primary">Paramètres du groupe</a>
            <?php endif; ?>

            <?php if ($groupRole === 'member'): ?>
            <a href="/group/<?= $group->getId() ?>/members" class="link link--secondary">Voir
                les membres</a>
            <form method="POST" action="/group/member/settings">
                <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <button class="button button--primary" type="submit">Paramètres</button>
            </form>
            <?php endif; ?>
            <a href="/" class="data-table__back-link">Retour</a>
        </div>
    </div>
</div>
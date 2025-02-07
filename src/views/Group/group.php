<div class="group-view">
    <div class="group-view__container">
        <?php if ($group): ?>
        <div class="group-view__header">
            <h1><?= htmlspecialchars($group->getName()) ?></h1>
            <p><?= htmlspecialchars($group->getDescription()) ?></p>
        </div>

        <div class="group-view__meta">
            Created: <?= htmlspecialchars($group->getCreatedAt()) ?>
        </div>
        <?php else: ?>
        <div class="group-view__header">
            <h1>No Group Found</h1>
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
            <a href="/group/<?= $group->getId() ?>/photos" class="link link--primary">Voir/publier des photos</a>
            <?php else: ?>
            <a href="/group/<?= $group->getId() ?>/photos" class="link link--secondary">Voir les
                photos</a>
            <?php endif; ?>

            <?php if ($groupRole === 'admin'): ?>
            <a href="/group/<?= $group->getId() ?>/members" class="link link--primary">Voir et
                gérer les membres</a>
            <a href="/group/<?= $group->getId() ?>/settings" class="link link--primary">Parametres du groupe</a>
            <?php endif; ?>

            <?php if ($groupRole === 'member'): ?>
            <a href="/group/<?= $group->getId() ?>/members" class="link link--secondary">Voir
                les membres</a>
            <form method="POST" action="/group/member/settings">
                <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <button class="button button--primary" type="submit">Parametres</button>
            </form>
            <?php endif; ?>
            <a href="/" class="link link--secondary">Back</a>
        </div>
    </div>
</div>
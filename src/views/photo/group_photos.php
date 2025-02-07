<?php $group = $this->data['group'] ?? null; ?>
<?php $groupAccess = $this->data['group_access'] ?? null; ?>

<div class="group-photos">
    <div class="group-photos__navigation">
        <a class="link__back" href="/group/<?= $group->getId() ?>">Back</a>
        <?php if ($groupAccess === 'writer'): ?>
        <a class="link__main" href="/group/<?= $group->getId() ?>/upload">Ajouter une Photo</a>
        <?php endif; ?>
    </div>


    <?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <h2 class="group-photos__title">Group Photos</h2>

    <?php if (!empty($photos)): ?>
    <ul class="group-photos__list">
        <?php foreach ($photos as $photo): ?>
        <?php $deleteAccess = ($photo->getUserId() === $userId || $userRole === 'admin'); ?>
        <li class="group-photos__item">

            <div class="group-photos__image">
                <img src="/uploads/<?= htmlspecialchars($photo->getFilename()) ?>"
                    alt="<?= htmlspecialchars($photo->getOriginalName()) ?>">
            </div>
            <div class="group-photos__content">
                <p><strong>Title:</strong> <?= htmlspecialchars($photo->getTitle()) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($photo->getDescription()) ?></p>
                <p><strong>Uploaded by:</strong> <?= htmlspecialchars($photo->userFullName) ?></p>
                <p><strong>Uploaded at:</strong> <?= htmlspecialchars($photo->getCreatedAt()) ?></p>
            </div>
            <?php if ($deleteAccess): ?>
            <div class="group-photos__actions">
                <form
                    action="/group/<?= htmlspecialchars($group->getId()) ?>/photo/<?= htmlspecialchars($photo->getId()) ?>/delete"
                    method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                    <input type="hidden" name="photoId" value="<?= htmlspecialchars($photo->getId()) ?>">
                    <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <button class="button__delete" type="submit">Delete</button>
                </form>
            </div>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <div class="group-photos__empty">
        <p>No photos available.</p>
    </div>
    <?php endif; ?>
</div>
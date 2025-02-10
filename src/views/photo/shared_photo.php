<?php $photo = $this->data['photo'] ?? null; ?>
<?php $errors = $this->data['errors'] ?? []; ?>

<div class="shared-photo">
    <div class="shared-photo__container">
        <div class="shared-photo__image">
            <img src="/uploads/<?= htmlspecialchars($photo->getFilename()) ?>"
                 alt="<?= htmlspecialchars($photo->getOriginalName()) ?>">
        </div>
        <div class="shared-photo__details">
            <h1><?= htmlspecialchars($photo->getTitle()) ?></h1>
            <?php if ($photo->getDescription()): ?>
                <p class="shared-photo__description">
                    <?= htmlspecialchars($photo->getDescription()) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
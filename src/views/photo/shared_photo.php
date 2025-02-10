<?php $photo = $this->data['photo'] ?? null; ?>
<?php $errors = $this->data['errors'] ?? []; ?>

<div class="group-photos group-photos--shared">
    <div class="group-photos__container">
        <div class="group-photos__image">
            <img src="/uploads/<?= htmlspecialchars($photo->getFilename()) ?>"
                 alt="<?= htmlspecialchars($photo->getOriginalName()) ?>">
        </div>
        <div class="group-photos__details">
            <h1><?= htmlspecialchars($photo->getTitle()) ?></h1>
            <?php if ($photo->getDescription()): ?>
                <p class="group-photos__description">
                    <?= htmlspecialchars($photo->getDescription()) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
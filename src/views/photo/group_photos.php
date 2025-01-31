<h1><?= htmlspecialchars($title) ?></h1>
<p><?= htmlspecialchars($description) ?></p>

<?php $group = $this->data['group'] ?? null; ?>
<a href="/group/<?= $group->getId() ?>">Back</a>
<a href="/group/<?= $group->getId() ?>/upload">Ajouter une Photo</a>

<?php if (!empty($errors)): ?>
    <div>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<h2>Group Photos</h2>
<?php if (!empty($photos)): ?>
    <ul>
        <?php foreach ($photos as $photo): ?>
            <li>
                <h3><?= htmlspecialchars($photo->getOriginalName()) ?></h3>
                <p>Title: <?= htmlspecialchars($photo->getTitle()) ?></p>
                <p>Description: <?= htmlspecialchars($photo->getDescription()) ?></p>
                <p>Uploaded by: <?= htmlspecialchars($photo->userFullName) ?></p>
                <p>Uploaded at <?= htmlspecialchars($photo->getCreatedAt()) ?></p>
                <img src="/uploads/<?= htmlspecialchars($photo->getFilename()) ?>" alt="<?= htmlspecialchars($photo->getOriginalName()) ?>" width="200">
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No photos available.</p>
<?php endif; ?>

<a href="/group/<?= $group->getId() ?>">Back</a>

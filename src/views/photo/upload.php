    <h1><?= htmlspecialchars($title) ?></h1>
    <p><?= htmlspecialchars($description) ?></p>

    <?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>


    <?php $groupId = $this->data['groupId'] ?? null; ?>

    <form action="/group/<?= $groupId ?>/upload/post" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">

        <div>
            <br>
        </div>
        <div>
    <label for="title">Titre:</label>
    <br>
    <input type="text" name="title" id="title" required>
</div>

<div>
    <label for="description">Description:</label>
    <br>
    <textarea name="description" id="description" required></textarea>
</div>
<div>
            <br>
        </div>
        <div>
        <div>
            <label for="photo">Choose Photo:</label>
            <br>
            <input type="file" name="photo" id="photo" accept="<?= implode(',', $allowedTypes) ?>" required>
            <br>
            <small>Taille du ficheir Max: <?= $maxFileSize / 1024 / 1024 ?>MB</small>
            <br>
            <?php
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                $displayTypes = str_replace('image/', '', $allowedTypes);
            ?>
            <small>Types de fichiers autorisé: <?= implode('/', $displayTypes) ?></small>
        </div>

        <div>
            <button type="submit">Poster la Photo</button>
        </div>
    </form>

    <a href="/">Retour à l'accueil</a>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p><?= htmlspecialchars($description) ?></p>

    <?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="/upload/post" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div>
            <label for="group">Select Group:</label>
            <br>
            <select name="group_id" id="group" required>
                <option value="">Select a group...</option>
                <?php foreach ($groups as $groupData): ?>
                <?php $group = $groupData['group']; ?>
                <option value="<?= htmlspecialchars($group->getId()) ?>"
                    <?= isset($formData['group_id']) && $formData['group_id'] == $group->getId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($group->getName()) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

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
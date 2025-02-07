    <?php
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                $displayTypes = str_replace('image/', '', $allowedTypes);
                $groupId = $this->data['groupId'] ?? null;
    ?>

    <div class="upload-photo-form">
        <h1 class="form-title"><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($description) ?></p>
        <?php if (!empty($errors)): ?>
        <div class="errors">
            <div class="errors__title">Errors:</div>
            <ul class="errors__list">
                <?php foreach ($errors as $error): ?>
                <li class="errors__item"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <form action="/group/<?= $groupId ?>/upload/post" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">

            <div class="form-group">
                <label for="title">Titre:</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div class="form-group">
                <div class="file-upload">
                    <label for="photo">
                        <div class="upload-icon">📁</div>
                        Choose Photo
                        <input type="file" name="photo" id="photo" accept="<?= implode(',', $allowedTypes) ?>" required>
                        <div class="file-info">
                            <small>Taille du ficheir Max: <?= $maxFileSize / 1024 / 1024 ?>MB</small>
                            <br>
                            <small>Types de fichiers autorisé: <?= implode('/', $displayTypes) ?></small>
                        </div>
                </div>

            </div>
            <button type="submit" class="submit-button">Poster la Photo</button>
            <a href="/group/<?= $groupId ?>/photos" class="back-link">Retour</a>

    </div>

    </form>

    </div>
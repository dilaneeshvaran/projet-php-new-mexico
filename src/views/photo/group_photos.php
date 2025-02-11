<?php $group = $this->data['group'] ?? null; ?>
<?php $groupAccess = $this->data['group_access'] ?? null; ?>

<div class="group-photos">

    <div class="group-photos__navigation">
        <a class="data-table__back-link" href="/group/<?= $group->getId() ?>">Retour</a>
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
    <h2 class="group-photos__title">Groupe : <?= $group->getName() ?></h2>


    <?php if (!empty($photos)): ?>
    <ul class="group-photos__list">
        <?php foreach ($photos as $photo): ?>
        <?php $deleteAccess = ($photo->getUserId() === $userId || $userRole === 'admin'); ?>
        <li class="group-photos__item">

            <div class="group-photos__image">
                <img src="/uploads/<?= htmlspecialchars($photo->getFilename()) ?>"
                    alt="<?= htmlspecialchars($photo->getOriginalName()) ?>">
            </div>

            <?php
    $createdAt = new DateTime($photo->getCreatedAt());
    $formattedDate = $createdAt->format('d/m/Y à H:i');
?>
            <div class="group-photos__content">
                <p><strong>Titre:</strong> <?= htmlspecialchars($photo->getTitle()) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($photo->getDescription()) ?></p>
                <p><strong>Posté par:</strong> <?= htmlspecialchars($photo->userFullName) ?></p>
                <p><strong>Posté le:</strong> <?= htmlspecialchars($formattedDate) ?></p>
            </div>
            <?php if ($deleteAccess): ?>
                <div class="group-photos__actions">
    <form
        action="/group/<?= htmlspecialchars($group->getId()) ?>/photo/<?= htmlspecialchars($photo->getId()) ?>/delete"
        method="POST">
        <input type="hidden" name="photoId" value="<?= htmlspecialchars($photo->getId()) ?>">
        <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button class="button button__delete deletePhotoButton" type="button">Supprimer</button>
    </form>

    <button class="button button--secondary sharePhotoButton" 
            data-photo-id="<?= htmlspecialchars($photo->getId()) ?>"
            data-csrf-token="<?= htmlspecialchars($csrfToken) ?>"
            type="button">
        Partager
    </button>
</div>

            <?php endif; ?>
        </li>

        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <div class="group-photos__empty">
        <p>Aucune photo dans ce groupe.</p>
    </div>
    <?php endif; ?>
</div>

        <!--delete photo confirmation -->
        <div id="deletePhotoModal" class="modal">
    <div class="modal__content">
        <p>Êtes-vous sûr de vouloir supprimer cette photo ?</p>
        <button class="button button--danger" id="confirmDelete">Oui, Supprimer</button>
        <button class="button button--secondary" id="cancelDelete">Annuler</button>
    </div>
</div>

        <!--share photo -->

<div id="sharePhotoModal" class="modal">
    <div class="modal__content">
        <h3>Lien de partage</h3>
        <div class="modal__content">
            <input type="text" id="shareLink" readonly>
            <button class="button button--secondary" id="copyShareLink">Copier</button>
        </div>
        <button class="button button--secondary" id="closeShareModal">Fermer</button>
    </div>
</div>
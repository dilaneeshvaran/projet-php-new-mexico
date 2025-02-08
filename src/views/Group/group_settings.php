<?php $group = $this->data['group'] ?? null;?>
<?php $groupId = $this->data['groupId'] ?? null; ?>
<div class="form form--large">
    <div class="form__container">
        <div class="form__header">
            <h1 class="form__title">Paramètres du groupe</h1>
        </div>
        <?php if (!empty($errors)): ?>
        <div class="form__errors">
            <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <form class="form__area" action="/group/<?= htmlspecialchars($groupId) ?>/settings/save" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">

            <div class="form__field">
                <label class="form__label" for="group_name">Nom du Groupe</label>
                <input class="form__input" type="text" id="group_name" name="group_name"
                    value="<?php echo htmlspecialchars($group->getName()); ?>" required>
            </div>

            <div class="form__field">
                <label class="form__label" for="description">Description</label>
                <textarea class="form__textarea" id="description" name="description"
                    required><?php echo htmlspecialchars($group->getDescription()); ?></textarea>
            </div>

            <div class="form__field">
                <label class="form__label" for="access_type">Type d'accès</label>
                <select class="form__select" id="access_type" name="access_type">
                    <option value="open" <?php echo ($group->getAccessType() == 'open') ? 'selected' : ''; ?>>Ouvert
                    </option>
                    <option value="on_invitation"
                        <?php echo ($group->getAccessType() == 'on_invitation') ? 'selected' : ''; ?>>Sur Invitation
                    </option>
                    <option value="closed" <?php echo ($group->getAccessType() == 'closed') ? 'selected' : ''; ?>>Fermé
                    </option>
                </select>
            </div>

            <div class="form__actions">
                <div>
                    <a href="/group/<?=$groupId?>" class="form__back">Retour</a>
                    <a href="#" class="form__delete" data-group-id="<?= htmlspecialchars($groupId) ?>" onclick="openDeleteModal(event)">Supprimer</a>
                    </div>
                <button type="submit" class="form__submit">Enregistrer</button>
            </div>
        </form>
        
        <div id="deleteModal" class="modal">
  <div class="modal__content">
    <p>Êtes-vous sûr de vouloir supprimer ce groupe ?</p>
    <p>Attention : Toutes les photos vont être supprimées !</p>
      <button class="button button--danger" id="confirmDelete">Oui, Supprimer</button>
      <button class="button button--secondary" id="cancelDelete">Annuler</button>
  </div>
</div>

    </div>
</div>
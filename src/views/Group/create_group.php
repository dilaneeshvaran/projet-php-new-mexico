<div class="form form--large">
    <div class="form__container">
        <div class="form__header">
            <h2 class="form__title">Créer un groupe</h2>
        </div>

        <?php if (!empty($errors)): ?>
        <div class="form__errors">
            <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form action="/group/create/submit" method="post" class="form__area">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div>
                <label class="form__label" for="name">Nom du groupe:</label>
                <input class="form__input" type="text" id="name" name="name" required>
            </div>

            <div>
                <label class="form__label" for="description">Description:</label>
                <textarea class="form__textarea" id="description" name="description" required></textarea>
            </div>

            <div>
                <label class="form__label" for="access_type">Accèss :</label>
                <select class="form__select" id="access_type" name="access_type" required>
                    <option value="open">Ouvert</option>
                    <option value="on_invitation">Sur Invitation</option>
                    <option value="closed">Fermé</option>
                </select>
            </div>

            <div class="form__actions">
                <button type="submit" class="form__submit">Créer le Groupe</button>
                <a href="/" class="form__back">Retour</a>
            </div>
        </form>
    </div>
</div>
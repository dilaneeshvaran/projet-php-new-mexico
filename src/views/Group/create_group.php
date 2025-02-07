<div class="create-group">
    <?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="/group/create/submit" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div class="form-field">
            <label for="name">Group Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-field">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="form-field">
            <label for="access_type">Access Type:</label>
            <select id="access_type" name="access_type" required>
                <option value="open">Ouvert</option>
                <option value="on_invitation">Sur Invitation</option>
                <option value="closed">Fermé</option>
            </select>
        </div>

        <button type="submit">Create Group</button>
    </form>

    <a href="/">Back to Groups</a>
</div>
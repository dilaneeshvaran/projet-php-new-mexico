<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<form action="/group/create/submit" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    
    <label for="name">Group Name:</label>
    <input type="text" id="name" name="name" required>

    <br>
    
    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea>
    
    <br>

    <label for="access_type">Access Type:</label>
<select id="access_type" name="access_type" required>
    <option value="open">Ouvert</option>
    <option value="on_invitation">Sur Invitation</option>
    <option value="closed">Fermé</option>
</select>
    
    <button type="submit">Create Group</button>
</form>

<a href="/">Back to Groups</a>

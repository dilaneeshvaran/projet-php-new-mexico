<?php $group = $this->data['group'] ?? null;?>

<?php $groupId = $this->data['groupId'] ?? null; ?>


<form action="/group/<?= htmlspecialchars($groupId) ?>/settings/save" method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
<input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">

        <label for="group_name">Nom du Groupe:</label>
        <input type="text" id="group_name" name="group_name" value="<?php echo htmlspecialchars($group->getName()); ?>" required>
        <br><br>

        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($group->getDescription()); ?></textarea>
        <br><br>

        <label for="access_type">Access Type:</label>
        <select id="access_type" name="access_type">
            <option value="open" <?php echo ($access_type == 'open') ? 'selected' : ''; ?>>Ouvert</option>
            <option value="on_invitation" <?php echo ($access_type == 'on_invitation') ? 'selected' : ''; ?>>Sur Invitation</option>
            <option value="closed" <?php echo ($access_type == 'closed') ? 'selected' : ''; ?>>Fermé</option>
        </select>
        <br><br>

        <button type="submit">Enregistrer</button>
    </form>

    <?php $groupId = $this->data['groupId'] ?? null; ?>

    <a href="/group/<?=$groupId?>">back</a>
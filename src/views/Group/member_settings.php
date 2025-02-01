<?php $groupId = $this->data['groupId'] ?? null; ?>
<?php $memberId = $this->data['memberId'] ?? null; ?>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<form method="POST" action="/group/leave">
    <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
    <input type="hidden" name="memberId" value="<?= htmlspecialchars($memberId) ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <button type="submit">Leave Group</button>
</form>
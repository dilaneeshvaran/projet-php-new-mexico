<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<?php $groupId = $this->data['groupId'] ?? null; ?>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Joined At</th>
            <th>Group Access</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($members)): ?>
            <?php foreach ($members as $index => $member): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($member['firstname']) ?></td>
                    <td><?= htmlspecialchars($member['lastname']) ?></td>
                    <td><?= htmlspecialchars($member['email']) ?></td>
                    <td><?= htmlspecialchars($member['joined_at']) ?></td>
                    <td><?= htmlspecialchars($member['group_access']) ?></td>
                    <td><?= htmlspecialchars($member['role']) ?></td>
                    <td>
                        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/member/<?= htmlspecialchars($member['id']) ?>/manage">
                            <input type="hidden" name="memberId" value="<?= htmlspecialchars($member['id']) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <button type="submit">Manage</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No members found in this group.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<a href="/group/<?= htmlspecialchars($groupId) ?>">back</a>
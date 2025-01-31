<h1>Manage Group Member</h1>
<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Access</th>
            <th>Joined At</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($memberDetails)): ?>
            <tr>
                <td><?= htmlspecialchars($memberDetails['firstname']) ?></td>
                <td><?= htmlspecialchars($memberDetails['lastname']) ?></td>
                <td><?= htmlspecialchars($memberDetails['email']) ?></td>
                <td><?= htmlspecialchars($memberDetails['role']) ?></td>
                <td><?= htmlspecialchars($memberDetails['group_access']) ?></td>
                <td><?= htmlspecialchars($memberDetails['joined_at']) ?></td>
            </tr>
        <?php else: ?>
            <tr><td colspan="6">No member details available.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div>
    <form method="POST" action="/group/<?= $groupId ?>/member/<?= $memberId ?>/update-access">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="memberId" value="<?= $memberId ?>">
        <input type="hidden" name="groupId" value="<?= $groupId ?>">
        <?php if ($memberDetails['group_access'] === 'writer'): ?>
            <button type="submit" name="update_access" value="remove_write">Remove Write Access</button>
        <?php else: ?>
            <button type="submit" name="update_access" value="give_write">Give Write Access</button>
        <?php endif; ?>
    </form>

    <form method="POST" action="/group/<?= $groupId ?>/member/<?= $memberId ?>/remove" onsubmit="return confirm('Are you sure?');">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="memberId" value="<?= $memberId ?>">
        <input type="hidden" name="groupId" value="<?= $groupId ?>">
        <button type="submit">Remove from Group</button>
    </form>
</div>

<a href="/group/<?= htmlspecialchars($groupId) ?>/members">back</a>
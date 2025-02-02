<h1>Search Users</h1>
<?php $groupId = $this->data['groupId'] ?? null; ?>

<form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/invite-member/search">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
    <input type="text" name="searchUser" placeholder="Search by name or email..." required>
    <button type="submit">Search</button>
</form>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars(is_array($error) ? json_encode($error) : $error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($this->data['users'])): ?>
    <h2>Search Results:</h2>
    <table border="1">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->data['users'] as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->getFirstname()) ?></td>
                    <td><?= htmlspecialchars($user->getLastname()) ?></td>
                    <td><?= htmlspecialchars($user->getEmail()) ?></td>
                    <td><?= htmlspecialchars($user->getCreatedAt()) ?></td>
                    <td>
    <?php if ($user->invitationStatus === 'member'): ?>
        <span >Member</span>
    <?php elseif (isset($user->invitationStatus)): ?>
        <span >
            <?= htmlspecialchars(ucfirst($user->invitationStatus)) ?>
        </span>
    <?php else: ?>
        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/invite-member/<?= htmlspecialchars($user->getId()) ?>/send">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($this->data['csrfToken']) ?>">
            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
            <input type="hidden" name="memberId" value="<?= htmlspecialchars($user->getId()) ?>">
            <button type="submit">Invite</button>
        </form>
    <?php endif; ?>
</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>

<a href="/group/<?= htmlspecialchars($groupId) ?>/members">Back</a>

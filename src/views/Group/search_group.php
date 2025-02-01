<h1>Search Groups</h1>

<form method="POST" action="/group/search/result">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <input type="text" name="searchGroupName" placeholder="Search for groups..." required>
    <button type="submit">Search</button>
</form>

<?php if (isset($_SESSION['join_group_errors'])): ?>
    <div class="alert">
        <?php foreach ($_SESSION['join_group_errors'] as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['join_group_errors']); ?>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($groups)): ?>
    <h2>Search Results:</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Access Type</th>
                <th>Total Members</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($groups as $group): ?>
                <tr>
                    <td><?= htmlspecialchars($group->getName()) ?></td>
                    <td><?= htmlspecialchars($group->getDescription()) ?></td>
                    <td><?= htmlspecialchars($group->getCreatedAt()) ?></td>
                    <td><?= htmlspecialchars($group->getAccessType()) ?></td>
                    <td><?= htmlspecialchars($group->total_members) ?></td>
                    <td>
    <?php if ($group->is_member): ?>
        <button disabled>You are a member</button>
    <?php else: ?>
        <?php if ($group->getAccessType() === 'open'): ?>
            <form method="POST" action="/group/join">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                <button type="submit">Join</button>
            </form>
        <?php elseif ($group->getAccessType() === 'closed'): ?>
            <button disabled>Closed</button>
        <?php elseif ($group->getAccessType() === 'on_invitation'): ?>
            <form method="POST" action="/group/join">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                <button type="submit">Request to Join</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No groups found.</p>
<?php endif; ?>

<a href="/">Back</a>

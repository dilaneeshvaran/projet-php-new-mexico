<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Registered On</th>
            <th>Received On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($requests)): ?>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['firstname']) ?></td>
                <td><?= htmlspecialchars($request['lastname']) ?></td>
                <td><?= htmlspecialchars($request['email']) ?></td>
                <td><?= htmlspecialchars($request['registered_on']) ?></td>
                <td><?= htmlspecialchars($request['created_at']) ?></td>
                <td>
                    <?php if ($request['status'] === 'pending'): ?>
                        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/join-requests/<?= htmlspecialchars($request['id']) ?>/process">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="requestId" value="<?= htmlspecialchars($request['id']) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
                            <button type="submit" name="status" value="approved">Accept</button>
                            <button type="submit" name="status" value="rejected">Reject</button>
                        </form>
                    <?php else: ?>
                        <?= ucfirst(htmlspecialchars($request['status'])) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No join requests available.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<a href="/group/<?= htmlspecialchars($groupId) ?>/members">Back to group</a>

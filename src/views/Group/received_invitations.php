<h1>Pending Invitations</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Group Name</th>
                <th>Description</th>
                <th>Sent Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invitations as $invitation): ?>
                <tr>
                    <td><?= htmlspecialchars($invitation['group_name']) ?></td>
                    <td><?= htmlspecialchars($invitation['description']) ?></td>
                    <td><?= htmlspecialchars($invitation['sent_on']) ?></td>
                    <td>
                        <form method="POST" action="/invitations/<?= htmlspecialchars($invitation['id']) ?>/respond">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="invitationId" value="<?= htmlspecialchars($invitation['id']) ?>">
                            <button type="submit" name="action" value="accept">Accept</button>
                            <button type="submit" name="action" value="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<a href="/">Back</a>
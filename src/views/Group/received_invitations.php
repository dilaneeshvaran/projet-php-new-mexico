<a href="/" class="data-table__back-link ml-2 mb-4">Retour</a>

<div class="data-table p-4">
    <h1 class="data-table__title mb-6">Invitations reçues</h1>
    <?php if (empty($invitations)): ?>
    <p class="data-table__empty p-8">Aucune invitation en attente.</p>
    <?php else: ?>
    <div class="data-table__container mb-6">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th class="p-4">Nom Groupe</th>
                    <th class="p-4">Description</th>
                    <th class="p-4">Reçue le</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invitations as $invitation): ?>
                    <?php
    $sent_on = new DateTime($invitation['sent_on']);
    $formattedDate = $sent_on->format('d/m/Y à H:i');
?>
                <tr>
                    <td data-label="Group Name" class="p-4"><?= htmlspecialchars($invitation['group_name']) ?></td>
                    <td data-label="Description" class="p-4"><?= htmlspecialchars($invitation['description']) ?></td>
                    <td data-label="Sent Date" class="p-4"><?= htmlspecialchars($formattedDate) ?></td>
                    <td data-label="Action" class="p-4">
                        <form method="POST" action="/invitations/<?= htmlspecialchars($invitation['id']) ?>/respond">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="invitationId" value="<?= htmlspecialchars($invitation['id']) ?>">
                            <div class="data-table__actions">
                                <button type="submit" name="action" value="accept"
                                    class="data-table__button data-table__button--success radius-1 p-2 mr-2">Accepter</button>
                                <button type="submit" name="action" value="reject"
                                    class="data-table__button data-table__button--danger radius-1 p-2">Rejecter</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
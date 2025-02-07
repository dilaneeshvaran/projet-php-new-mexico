<div class="data-table">
    <h1 class="data-table__title">Invitations reçues</h1>
    <?php if (empty($invitations)): ?>
    <p class="data-table__empty">Aucune invitation en attente.</p>
    <?php else: ?>
    <div class="data-table__container">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th>Nom Groupe</th>
                    <th>Description</th>
                    <th>Reçue le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invitations as $invitation): ?>
                <tr>
                    <td data-label="Group Name"><?= htmlspecialchars($invitation['group_name']) ?></td>
                    <td data-label="Description"><?= htmlspecialchars($invitation['description']) ?></td>
                    <td data-label="Sent Date"><?= htmlspecialchars($invitation['sent_on']) ?></td>
                    <td data-label="Action">
                        <form method="POST" action="/invitations/<?= htmlspecialchars($invitation['id']) ?>/respond">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="invitationId" value="<?= htmlspecialchars($invitation['id']) ?>">
                            <div class="data-table__actions">
                                <button type="submit" name="action" value="accept"
                                    class="data-table__button data-table__button--success">Accepter</button>
                                <button type="submit" name="action" value="reject"
                                    class="data-table__button data-table__button--danger">Rejecter</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <a href="/" class="data-table__back-link">Retour</a>
</div>
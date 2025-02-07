<div class="data-table">
    <h1 class="data-table__title">Demandes pour rejoindre</h1>

    <div class="data-table__container">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Inscrit Le</th>
                    <th>Reçue Le</th>
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
                        <form method="POST"
                            action="/group/<?= htmlspecialchars($groupId) ?>/join-requests/<?= htmlspecialchars($request['id']) ?>/process">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="requestId" value="<?= htmlspecialchars($request['id']) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
                            <div class="data-table__actions">
                                <button type="submit" name="status" value="approved"
                                    class="data-table__button data-table__button--success">Accepter</button>
                                <button type="submit" name="status" value="rejected"
                                    class="data-table__button data-table__button--danger">Rejecter</button>
                            </div>
                        </form>
                        <?php else: ?>
                        <span class="data-table__status data-table__status--<?= 
                                    $request['status'] === 'approved' ? 'success' : 
                                    ($request['status'] === 'rejected' ? 'danger' : 'warning') 
                                ?>">
                            <?= ucfirst(htmlspecialchars($request['status'])) ?>
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" class="data-table__empty">Aucune demande pour rejoindre le groupe.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <a href="/group/<?= htmlspecialchars($groupId) ?>/members" class="data-table__back-link">Retour</a>
</div>
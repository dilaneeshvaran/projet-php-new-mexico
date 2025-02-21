<a href="/group/<?= htmlspecialchars($groupId) ?>/members" class="data-table__back-link ml-2 mb-2">Retour</a>

<div class="data-table p-4">
    <h1 class="data-table__title mb-6">Demandes pour rejoindre</h1>

    <div class="data-table__container mb-6">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Prénom</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Inscrit Le</th>
                    <th class="p-4">Reçue Le</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $request): ?>
                <tr>
                <?php
                    $createdAt = new DateTime($request['created_at']);
                    $registered_on = new DateTime($request['registered_on']);
                    $formattedCreatedDate = $createdAt->format('d/m/Y à H:i');
                    $formattedRegisteredDate = $registered_on->format('d/m/Y à H:i');
                ?>
                    <td class="p-4" data-label="Nom"><?= htmlspecialchars($request['firstname']) ?></td>
                    <td class="p-4" data-label="Prénom"><?= htmlspecialchars($request['lastname']) ?></td>
                    <td class="p-4" data-label="Email"><?= htmlspecialchars($request['email']) ?></td>
                    <td class="p-4" data-label="Inscrit Le"><?= htmlspecialchars($formattedRegisteredDate) ?></td>
                    <td class="p-4" data-label="Reçue Le"><?= htmlspecialchars($formattedCreatedDate) ?></td>
                    <td class="p-4" data-label="Actions">
                        <?php if ($request['status'] === 'pending'): ?>
                        <form method="POST"
                            action="/group/<?= htmlspecialchars($groupId) ?>/join-requests/<?= htmlspecialchars($request['id']) ?>/process">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="requestId" value="<?= htmlspecialchars($request['id']) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
                            <div class="data-table__actions">
                                <button type="submit" name="status" value="approved"
                                    class="data-table__button data-table__button--success p-2 radius-1 mr-2">Accepter</button>
                                <button type="submit" name="status" value="rejected"
                                    class="data-table__button data-table__button--danger p-2 radius-1">Rejecter</button>
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
                    <td colspan="6" class="data-table__empty p-8">Aucune demande pour rejoindre le groupe.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
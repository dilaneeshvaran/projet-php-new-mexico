<div class="data-table">
    <h1 class="data-table__title">Gestion du membre:</h1>

    <?php if (!empty($errors)): ?>
    <div class="data-table__empty data-table__status--danger">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="data-table__container">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Accèss</th>
                    <th>Rejoint Le</th>
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
                <tr>
                    <td colspan="6" class="data-table__empty">Informations du membre indisponible.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="data-table__actions">
        <form method="POST" action="/group/<?= $groupId ?>/member/<?= $memberId ?>/update-access">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <input type="hidden" name="memberId" value="<?= $memberId ?>">
            <input type="hidden" name="groupId" value="<?= $groupId ?>">
            <?php if ($memberDetails['group_access'] === 'writer'): ?>
            <button type="submit" name="update_access" value="remove_write"
                class="data-table__button data-table__button--danger">Retirer les droits d'écriture dans le
                groupe</button>
            <?php else: ?>
            <button type="submit" name="update_access" value="give_write"
                class="data-table__button data-table__button--success">Donner les droits d'écriture dans le
                groupe</button>
            <?php endif; ?>
        </form>
        <form id="removeMemberForm" method="POST" action="/group/<?= $groupId ?>/member/<?= $memberId ?>/remove">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <input type="hidden" name="memberId" value="<?= $memberId ?>">
    <input type="hidden" name="groupId" value="<?= $groupId ?>">
    <button type="button" id="removeMemberButton" class="data-table__button data-table__button--danger">
        Virer le membre du groupe
    </button>
</form>
    </div>
    <br>
    <br>

    <a href="/group/<?= htmlspecialchars($groupId) ?>/members" class="data-table__back-link">Retour</a>
</div>

<!-- confirmation pour virer -->
<div id="removeMemberModal" class="modal">
    <div class="modal__content">
        <p>Êtes-vous sûr de vouloir virer ce membre du groupe ?</p>
        <button class="button button--danger" id="confirmRemove">Oui, Virer</button>
        <button class="button button--secondary" id="cancelRemove">Annuler</button>
    </div>
</div>
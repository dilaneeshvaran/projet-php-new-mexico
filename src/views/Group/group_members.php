<a href="/group/<?= htmlspecialchars($groupId) ?>" class="data-table__back-link">Retour</a>

<div class="data-table">
    <?php if (!empty($errors)): ?>
    <div class="errors">
        <div class="errors__list">
            <?php foreach ($errors as $error): ?>
            <div class="errors__item"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php $groupId = $this->data['groupId'] ?? null; ?>
    <?php $userRole = $this->data['userRole'] ?? null; ?>
    <?php if ($userRole === 'admin'): ?>
    <div class="data-table__link-actions mb-4">
        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/invite-member">
            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="data-table__button data-table__button--primary radius-1">Ajouter un membre</button>
        </form>

        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/join-requests">
            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="data-table__button data-table__button--primary radius-1">Voir les demandes reçues pour
                rejoindre
            </button>
        </form>
    </div>
    <?php endif; ?>
    <div class="data-table__container mb-6">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th class="p-4">#</th>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Prénom</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Rejoint le</th>
                    <th class="p-4">Droits dans le groupe</th>
                    <th class="p-4">Rôle</th>
                    <?php if ($userRole === 'admin'): ?>
                    <th class="p-4">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($members)): ?>
            <?php foreach ($members as $index => $member): ?>
                <?php
                $joined_at = new DateTime($member['joined_at']);
                $formattedDate = $joined_at->format('d/m/Y à H:i');
                ?>
                <tr>
                    <td data-label="#" class="p-4"><?= $index + 1 ?></td>
                    <td data-label="First Name" class="p-4"><?= htmlspecialchars($member['firstname']) ?></td>
                    <td data-label="Last Name" class="p-4"><?= htmlspecialchars($member['lastname']) ?></td>
                    <td data-label="Email" class="p-4"><?= htmlspecialchars($member['email']) ?></td>
                    <td data-label="Joined At" class="p-4"><?= htmlspecialchars($formattedDate) ?></td>
                    <td data-label="Group Access" class="p-4"><?= htmlspecialchars($member['group_access']) ?></td>
                    <td data-label="Role" class="p-4"><?= htmlspecialchars($member['role']) ?></td>
                    <?php if ($userRole === 'admin'): ?>
                    <td data-label="Actions" class="data-table__actions p-4">
                        <?php if ($member['id'] !== $_SESSION['user_id']): ?>
                        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/member/<?= htmlspecialchars($member['id']) ?>/manage">
                            <input type="hidden" name="memberId" value="<?= htmlspecialchars($member['id']) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <button type="submit" class="data-table__button data-table__button--primary radius-1 p-2">Gérer</button>
                        </form>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $userRole === 'admin' ? 8 : 7 ?>" class="data-table__empty p-8">
                        Aucun membre trouvé dans ce groupe.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
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
    <div class="data-table__actions">
        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/invite-member">
            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="data-table__button data-table__button--primary">Ajouter un membre</button>
        </form>
    </div>
    <?php endif; ?>
    <div class="data-table__container">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Joined At</th>
                    <th>Group Access</th>
                    <th>Role</th>
                    <?php if ($userRole === 'admin'): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($members)): ?>
                <?php foreach ($members as $index => $member): ?>
                <tr>
                    <td data-label="#"><?= $index + 1 ?></td>
                    <td data-label="First Name"><?= htmlspecialchars($member['firstname']) ?></td>
                    <td data-label="Last Name"><?= htmlspecialchars($member['lastname']) ?></td>
                    <td data-label="Email"><?= htmlspecialchars($member['email']) ?></td>
                    <td data-label="Joined At"><?= htmlspecialchars($member['joined_at']) ?></td>
                    <td data-label="Group Access"><?= htmlspecialchars($member['group_access']) ?></td>
                    <td data-label="Role"><?= htmlspecialchars($member['role']) ?></td>
                    <?php if ($userRole === 'admin'): ?>
                    <td data-label="Actions" class="data-table__actions">
                        <form method="POST"
                            action="/group/<?= htmlspecialchars($groupId) ?>/member/<?= htmlspecialchars($member['id']) ?>/manage">
                            <input type="hidden" name="memberId" value="<?= htmlspecialchars($member['id']) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <button type="submit" class="data-table__button data-table__button--primary">Manage</button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="<?= $userRole === 'admin' ? 8 : 7 ?>" class="data-table__empty">
                        No members found in this group.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($userRole === 'admin'): ?>
    <div class="data-table__actions">
        <form method="POST" action="/group/<?= htmlspecialchars($groupId) ?>/join-requests">
            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="data-table__button data-table__button--primary">Voir les demandes
                reçues</button>
        </form>
    </div>
    <?php endif; ?>
    <a href="/group/<?= htmlspecialchars($groupId) ?>" class="data-table__back-link">back</a>
</div>
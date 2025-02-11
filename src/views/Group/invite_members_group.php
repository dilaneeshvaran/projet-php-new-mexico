<a href="/group/<?= htmlspecialchars($groupId) ?>/members" class="data-table__back-link">Retour</a>

<div class="data-table">
    <h1 class="data-table__title">Rechercher un utilisateur</h1>

    <?php $groupId = $this->data['groupId'] ?? null; ?>

    <form class="data-table__search-form" method="POST"
        action="/group/<?= htmlspecialchars($groupId) ?>/invite-member/search">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
        <input type="text" name="searchUser" placeholder="Rechercher par le nom-prénom ou l'email..." required>
        <button type="submit">Rechercher</button>
    </form>
    <?php if (!empty($errors)): ?>
    <div class="data-table__empty">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars(is_array($error) ? json_encode($error) : $error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($this->data['users'])): ?>
    <div class="data-table__container">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Crée Le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->data['users'] as $user): ?>
<?php
    $createdAt = new DateTime($user->getCreatedAt());
    $formattedDate = $createdAt->format('d/m/Y à H:i');
?>
                <tr>
                    <td><?= htmlspecialchars($user->getFirstname()) ?></td>
                    <td><?= htmlspecialchars($user->getLastname()) ?></td>
                    <td><?= htmlspecialchars($user->getEmail()) ?></td>
                    <td><?= htmlspecialchars($formattedDate) ?></td>
                    <td>
                        <?php if ($user->invitationStatus === 'member'): ?>
                        <span class="data-table__status data-table__status--success">Membre</span>
                        <?php elseif (isset($user->invitationStatus)): ?>
                        <span class="data-table__status data-table__status--warning">
                            <?= htmlspecialchars(ucfirst($user->invitationStatus)) ?>
                        </span>
                        <?php else: ?>
                        <form method="POST"
                            action="/group/<?= htmlspecialchars($groupId) ?>/invite-member/<?= htmlspecialchars($user->getId()) ?>/send">
                            <input type="hidden" name="csrf_token"
                                value="<?= htmlspecialchars($this->data['csrfToken']) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
                            <input type="hidden" name="memberId" value="<?= htmlspecialchars($user->getId()) ?>">
                            <button class="data-table__button data-table__button--primary"
                                type="submit">Inviter</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="data-table__empty">Aucun Utilisateur trouvé.</p>
    <?php endif; ?>
</div>
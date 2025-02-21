<a href="/group/<?= htmlspecialchars($groupId) ?>/members" class="data-table__back-link ml-2 mb-2">Retour</a>

<div class="data-table p-4">
    <h1 class="data-table__title mb-6">Rechercher un utilisateur</h1>

    <?php $groupId = $this->data['groupId'] ?? null; ?>

    <form class="data-table__search-form mb-4" method="POST"
        action="/group/<?= htmlspecialchars($groupId) ?>/invite-member/search">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupId) ?>">
        <input type="text" name="searchUser" placeholder="Rechercher par le nom-prénom ou l'email..." required class="p-2 radius-1">
        <button type="submit" class="p-2 radius-1">Rechercher</button>
    </form>

    <?php if (!empty($errors)): ?>
    <div class="data-table__empty p-8">
        <?php foreach ($errors as $error): ?>
        <p class="mb-2"><?= htmlspecialchars(is_array($error) ? json_encode($error) : $error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($this->data['users'])): ?>
    <div class="data-table__container mb-6">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Prénom</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Crée Le</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->data['users'] as $user): ?>
                <?php
                    $createdAt = new DateTime($user->getCreatedAt());
                    $formattedDate = $createdAt->format('d/m/Y à H:i');
                ?>
                <tr>
                    <td class="p-4" data-label="Nom"><?= htmlspecialchars($user->getFirstname()) ?></td>
                    <td class="p-4" data-label="Prénom"><?= htmlspecialchars($user->getLastname()) ?></td>
                    <td class="p-4" data-label="Email"><?= htmlspecialchars($user->getEmail()) ?></td>
                    <td class="p-4" data-label="Crée Le"><?= htmlspecialchars($formattedDate) ?></td>
                    <td class="p-4" data-label="Action">
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
                            <button class="data-table__button data-table__button--primary p-2 radius-1"
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
    <p class="data-table__empty p-8">Aucun Utilisateur trouvé.</p>
    <?php endif; ?>
</div>
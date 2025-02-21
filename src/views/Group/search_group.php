<a href="/" class="data-table__back-link ml-2 mb-2">Retour</a>

<div class="data-table p-4">
    <h1 class="data-table__title mb-6">Rechercher un groupe</h1>

    <form class="data-table__search-form mb-4" method="POST" action="/group/search/result">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="text" name="searchGroupName" placeholder="Rechercher un groupe..." required class="p-2 radius-1">
        <button type="submit" class="p-2 radius-1">Rechercher</button>
    </form>

    <?php if (!empty($errors)): ?>
    <div class="data-table__empty data-table__color p-8">
        <?php foreach ($errors as $error): ?>
        <p class="mb-2"><?= htmlspecialchars(is_array($error) ? implode(', ', $error) : $error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($groups)): ?>
    <div class="data-table__container mb-6">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Description</th>
                    <th class="p-4">Crée le</th>
                    <th class="p-4">Accèss</th>
                    <th class="p-4">Membres Totale</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group): ?>
                <?php
                    $createdAt = new DateTime($group->getCreatedAt());
                    $formattedDate = $createdAt->format('d/m/Y à H:i');
                ?>
                <tr>
                    <td data-label="Name" class="p-4"><?= htmlspecialchars($group->getName()) ?></td>
                    <td data-label="Description" class="p-4"><?= htmlspecialchars($group->getDescription()) ?></td>
                    <td data-label="Created At" class="p-4"><?= htmlspecialchars($formattedDate) ?></td>
                    <td data-label="Access Type" class="p-4"><?= htmlspecialchars($group->getAccessType()) ?></td>
                    <td data-label="Total Members" class="p-4"><?= htmlspecialchars($group->total_members) ?></td>
                    <td data-label="Action" class="p-4">
                        <?php if ($group->is_member): ?>
                        <button class="data-table__button p-2 radius-1" disabled>Déjà Membre</button>
                        <?php else: ?>
                        <?php if ($group->getAccessType() === 'open'): ?>
                        <form method="POST" action="/group/join">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                            <button class="data-table__button data-table__button--primary p-2 radius-1"
                                type="submit">Rejoindre</button>
                        </form>
                        <?php elseif ($group->getAccessType() === 'closed'): ?>
                        <button class="data-table__button data-table__button--danger p-2 radius-1" disabled>Fermé</button>
                        <?php elseif ($group->getAccessType() === 'on_invitation'): ?>
                        <form method="POST" action="/group/join">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                            <button class="data-table__button data-table__button--warning p-2 radius-1" type="submit">Demander Pour
                                Rejoindre</button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="data-table__empty p-8">Aucun groupe trouvé.</p>
    <?php endif; ?>
</div>
<div class="data-table">
    <h1 class="data-table__title">Search Groups</h1>

    <form class="data-table__search-form" method="POST" action="/group/search/result">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="text" name="searchGroupName" placeholder="Search for groups..." required>
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($errors)): ?>
    <div class="data-table__empty">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars(is_array($error) ? json_encode($error) : $error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($groups)): ?>
    <div class="data-table__container">
        <table class="data-table__table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Access Type</th>
                    <th>Total Members</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group): ?>
                <tr>
                    <td data-label="Name"><?= htmlspecialchars($group->getName()) ?></td>
                    <td data-label="Description"><?= htmlspecialchars($group->getDescription()) ?></td>
                    <td data-label="Created At"><?= htmlspecialchars($group->getCreatedAt()) ?></td>
                    <td data-label="Access Type"><?= htmlspecialchars($group->getAccessType()) ?></td>
                    <td data-label="Total Members"><?= htmlspecialchars($group->total_members) ?></td>
                    <td data-label="Action">
                        <?php if ($group->is_member): ?>
                        <button class="data-table__button" disabled>You are a member</button>
                        <?php else: ?>
                        <?php if ($group->getAccessType() === 'open'): ?>
                        <form method="POST" action="/group/join">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                            <button class="data-table__button data-table__button--primary" type="submit">Join</button>
                        </form>
                        <?php elseif ($group->getAccessType() === 'closed'): ?>
                        <button class="data-table__button data-table__button--danger" disabled>Closed</button>
                        <?php elseif ($group->getAccessType() === 'on_invitation'): ?>
                        <form method="POST" action="/group/join">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                            <button class="data-table__button data-table__button--warning" type="submit">Request to
                                Join</button>
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
    <p class="data-table__empty">No groups found.</p>
    <?php endif; ?>

    <a href="/" class="data-table__back-link">Back</a>
</div>
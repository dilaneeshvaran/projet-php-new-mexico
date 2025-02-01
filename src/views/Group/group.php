<?php
$group = $this->data['group'] ?? null;
$groupAccess = $this->data['groupAccess'] ?? null;
$groupRole = $this->data['groupRole'] ?? null;



if ($group) {
    echo "Group ID: " . htmlspecialchars($group->getId()) . "<br>";
    echo "Group Name: " . htmlspecialchars($group->getName()) . "<br>";
    echo "Group Description: " . htmlspecialchars($group->getDescription()) . "<br>";
    echo "Created At: " . htmlspecialchars($group->getCreatedAt()) . "<br>";
} else {
    echo "No group data provided.";
}
?>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<?php if ($groupAccess === 'writer'): ?>
    <a href="/group/<?= $group->getId() ?>/photos">Voir/publier des photos</a>
<?php else: ?>
    <a href="/group/<?= $group->getId() ?>/photos">Voir les photos</a>
<?php endif; ?>

<?php if ($groupRole === 'admin'): ?>
    <a href="/group/<?= $group->getId() ?>/members">Voir et gérer les membres</a>
    <a href="/group/<?= $group->getId() ?>/settings">Parametres du groupe</a>
<?php endif; ?>

<?php if ($groupRole === 'member'): ?>
    <a href="/group/<?= $group->getId() ?>/members">Voir les membres</a>
    <form method="POST" action="/group/member/settings">
        <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit">Parametres</button>
    </form>
<?php endif; ?>
<a href="/">Back</a>
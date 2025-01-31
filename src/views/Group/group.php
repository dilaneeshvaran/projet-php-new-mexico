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

<?php if ($groupAccess === 'writer'): ?>
    <a href="/group/<?= $group->getId() ?>/photos">Voir/publier des photos</a>
<?php else: ?>
    <a href="/group/<?= $group->getId() ?>/photos">Voir les photos</a>
<?php endif; ?>

<?php if ($groupRole === 'admin'): ?>
    <a href="/group/<?= $group->getId() ?>/members">Voir et gérer les membres</a>
    <a href="/group/<?= $group->getId() ?>/settings">Parametres du groupe</a>
<?php endif; ?>
<a href="/">Back</a>
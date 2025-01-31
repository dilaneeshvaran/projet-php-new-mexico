<?php
$group = $this->data['group'] ?? null;

if ($group) {
    echo "Group ID: " . htmlspecialchars($group->getId()) . "<br>";
    echo "Group Name: " . htmlspecialchars($group->getName()) . "<br>";
    echo "Group Description: " . htmlspecialchars($group->getDescription()) . "<br>";
    echo "Created At: " . htmlspecialchars($group->getCreatedAt()) . "<br>";
} else {
    echo "No group data provided.";
}
?>

<a href="/group/<?= $group->getId() ?>/photos">Voir/publier des photos</a>
<a href="/group/<?= $group->getId() ?>/members">Voir et gérer les membres</a>
<a href="/group/<?= $group->getId() ?>/settings">Parametres du groupe</a>
<a href="/">Back</a>
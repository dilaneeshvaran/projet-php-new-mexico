<?php

use App\Controllers\MainController;
use App\Controllers\PageController;

$mainController = new MainController();

if (isset($_SESSION["firstname"])) {
    if ($mainController->getPseudo() == $_SESSION["firstname"]) {
        echo "Welcome back " . htmlspecialchars($mainController->getPseudo());
        echo '<br><a href="/logout">Se déconnecter</a>';
    }
?>
<h1>Vos Groupes:</h1>
<br>
<?php foreach ($groups as $groupData):
    $group = $groupData['group']; ?>
    <a href="group/<?= htmlspecialchars($group->getId()) ?>">
        <?= htmlspecialchars($group->getName()) ?>
    </a><br>
<?php endforeach; ?>

<?php
    $pageController = new PageController();
    $pageController->show();
} else {
    header('Location: /login');
    exit();
}
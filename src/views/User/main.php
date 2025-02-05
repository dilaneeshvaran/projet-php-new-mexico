<?php

use App\Controllers\MainController;
use App\Controllers\PageController;

$mainController = new MainController();
?>

<div class="main">
    <div class="main__welcome">
        <?php if (isset($_SESSION["firstname"])): ?>
            <p>Welcome back <?= htmlspecialchars($mainController->getPseudo()); ?></p>
            <a href="/logout" class="main__logout">Se déconnecter</a>
    </div>

    <div class="main__groups">
        <h1>Vos Groupes:</h1>
        <?php foreach ($groups as $groupData): 
            $group = $groupData['group']; ?>
            <a href="group/<?= htmlspecialchars($group->getId()) ?>">
                <?= htmlspecialchars($group->getName()) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="main__actions">
        <a href="/group/create">Créer un groupe</a>
        <a href="/group/search/result">Rejoindre un groupe</a>
        <a href="/invitations">Voir les invitations reçues</a>
    </div>
</div>



    <?php else:
            //$pageController = new PageController();
    //$pageController->show();
            header('Location: /login');
            exit();
        
    endif; ?>
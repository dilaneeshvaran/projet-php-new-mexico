<?php

use App\Controllers\MainController;
use App\Controllers\PageController;

$mainController = new MainController();

if (isset($_SESSION["firstname"])) {
    if ($mainController->getPseudo() == $_SESSION["firstname"]) {
        echo "Welcome back " . htmlspecialchars($mainController->getPseudo());
        echo '<br><a href="/logout">Se déconnecter</a>';
    }

    $pageController = new PageController();
    $pageController->show();
} else {
    header('Location: /login');
    exit();
}
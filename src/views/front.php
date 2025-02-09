<?php 
use App\Controllers\MainController; 
use App\Controllers\PageController; 

$mainController = new MainController(); 
$currentUrl = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title ?? "Titre de ma page"); ?></title>
    <meta name="description"
        content="<?php echo htmlspecialchars($description ?? "Ceci est la description de la page"); ?>">
    <link rel="stylesheet" href="/assets/main.css">
    <script src="/assets/main.js"></script>
</head>

<body>
    <div class="container">
        <div class="navbar">
            <a href="/" class="navbar__logo">
                <img src="/assets/logo.png" alt="New Mexico" class="logo">
            </a>

            <div class="navbar__header">
                <a class="navbar__label" href="/">New Mexico</a>
                <?php if (isset($_SESSION["firstname"])): ?>
    <div class="navbar__actions">
    <img src="/assets/darkmode.png" alt="Dark Mode" class="theme-icon" width="25" height="25">

        <button id="darkModeToggle" class="theme__button">
            Dark Mode
        </button>
        <a href="/logout" class="navbar__header-action">Se déconnecter</a>
    </div>
<?php else: ?>
                <?php if ($currentUrl == '/login'): ?>
                <a href="/register" class="navbar__header-action">S'inscrire</a>
                <?php else: ?>
                <a href="/login" class="navbar__header-action">Se connecter</a>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <?php echo $content; ?>
    </div>
    <footer class="footer">
            <p class="footer__copyright">© 2025 New Mexico - EESHVARAN Dilan</p>
</footer>

    </div>

</body>

</html>
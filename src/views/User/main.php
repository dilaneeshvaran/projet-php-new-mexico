<div class="home">
    <div class="home__container">
        <?php if (isset($_SESSION["firstname"])): ?>


        <div class="home__actions">
            <a href="/group/create" class="home__actions-button">Créer un groupe</a>
            <a href="/group/search/result" class="home__actions-button">Rejoindre un groupe</a>
            <a href="/invitations" class="home__actions-button home__actions-button--secondary">
                Voir les invitations reçues
            </a>
        </div>

        <div class="home__groups">
            <h2>Vos Groupes</h2>
            <div class="home__groups-grid">
                <?php foreach ($groups as $groupData): 
                        $group = $groupData['group']; ?>
                <a href="group/<?= htmlspecialchars($group->getId()) ?>" class="home__groups-item">
                    <?= htmlspecialchars($group->getName()) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>


        <?php else:
            header('Location: /login');
            exit();
        endif; ?>
    </div>
</div>
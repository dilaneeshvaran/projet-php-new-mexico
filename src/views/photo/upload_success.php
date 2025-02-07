<?php $groupId = $this->data['groupId'] ?? null; ?>
<div class="success">
    <div class="success__container">
        <div class="success__icon">✓</div>
        <p class="success__message">L'image a bien été postée</p>
        <a href="/group/<?=htmlspecialchars($groupId)?>/photos" class="success__link">Retour à l'accueil</a>
    </div>
</div>
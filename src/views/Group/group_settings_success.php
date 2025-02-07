<?php $groupId = $this->data['groupId'] ?? null; ?>
<div class="success">
    <div class="success__container">
        <div class="success__icon">✓</div>
        <p class="success__message">Les paramètres ont bien été enregistrés</p>
        <a href="/group/<?=htmlspecialchars($groupId)?>" class="success__link">Retour</a>
    </div>
</div>
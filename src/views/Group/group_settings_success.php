<?php $groupId = $this->data['groupId'] ?? null; ?>
<div class="success">
    <p class="success__message">Les paramètres ont bien été enregistrés.</p>
    <a href="/group/<?=htmlspecialchars($groupId)?>" class="success__link">Retour</a>
</div>

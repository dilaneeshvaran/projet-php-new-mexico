
<?php $group = $this->data['group'] ?? null; ?>
<?php $groupAccess = $this->data['group_access'] ?? null; ?>

<a href="/group/<?= $group->getId() ?>">Back</a>
<?php if ($groupAccess === 'writer'): ?>
    <a href="/group/<?= $group->getId() ?>/upload">Ajouter une Photo</a>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 20px;">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars(is_array($error) ? json_encode($error) : $error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<h2>Group Photos</h2>
<?php if (!empty($photos)): 
?>
    
    <ul>
    <?php foreach ($photos as $photo): ?>
        <?php
        $deleteAccess = ($photo->getUserId() === $userId || $userRole === 'admin');
        ?>
        <li>
            <h3><?= htmlspecialchars($photo->getOriginalName()) ?></h3>
            <p>Title: <?= htmlspecialchars($photo->getTitle()) ?></p>
            <p>Description: <?= htmlspecialchars($photo->getDescription()) ?></p>
            <p>Uploaded by: <?= htmlspecialchars($photo->userFullName) ?></p>
            <p>Uploaded at <?= htmlspecialchars($photo->getCreatedAt()) ?></p>
            <img src="/uploads/<?= htmlspecialchars($photo->getFilename()) ?>" alt="<?= htmlspecialchars($photo->getOriginalName()) ?>" width="200">
            
            <!-- Show delete button only if the user has permission -->
            <?php if ($deleteAccess): ?>
                <form action="/group/<?= htmlspecialchars($group->getId()) ?>/photo/<?= htmlspecialchars($photo->getId()) ?>/delete" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this photo?');">
                    <input type="hidden" name="photoId" value="<?= htmlspecialchars($photo->getId()) ?>">
                    <input type="hidden" name="groupId" value="<?= htmlspecialchars($group->getId()) ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <button type="submit">Delete</button>
                </form>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <p>No photos available.</p>
<?php endif; ?>

<a href="/group/<?= $group->getId() ?>">Back</a>

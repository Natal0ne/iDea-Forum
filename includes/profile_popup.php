<div class="profile-popup-header">
    <img src="<?php echo $post['user_avatar_url']; ?>" alt="Avatar">
    <p><?php echo $post['user_username']; ?></p>
    <?php echo $post['user_role']; ?>
</div>
<div class="profile-popup-body">
    <div class="profile-popup-bio">
        <p>
           <?php echo !isset($post['user_bio']) ? 'Bio not available.' : htmlspecialchars($post['user_bio']); ?>
        </p>
    </div>
    <div class="profile-popup-info">
        <div class="profile-popup-info-labels">
            <div class="profile-popup-info-item-label">Last Active: </div>
            <div class="profile-popup-info-item-label">Joined:</div>
            <div class="profile-popup-info-item-label">Reputation: </div>
            <?php echo !$post['user_location'] ? '' : '<div class="profile-popup-info-item-label">Location: </div>' ?>
        </div>
        <div class="profile-popup-info-values">
            <div class="profile-popup-info-item-value"><?php echo date('j M Y', strtotime($post['user_last_active_at'])); ?></div>
            <div class="profile-popup-info-item-value"><?php echo date('F Y', strtotime($post['user_created_at'])); ?></div>
            <div class="profile-popup-info-item-value"><?php echo $post['user_reputation'] ?></div>
            <?php echo !$post['user_location'] ? '' : '<div class="profile-popup-info-item-value">' . $post['user_location'] . '</div>' ?>
        </div>
    </div>
</div>

<div class="profile-popup-header">
    <img src="<?php echo $post['user_avatar_url']; ?>" alt="<?php echo $post['user_name']; ?>">
    <p><?php echo $post['user_username']; ?></p>
</div>
<div class="profile-popup-body">
    <div class="profile-popup-info">
        <div class="profile-popup-info-labels">
            <div class="profile-popup-info-item-label">Last Active: </div>
            <div class="profile-popup-info-item-label">Joined:</div>
            <div class="profile-popup-info-item-label">Reputation: </div>
        </div>
        <div class="profile-popup-info-values">
            <div class="profile-popup-info-item-value"><?php echo date('j M Y', strtotime($post['user_last_active_at'])); ?></div>
            <div class="profile-popup-info-item-value"><?php echo date('F Y', strtotime($post['user_created_at'])); ?></div>
            <div class="profile-popup-info-item-value"><?php echo $post['user_reputation'] ?></div>
        </div>
    </div>
</div>

<div class="reply-box">
    <div class="reply-header">
        <h3>Replying to <span class="reply-target-user"></span></h3>
    </div>
    
    <form action="includes/reply_post_process.php" method="POST" enctype="multipart/form-data">
        <!-- Dati tecnici necessari al database -->
        <input type="hidden" name="thread_id" value="<?php echo $thread['id']; ?>">
        <input type="hidden" name="thread_slug" value="<?php echo $thread['slug']; ?>">
        <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>">

        <textarea name="reply-content" class="reply-textarea" rows="5" placeholder="Write your reply..." required></textarea>

        <label for="attachments_<?php echo $parent_id; ?>" class="drop-zone reply-drop-zone">
            <span>Drag & Drop images here or click to select</span>
        </label>
        <input type="file" id="attachments_<?php echo $parent_id; ?>" name="attachments[]" multiple accept="image/*" style="display: none;">
        <div class="thumbnails reply-file-list"></div>
        
        <div class="reply-footer">
            <button type="button" class="close-reply-btn">Close</button>
            <button type="submit" class="btn-submit">Post Reply</button>
        </div>
    </form>
</div>
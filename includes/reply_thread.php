<div class="reply-box">
    <div class="reply-header">
        <h3>Replying to <span id="replyTargetUser"></span></h3>
    </div>
    
    <form action="includes/post_reply_process.php" method="POST" enctype="multipart/form-data">
        <!-- Dati tecnici necessari al database -->
        <input type="hidden" name="thread_id" value="<?php echo $thread['id']; ?>">
        <input type="hidden" name="thread_slug" value="<?php echo $thread['slug']; ?>">
        <input type="hidden" name="parent_id" id="parentIdInput" value="">

        <textarea name="reply-content" id="reply-content" rows="5" placeholder="Write your reply..." required></textarea>
        
        <div class="reply-footer">
            <div class="file-upload">
                <input type="file" id="replyFileInput" name="attachments[]" multiple accept="image/*" style="display: none;">
            <div id="replyFileList" class="thumbnails"></div>
            </div>
            <button type="button" onclick="hideReplyForm()" class="close-reply-btn">Close</button>
            <button type="submit" class="btn-submit">Post Reply</button>
        </div>
    </form>
</div>
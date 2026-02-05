<div id="newThreadModal" class="modal hidden<?php echo $new_thread_modal_class?>">

    <div class="modal-overlay"></div>

    <div class="thread-modal-content">
        
        <div class="close-btn-div">
            <span id='newThreadCloseBtn' class="close-btn">&times;</span>
        </div>

        <h2>New Thread</h2>

        <div id='newThreadErrorMsg'>
            <?php if (!empty($new_thread_error_message)): ?>
                <div class="error-box">
                    <p class="error"><?php echo htmlspecialchars($new_thread_error_message); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <form action="includes/new_thread_process.php" method="POST">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required placeholder="Title" />
            <label for="body">Body</label>
            <textarea id="boxy" rows="10" cols="70" placeholder="Body text (optional)"></textarea>
            <label for="fileInput" class="drop-zone" id="dropZone">
                <span>Drag & Drop images here or click to select</span>
            </label>
            <input type="file" id="fileInput" multiple accept="image/*" style="display: none;">
            <div id="fileList" class="thumbnails"></div>
            <button type="submit" class="btn-submit">Create</button>
        </form>
    </div>
</div>
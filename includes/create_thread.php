<?php
// includes/create_thread.php - The Modal Component
?>
<div id="createThreadModal" class="modal <?php echo !empty($_SESSION['create_error']) ? 'active' : ''; ?>">
    <div class="modal-overlay"></div>
    <div class="modal-content modal-large">
        <span class="close-btn" data-target="createThreadModal">&times;</span>
        <h2>Create New Thread</h2>
        
        <?php if (isset($_SESSION['create_error'])): ?>
            <p class="error-msg"><?php echo $_SESSION['create_error']; ?></p>
            <?php unset($_SESSION['create_error']); ?>
        <?php endif; ?>

        <form id="createThreadForm" action="includes/process_new_thread.php" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="thread_title">Title</label>
                <input type="text" id="thread_title" name="title" required placeholder="Question or Subject" style="font-weight: bold;">
            </div>

            <div class="form-group">
                <label for="thread_content">Body</label>
                <textarea id="thread_content" name="content" required placeholder="Explain your question... (You can drag images here)" rows="6"></textarea>
            </div>

            <!-- Drag & Drop Zone -->
            <div id="drop-zone" class="drop-zone">
                <p>Drag & Drop images here or click to select</p>
                <input type="file" name="attachments[]" id="file_input" multiple accept="image/*" style="display: none;">
                <div id="preview-area" class="preview-area"></div>
            </div>

            <button type="submit" class="btn-submit">Post Thread</button>
        </form>
    </div>
</div>

<style>
/* Modal Large Override */
.modal-large {
    max-width: 600px;
    width: 95%;
}

textarea {
    width: 100%;
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    resize: vertical;
    font-family: inherit;
}

.drop-zone {
    border: 2px dashed #ccc;
    border-radius: 6px;
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
    transition: background 0.3s, border-color 0.3s;
    cursor: pointer;
    background: #fafafa;
}

.drop-zone.dragover {
    border-color: #50616d;
    background: #eef2f5;
}

.preview-area {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
    justify-content: center;
}

.preview-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ddd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file_input');
    const previewArea = document.getElementById('preview-area');
    
    // Trigger file input on click
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('dragover');
    }

    function unhighlight(e) {
        dropZone.classList.remove('dragover');
    }

    // Handle Drop
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
        
        // Manually assign to input (works in modern browsers)
        fileInput.files = files; 
    }

    // Handle Selection
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        previewArea.innerHTML = ''; // Clear previous
        ([...files]).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function() {
                    const img = document.createElement('img');
                    img.src = reader.result;
                    img.className = 'preview-img';
                    previewArea.appendChild(img);
                }
            }
        });
    }
});
</script>

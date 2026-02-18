<div id="newThreadModal" class="modal hidden">

    <div class="modal-overlay"></div>

    <div class="thread-modal-content">

        <div class="close-btn-div">
            <span id='newThreadCloseBtn' class="close-btn">&times;</span>
        </div>

        <h2>New Thread</h2>

        <form action="includes/new_thread_process.php" method="POST" enctype="multipart/form-data">

            <label for="category">Category:</label>
            <select name="category_id" id="category" required>
                <option value="" disabled selected>Choose a category...</option>
                <?php

                $conn = connect_db();
                
                $query_cats = "SELECT id, name FROM categories ORDER BY name ASC";
                $res_cats = pg_query($conn, $query_cats);

                if ($res_cats) {
                    while ($row = pg_fetch_assoc($res_cats)) {
                        $cat_id = $row['id'];
                        // htmlspecialchars serve a evitare problemi se il nome contiene caratteri speciali
                        $cat_name = htmlspecialchars($row['name']);

                        echo "<option value=\"$cat_id\">$cat_name</option>";
                    }
                } else {
                    echo "<option value=\"\">Error loading categories.</option>";
                }

                pg_close($conn);
                ?>
            </select>

            <label for="title">Title</label>
            <input type="text" id="title" name="title" required placeholder="Title" />
            <label for="content">Body</label>
            <textarea id="content" name="content" rows="10" cols="70" placeholder="Body text (optional)"></textarea>
            <label for="fileInput" class="drop-zone" id="dropZone">
                <span>Drag & Drop images here or click to select</span>
            </label>
            <input type="file" id="fileInput" name="attachments[]" multiple accept="image/*" style="display: none;">
            <div id="fileList" class="thumbnails"></div>
            <button type="submit" class="btn-submit">Create</button>
        </form>
    </div>
</div>
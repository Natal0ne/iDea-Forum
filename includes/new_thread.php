<div id="newThreadModal" class="modal hidden">

    <div class="modal-overlay"></div>

    <div class="thread-modal-content">

        <div class="close-btn-div">
            <span id='newThreadCloseBtn' class="close-btn">&times;</span>
        </div>

        <h2>New Thread</h2>

        <form id="newThreadForm" action="includes/new_thread_process.php" method="POST" enctype="multipart/form-data">

            <label for="category">Category:</label>
            <select name="category_id" id="category" required>
                <option value="" disabled selected>Choose a category...</option>
                <?php
                $conn = connect_db();

                $query_cats = "SELECT id, name, parent_id FROM categories ORDER BY parent_id ASC NULLS FIRST, name ASC";
                $res_cats = pg_query($conn, $query_cats);

                if ($res_cats) {
                    $all_categories = pg_fetch_all($res_cats) ?: [];

                    $parents = [];
                    $children = [];
                    $admin_category = null;

                    foreach ($all_categories as $cat) {
                        if ($cat['id'] == 1) {
                            $admin_category = $cat;
                        } elseif ($cat['parent_id'] === null) {
                            $parents[] = $cat;
                        } else {
                            $children[$cat['parent_id']][] = $cat;
                        }
                    }

                    // Se l'utente è admin, metto la categoria 1 in alto e selezionabile
                    if ($admin_category && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                        $adm_name = htmlspecialchars($admin_category['name']);
                        echo "<option value=\"{$admin_category['id']}\">---- $adm_name (Admin Only) ----</option>";

                        // Se la categoria admin ha dei figli, li metto subito sotto (anche se non dovrebbe)
                        if (isset($children[$admin_category['id']])) {
                            foreach ($children[$admin_category['id']] as $child) {
                                echo "<option value=\"{$child['id']}\">&nbsp;&nbsp;&nbsp;" . htmlspecialchars($child['name']) . "</option>";
                            }
                        }
                    }

                    // Scorro le categorie
                    foreach ($parents as $parent) {
                        // Skip categoria admin se l'abbiamo già gestita o se non siamo admin
                        if ($parent['id'] == 1) continue;

                        $parent_name = htmlspecialchars($parent['name']);
                        $parent_id = $parent['id'];

                        if (isset($children[$parent_id])) {
                            // Se ha figli, uso optgroup (padre non selezionabile)
                            echo "<optgroup label=\"$parent_name\">";
                            foreach ($children[$parent_id] as $child) {
                                $child_id = $child['id'];
                                $child_name = htmlspecialchars($child['name']);
                                echo "<option value=\"$child_id\">$child_name</option>";
                            }
                            echo "</optgroup>";
                        } else {
                            // Se è un padre senza figli, lo mostro disabilitato (come titolo)
                            echo "<option value=\"\" disabled>── $parent_name ──</option>";
                        }
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

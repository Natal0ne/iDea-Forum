<?php
// includes/feed.php
require_once __DIR__ . '/db_connect.php';

// Fetch threads with their root post content and vote score
$sql = "
    SELECT 
        t.id AS thread_id, 
        t.title, 
        t.created_at, 
        u.username, 
        p.id AS root_post_id, 
        p.content,
        (SELECT file_url FROM post_attachments WHERE post_id = p.id LIMIT 1) as image_path,
        (SELECT COALESCE(SUM(vote_value), 0) FROM post_votes WHERE post_id = p.id) as score,
        (SELECT COUNT(*) FROM posts WHERE thread_id = t.id AND parent_id IS NOT NULL) as reply_count
    FROM threads t
    JOIN posts p ON t.id = p.thread_id
    JOIN users u ON t.user_id = u.id
    WHERE p.parent_id IS NULL
    ORDER BY t.created_at DESC
";

try {
    $threads = $conn->query($sql)->fetchAll();
} catch (PDOException $e) {
    // Fallback if table doesn't exist or query fails (e.g. during dev transitions)
    $threads = [];
    echo "<!-- Error loading feed: " . $e->getMessage() . " -->";
}
?>

<div class="thread-feed">
    <?php if (empty($threads)): ?>
        <p style="text-align: center; color: #888;">No threads found. Be the first to post!</p>
    <?php else: ?>
        <?php foreach ($threads as $thread): ?>
            <article class="thread-box" id="thread-<?php echo $thread['thread_id']; ?>">
                <div class="thread-header">
                    <h3><strong><?php echo htmlspecialchars($thread['title']); ?></strong></h3>
                    <span class="meta">
                        Posted by <strong><?php echo htmlspecialchars($thread['username']); ?></strong> • 
                        <?php echo date('M d, Y', strtotime($thread['created_at'])); ?>
                    </span>
                </div>

                <div class="thread-body">
                    <p><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>
                    
                    <?php if (!empty($thread['image_path'])): ?>
                        <div class="thread-image-container">
                            <img src="<?php echo htmlspecialchars($thread['image_path']); ?>" alt="Thread Image" class="thread-image">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="thread-actions">
                    <div class="votes">
                        <button class="vote-btn upvote" data-id="<?php echo $thread['root_post_id']; ?>" data-type="1">👍</button>
                        <span class="score" id="score-<?php echo $thread['root_post_id']; ?>"><?php echo $thread['score']; ?></span>
                        <button class="vote-btn downvote" data-id="<?php echo $thread['root_post_id']; ?>" data-type="-1">👎</button>
                    </div>
                    <div class="replies">
                        <span style="font-size: 0.9em; color: #666; margin-left: 15px;">
                            <?php echo $thread['reply_count']; ?> Replies
                        </span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    /* Feed Styles */
    .thread-feed {
        max-width: 800px;
        margin: 0 auto;
    }
    .thread-box {
        background: #fff;
        border: 1px solid #e1e8ed;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .thread-header h3 {
        margin: 0 0 5px 0;
        color: #2c3e50;
    }
    .meta {
        font-size: 0.85em;
        color: #7f8c8d;
    }
    .thread-body {
        margin: 15px 0;
        color: #34495e;
        line-height: 1.6;
    }
    .thread-image {
        max-width: 100%;
        border-radius: 6px;
        margin-top: 10px;
        max-height: 500px;
        object-fit: contain;
    }
    .thread-actions {
        display: flex;
        align-items: center;
        border-top: 1px solid #f1f1f1;
        padding-top: 10px;
    }
    .votes {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .vote-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
        transition: transform 0.1s;
    }
    .vote-btn:hover {
        transform: scale(1.1);
    }
    .score {
        font-weight: bold;
        min-width: 20px;
        text-align: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vote Logic
    const voteBtns = document.querySelectorAll('.vote-btn');
    voteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            <?php if (!isset($_SESSION['user_id'])): ?>
                document.getElementById('loginModal').classList.add('active');
            <?php else: ?>
                const postId = this.dataset.id;
                const voteType = this.dataset.type;

                fetch('includes/process_vote.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `post_id=${postId}&vote_type=${voteType}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`score-${postId}`).textContent = data.new_score;
                    } else if (data.error === 'Not logged in') {
                        document.getElementById('loginModal').classList.add('active');
                    }
                });
            <?php endif; ?>
        });
    });
});
</script>

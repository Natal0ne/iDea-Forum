<?php
$conn = connect_db();

$query = "SELECT *,(SELECT COUNT(*) FROM threads WHERE category_id = categories.id) AS number_of_threads FROM categories";

$result = pg_query($conn, $query);

if ($result && pg_num_rows($result) > 0) {

    $roots = [];
    $children = [];

    while ($c = pg_fetch_assoc($result)) {
    
        if ($c['parent_id'] === NULL) {
            $roots[] = $c;
        } else {
            $children[] = $c;
        }
    }
}

$query = "SELECT * FROM users WHERE last_active_at > NOW() - INTERVAL '5 minutes' ";

$result = pg_query($conn, $query);

if ($result && pg_num_rows($result) > 0) {

    $online_users = pg_fetch_all($result);

}

pg_close($conn);

?>

<div class="main"> <!-- TODO: DA ORDINARE SECONDO SORT ORDER -->
    <?php if (isset($roots)): ?>
        <?php foreach ($roots as $r): ?>
            <div class="root-category-block">
                <a href="<?php echo $r['slug']; ?>" ><h2><?php echo $r['name']; ?></h2></a>
                <div class="categories">
                    <?php $empty = true; ?>
                    <?php foreach ($children as $c): ?> 
                        <?php if ($c['parent_id'] === $r['id']): ?>
                            <?php $empty = false; ?>
                            <div class="category">
                                <div class="category-main">
                                    <a href="<?php echo $c['slug']; ?>"><h4> <?php echo $c['name']; ?> </h4></a>
                                    <p> <?php echo $c['description']; ?></p>
                                </div>
                                <div class="category-stat">
                                    <div>threads: <?php echo $c['number_of_threads'] ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach ?>
                    <?php if ($empty): ?>
                        <div class="category">
                            <div class="category-main">
                                <p> <?php echo $r['description']; ?> </p>
                            </div>
                            <div class="category-stat">
                                <div>threads: <?php echo $r['number_of_threads'] ?></div>
                            </div>
                        </div>
                    <?php endif; ?> 
                </div>
            </div>
        <?php endforeach ?>
    <?php endif; ?>
</div>

<div class="side">
    <div class="online-users">
        <h3>online users</h3>
        <div class="users">
            <?php if (isset($online_users)): ?>
                <?php foreach ($online_users as $u): ?>
                <div class="user <?php echo $u['role']; ?>">
                    <div class="avatar-container"> <!-- Riutilizzo la classe della navbar -->
                        <img src="<?php echo $u['avatar_url']; ?>" alt="Avatar" class="avatar">
                    </div>
                    <p><?php echo $u['username']; ?></p>
                </div>
                <?php endforeach ?>
            <?php else: ?>
                <p> 0 </p>
            <?php endif; ?>
        </div>
    </div>
    <div class="latest-threads"> <!-- TODO DA FARE -->
        <h3>latest threads</h3>
    </div>
</div>
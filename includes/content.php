<?php
$conn = connect_db();

$query = "SELECT * FROM categories";

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

} else {
    die("Execute failed: " . pg_last_error());
}

?>

<div class="main"> <!-- TODO: DA ORDINARE SECONDO SORT ORDER -->
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
                                <div>threads: <?php echo rand(1, 100000) ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach ?>
                <?php if ($empty): ?>
                    <div>
                        <p> <?php echo $r['description']; ?> </p>
                    </div>
                <?php endif; ?> 
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="side">
    <div class="online-users">
        <h3>online users</h3>  <!-- DA FARE CON PHP -->
        <div class="users">
            <div class="user">
                <div class="avatar-container"> <!-- Riutilizzo la classe della navbar -->
                    <img src="assets/img/default-avatar.png" alt="Avatar" class="avatar">
                </div>
                <p> Capocchia </p>
            </div>

            <div class="user admin">
                <div class="avatar-container"> <!-- Riutilizzo la classe della navbar -->
                    <img src="assets/img/default-avatar.png" alt="Avatar" class="avatar">
                </div>
                <p> CapoCapocchia </p>
            </div>

            <div class="user">
                <div class="avatar-container"> <!-- Riutilizzo la classe della navbar -->
                    <img src="assets/img/default-avatar.png" alt="Avatar" class="avatar">
                </div>
                <p> RondoDaSosa </p>
            </div>

            <div class="user moderator">
                <div class="avatar-container"> <!-- Riutilizzo la classe della navbar -->
                    <img src="assets/img/default-avatar.png" alt="Avatar" class="avatar">
                </div>
                <p> Angiolett </p>
            </div>
        </div>
    </div>
    <div class="latest-threads">

    </div>
</div>
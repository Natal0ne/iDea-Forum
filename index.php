<?php require_once 'includes/init.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iDea</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/welcome.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
    <div class="bg-gradient"></div>

    <?php require_once 'includes/sign_up.php' ?>
    <?php require_once 'includes/sign_in.php' ?>
    <?php require_once 'includes/new_thread.php' ?>
    <?php require_once 'includes/navbar.php' ?>

    <div class="welcome hidden">
        <h1 id="animatedWelcome">Welcome to iDea</h1>
    </div>
    <div class="content hidden">
        <div class="main"> <!-- DA RENDERE DINAMICO CON DB QUERY ? -->
            <div class="category-block">
                <h2>Sesso con Enrico</h2> <!-- Da fare link -->
                <div class="threads">
                    <div class="thread">
                        <div class="thread-main">
                            <a href="#" class="category-link">Capocchia dura</a> <!-- da aggiustare mettendo h4 -->
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam consequatur totam ipsa iusto nobis officia officiis eligendi soluta, excepturi voluptas mollitia, aut est ea quo incidunt atque. Ipsum, est. Facere!</p>
                        </div>
                        <div class="thread-stat">
                            <div>Reply: 70</div>
                            <div>Like: 4</div>
                        </div>
                    </div>
                    <div class="thread">
                        <div class="thread-main">
                            <a href="#" class="category-link">Capocchia dura</a> <!-- da aggiustare mettendo h4 -->
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam consequatur totam ipsa iusto nobis officia officiis eligendi soluta, excepturi voluptas mollitia, aut est ea quo incidunt atque. Ipsum, est. Facere!</p>
                        </div>
                        <div class="thread-stat">
                            <div>Reply: 70</div>
                            <div>Like: 4</div>
                        </div>
                    </div>
                    <div class="thread">
                        <div class="thread-main">
                            <a href="#" class="category-link">Capocchia dura</a> <!-- da aggiustare mettendo h4 -->
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam consequatur totam ipsa iusto nobis officia officiis eligendi soluta, excepturi voluptas mollitia, aut est ea quo incidunt atque. Ipsum, est. Facere!</p>
                        </div>
                        <div class="thread-stat">
                            <div>Reply: 70</div>
                            <div>Like: 4</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="category-block">
                <h2>Problemi di erezione</h2>
                <div class="thread">
                        <div class="thread-main">
                            <a href="#" class="category-link">Capocchia dura</a> <!-- da aggiustare mettendo h4 -->
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam consequatur totam ipsa iusto nobis officia officiis eligendi soluta, excepturi voluptas mollitia, aut est ea quo incidunt atque. Ipsum, est. Facere!</p>
                        </div>
                        <div class="thread-stat">
                            <div>Reply: 70</div>
                            <div>Like: 4</div>
                        </div>
                    </div>
                    <div class="thread">
                        <div class="thread-main">
                            <a href="#" class="category-link">Capocchia dura</a> <!-- da aggiustare mettendo h4 -->
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam consequatur totam ipsa iusto nobis officia officiis eligendi soluta, excepturi voluptas mollitia, aut est ea quo incidunt atque. Ipsum, est. Facere!</p>
                        </div>
                        <div class="thread-stat">
                            <div>Reply: 70</div>
                            <div>Like: 4</div>
                        </div>
                    </div>
            </div>
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
            <div>

            </div>
        </div>
    </div>


    <?php require_once "includes/footer.php" ?>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/welcome.js"></script>
</body>

</html>
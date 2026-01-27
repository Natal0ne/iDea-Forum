<?php
session_start();
require_once "db_connect.php"; 

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login_user'])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $login_error = "Inserisci username e password";
    } else {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute([":username" => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            
            header("Location: index.php");
            exit;
        } else {
            $login_error = "Username o password errati";
        }
    }
}
?>


<!-- Login Modal -->
<div id="loginModal" class="modal <?php echo !empty($login_error) ? 'active' : ''; ?>">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-btn" data-target="loginModal">&times;</span>
        <h2>Accedi</h2>
        
        <?php if (!empty($login_error)): ?>
            <p class="error-msg"><?php echo $login_error; ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <input type="hidden" name="login_user" value="1">
            <div class="form-group">
                <label for="login_username">Username</label>
                <!-- Changed ID to avoid conflict with register -->
                <input type="text" id="login_username" name="username" required placeholder="Inserisci il tuo username">
            </div>

            <div class="form-group">
                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="password" required placeholder="Inserisci la password">
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p class="switch-text">
            Non hai un account? 
            <a href="#" id="switchToRegister">Registrati</a>
        </p>
    </div>
</div>
<<<<<<< Updated upstream
<?php
require_once "db_connect.php"; 

$register_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register_user'])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $register_error = "Tutti i campi sono obbligatori";
    } elseif ($password !== $confirm_password) {
        $register_error = "Le password non coincidono";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => $hashedPassword
            ]);

            // Login automatico o redirect
            $_SESSION["user_id"] = $conn->lastInsertId();
            $_SESSION["username"] = $username;
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $register_error = "Username o email già esistenti";
        }
    }
} 
?>

=======
>>>>>>> Stashed changes
<!-- Register Modal -->
<div id="registerModal" class="modal <?php echo (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'register') ? 'active' : ''; ?>">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-btn" data-target="registerModal">&times;</span>
        <h2>Sign up</h2>
        
        <?php if (isset($_SESSION['register_error'])): ?>
            <p class="error-msg"><?php echo $_SESSION['register_error']; ?></p>
            <?php unset($_SESSION['register_error']); ?>
        <?php endif; ?>

        <form method="post" action="includes/process_register.php"> 
            
            <div class="form-group">
                <label for="reg_username">Username</label>
                <input type="text" id="reg_username" name="username" required placeholder="Insert username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="reg_email">Email</label>
                <input type="email" id="reg_email" name="email" required placeholder="Insert email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="reg_password">Password</label>
                <input type="password" id="reg_password" name="password" required placeholder="Insert password ">
            </div>

            <div class="form-group">
                <label for="reg_confirm_password">Confirm password</label>
                <input type="password" id="reg_confirm_password" name="confirm_password" required placeholder="Repeat password">
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>

        <p class="switch-text">
            Already on iDea? 
            <a href="#" id="switchToLogin">Sign in</a>
        </p>
    </div>
</div>
<?php 
// Clean up
if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'register') {
    unset($_SESSION['open_modal']);
}
?>

<?php
require_once "../includes/db_connect.php"; 

$register_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register_user'])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($username) || empty($email) || empty($password)) {
        $register_error = "Tutti i campi sono obbligatori";
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

<!-- Register Modal -->
<div id="registerModal" class="modal <?php echo !empty($register_error) ? 'active' : ''; ?>">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-btn" data-target="registerModal">&times;</span>
        <h2>Registrati</h2>
        
        <?php if (!empty($register_error)): ?>
            <p class="error-msg"><?php echo $register_error; ?></p>
        <?php endif; ?>

        <form method="post" action=""> 
            <input type="hidden" name="register_user" value="1">
            
            <div class="form-group">
                <label for="reg_username">Username</label>
                <input type="text" id="reg_username" name="username" required placeholder="Scegli un username">
            </div>

            <div class="form-group">
                <label for="reg_email">Email</label>
                <input type="email" id="reg_email" name="email" required placeholder="Inserisci la tua email">
            </div>

            <div class="form-group">
                <label for="reg_password">Password</label>
                <input type="password" id="reg_password" name="password" required placeholder="Scegli una password sicura">
            </div>

            <button type="submit" class="btn-submit">Registrati</button>
        </form>

        <p class="switch-text">
            Hai già un account? 
            <a href="#" id="switchToLogin">Accedi</a>
        </p>
    </div>
</div>

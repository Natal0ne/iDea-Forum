<!DOCTYPE HTML>
    <head>  </head>
<body>
    <h2>Register</h2>
    <form action = "/includes/register_process.php" id ="register" method="POST">
        <div>
            <input type = "text" id = "register_username"  name = "username" required placeholder = "Username"/> 
        </div>
        <div>
            <input type = "email" id = "register_email"  name = "email" required placeholder = "E-Mail"/> 
        </div>
            <input type = "password" id = "register_password"  name = "password" required placeholder = "Password"/> 
        <div>
            <button type = "submit" class = "btn-submit"> Register </button>
        </div>
    </form>
    <p>
        Already on iDea? <a href = "login.php"  id="switchToLogin"> Sign In </a>
    </p>
</body>
<!DOCTYPE html>
<html>
<head>
  <style>
    .RegisterForm {
        background-color: #50616d;
        text-align: center;
    }
  </style>
</head>

<body>

  <form class="RegisterForm" method="post" action="#">
    <h2>Register</h2>
    <div>
      <input type="text" name="UserID" placeholder="Insert UserID">
    </div>
    <div>
      <input type="email" name="Email" placeholder="Insert Email">
    </div>
    <div>
      <input type="password" name="password" placeholder="Insert Password">
    </div>

    <button type="submit">Registrati</button>
    <button type="reset">Annulla</button>

    <p>Already on iDea? <a href="login.php">Sign in</a></p>
  </form>
</body>
</html>

<?php
session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FaceBrigadeiro</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="auth.css" />
  </head>

  <body class="text-center">
    <form class="form-signin" action="process.php" method="post">
      <input type="hidden" name="action" value="login">
      <img class="mb-4" src="FaceBrigadeiro_03.jpg" alt="" width="110" height="110">
      <?php
foreach ($_SESSION['error_messages'] as $message) {
    echo '<p>' . $message . '<p>';
}
?>
     <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <span>Don't have an account yet? Click <a href="register.php">here</a> to register your account.</span>
    </form>
  </body>
</html>

<?php
unset($_SESSION['error_messages']); //to reset the messages
?>
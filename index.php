<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header('Location: dashboard');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate form inputs
  $errors = array();
  if (empty($username)) {
    $errors[] = 'Please enter a username.';
  }
  if (empty($password)) {
    $errors[] = 'Please enter a password.';
  }

  if (empty($errors)) {
    // Authenticate user and start session
    $conn = mysqli_connect('localhost', 'root', '', 'loggi');
    $username = mysqli_real_escape_string($conn, $username);
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      header('Location: dashboard');
      exit;
    } else {
      $errors[] = 'Invalid username or password.';
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Primary Meta Tags -->
  <title>Loggi Login — Php User Management System</title>
  <meta name="title" content="Loggi — Php User Management System">
  <meta name="description" content="Loggi is a lightweight and easy-to-use PHP-based user management system designed for small to medium-sized websites and applications. With Loggi, developers can easily integrate user registration, login, and profile management features into their projects without the need to write complex code from scratch.">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website"> 
  <meta property="og:title" content="Loggi — Php User Management System">
  <meta property="og:description" content="Loggi is a lightweight and easy-to-use PHP-based user management system designed for small to medium-sized websites and applications. With Loggi, developers can easily integrate user registration, login, and profile management features into their projects without the need to write complex code from scratch.">
  <meta property="og:image" content="./img/favicon.png">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image"> 
  <meta property="twitter:title" content="Loggi — Php User Management System">
  <meta property="twitter:description" content="Loggi is a lightweight and easy-to-use PHP-based user management system designed for small to medium-sized websites and applications. With Loggi, developers can easily integrate user registration, login, and profile management features into their projects without the need to write complex code from scratch.">
  <meta property="twitter:image" content="./img/favicon.png"> 
  <link rel="stylesheet" href="./css/style.css">
  <link rel="icon" type="image/png" sizes="16x16" href="./img/favicon.png" /> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.cdnfonts.com/css/euclid-flex" rel="stylesheet">

</head>
<body> 

  <div class="container">
    <h1>Welcome to Loggi</h1> 
    <?php if (!empty($errors)): ?>
      <ul>
        <?php foreach ($errors as $error): ?>
        <div class="alert-danger"><b><?php echo $error; ?></b></div>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?> 
    <form action="#" method="post">
      <label for="username">Username:</label>
      <input type="text" name="username" placeholder="Enter valid email address" required>
      <label for="password">Password:</label>
      <input type="password" name="password" placeholder="Enter your password" required>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="./signup">Sign up</a></p>
  </div>
</body>
</html>

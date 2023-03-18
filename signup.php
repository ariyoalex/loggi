<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header('Location: dashboard');
  exit;
}

require 'db.php';

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors[] = 'Please fill in all fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
  } elseif ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match.';
  } else {
    // Check if username or email already exist
    $existing_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($existing_user) > 0) {
      $errors[] = 'Username or email already exists.';
    } else {
      // Upload profile image
      $image = '';
      // Process image upload
      if (!empty($_FILES['image']['name'])) {
        // Get file name and extension
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Valid image extensions
        $valid_exts = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the file extension is valid
        if (in_array($file_ext, $valid_exts)) {
          // Generate unique file name
          $new_filename = uniqid() . '.' . $file_ext;

          // Move the uploaded file to the uploads directory
          if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $new_filename)) {
            // Set the image path
            $image = 'uploads/' . $new_filename;
          } else {
            $errors[] = "Unable to upload image.";
          }
        } else {
          // Invalid file type
          $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
      }

      if (empty($errors)) {
        // Add user to database
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (username, email, password, image) VALUES ('$username', '$email', '$password_hash', '$image')");
        $user_id = mysqli_insert_id($conn);

        // Set session variables
        $_SESSION['user_id'] = $user_id;

        // Redirect to dashboard
        header('Location: dashboard');
        exit;
      }
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
  <title>Loggi Signup — Php User Management System</title>
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
    <h1>5sec Sign up on Loggi</h1>
    <?php if (!empty($errors)): ?>
      <ul>
        <?php foreach ($errors as $error): ?>
  <div class="alert-danger"><b><?php echo $error; ?></b></div>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <label for="username">Username:</label>
      <input type="text" name="username" placeholder="Create a Username" required>

      <label for="email">Email:</label>
      <input type="text" name="email" placeholder="Enter valid Email Address" required>

      <label for="password">Password:</label>
      <input type="password" name="password" placeholder="Create Password" required>

      <label for="confirm_password">Confirm Password:</label>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required> 

      <label for="image">Profile Image:</label>
        <input type="file" name="image" id="image" required> 
     
      <button type="submit">Sign up</button> 
    </form><br>
    <p>Already have an account? <a href="./">Login</a></p>
  </div>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ./');
  exit;
}

require 'db.php';

if (isset($_GET['id'])) {
  $user_id = $_GET['id'];
  $user = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
  $user_data = mysqli_fetch_assoc($user);
  if (!$user_data) {
    header('Location: dashboard');
    exit;
  }
} else {
  header('Location: dashboard');
  exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];

  // Validate username
  if (empty($username)) {
    $errors[] = 'Username is required';
  } else {
    $existing_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND id!='$user_id'");
    if (mysqli_num_rows($existing_user) > 0) {
      $errors[] = 'Username already taken';
    }
  }

  // Validate email
  if (empty($email)) {
    $errors[] = 'Email is required';
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
  } else {
    $existing_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND id!='$user_id'");
    if (mysqli_num_rows($existing_email) > 0) {
      $errors[] = 'Email already registered';
    }
  }

  // Handle image upload
  if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    // Check for errors
    if ($image_error === 0) {
      $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
      $allowed_ext = array('jpg', 'jpeg', 'png');

      if (in_array($image_ext, $allowed_ext)) {
        if ($image_size <= 5000000) {
          $image_new_name = uniqid('loggi_', true) . '.' . $image_ext;
          $image_dest = '' . $image_new_name;

          if (move_uploaded_file($image_tmp_name, $image_dest)) {
            // Delete old image file if it exists
            if (!empty($user_data['image'])) {
              unlink('uploads/' . $user_data['image']);
            }

            // Update image column in database
            $query = "UPDATE users SET image='$image_new_name' WHERE id='$user_id'";
            mysqli_query($conn, $query);
          }
        } else {
          $errors[] = 'Image size too large. Maximum size is 5MB.';
        }
      } else {
        $errors[] = 'Invalid image file type. Only JPG, JPEG, and PNG files are allowed.';
      }
    } else {
      $errors[] = 'Error uploading image. Please try again.';
    }
  }

  if (empty($errors)) {
    $query = "UPDATE users SET username='$username', email='$email' WHERE id='$user_id'";

    if (mysqli_query($conn, $query)) {
      header('Location: dashboard');
      exit;
    } else {
      $errors[] = 'Failed to
 update user';
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
  <title> 
    <?php  
if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) { 
    $name = "User";
    echo $greeting . " <img src='" . $icon . "' alt='icon' width='20px'> " . $name . "!";
} else { 
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['username'];
        echo $name; 
    } else {
        // user ID is invalid, show default message
        $name = "User";
          echo $name; 
    }
}

?> Edit Users — Php User Management System</title>
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

  <!-- Loggi / Favicon -->
  <meta property="twitter:image" content="./img/favicon.png"> 

  <!-- Loggi / Styles -->
  <link rel="stylesheet" href="./css/style.css">
  <link rel="icon" type="image/png" sizes="16x16" href="./img/favicon.png" /> 

  <!-- Loggi / Js -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.cdnfonts.com/css/euclid-flex" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body> 

  <div class="container">
    <h1>Edit User</h1>
    <?php if (!empty($errors)): ?>
      <div class="errors">
        <?php foreach ($errors as $error): ?>
          <p><?php echo $error; ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>">
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>">
      </div>
      <div>
  <label for="image">Profile Image:</label>
  <?php if (!empty($user_data['image'])): ?>
    <img src="<?php echo $user_data['image']; ?>" alt="Profile Image" width="50">
  <?php endif; ?><br> 
  <input type="file" name="image" id="image"> 
</div><br> 

      <button type="submit">Save Changes</button>
    </form>
    <p><a href="dashboard">Back to Dashboard</a></p>
  </div>

</body>
</html>

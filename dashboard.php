<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ./');
  exit;
}

require 'db.php';

// Construct welcome message
$user_id = $_SESSION['user_id'];
$user = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($user);
$username = $user_data['username'];
$image = $user_data['image']; 

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

?> Dashboard — Php User Management System</title>
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
  <div class="profile-img">
  <?php if ($user_data['image']): ?>
    <img src="<?php echo $user_data['image']; ?>" alt="Profile Image" />
  <?php else: ?>
    <i class="fa fa-user fa-5x"></i>
  <?php endif; ?>
</div>
  
<div align="center">
  <?php  
date_default_timezone_set('Africa/Lagos'); 
$time = date('H:i'); 
$hour = date('H'); 

if ($hour >= 5 && $hour < 12) {
    $greeting = "Good morning";
    $icon = "./img/morning.png"; 
} elseif ($hour >= 12 && $hour < 18) {
    $greeting = "Good afternoon";
    $icon = "./img/afternoon.png";  
} else {
    $greeting = "Good evening";
    $icon = "./img/night.png"; 
}

if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // user ID is not set or empty, show default message
    $name = "User";
    echo $greeting . " <img src='" . $icon . "' alt='icon' width='20px'> " . $name . "!";
} else {
    // user ID is set, get the user's name from the database
    $user_id = $_SESSION['user_id'];
    require_once 'db.php';  
    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['username'];
        echo $greeting . " <img src='" . $icon . "' alt='icon' width='20px'> <br> " . $name . "!"; 
    } else {
        // user ID is invalid, show default message
        $name = "User";
        echo $greeting . " <img src='" . $icon . "' alt='icon' width='20px'> <br> " . $name . "!"; 
    }
}

?>
  

</div>

<?php
  // Connect to the database
  include_once 'db.php';

  // Check for success message
  $msg = '';
  if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $msg = "<div class'alert-success'><b>User deleted successfully!</b></div>";
  }
?>

<!-- Display success message -->
<?php if ($msg): ?> 
  <div class="alert-success"><b><?php echo $msg; ?></b></div>
<?php endif; ?>

<!-- Display user table -->
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Image</th>
      <th>Name</th>
      <th>Email</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $limit = 5;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $start = ($page - 1) * $limit;
      $count = $start + 1;

      $users = mysqli_query($conn, "SELECT * FROM users LIMIT $start, $limit");
      while ($user_data = mysqli_fetch_assoc($users)) {
        echo "<tr>";
        echo "<td>{$count}</td>";
        echo "<td><div style='width: 50px; height: 50px; border-radius: 50%; overflow: hidden;'><img src='{$user_data['image']}' style='width: 100%; height: 100%; object-fit: cover;'></div></td>";
        echo "<td>{$user_data['username']}</td>";
        echo "<td>{$user_data['email']}</td>";
        if ($_SESSION['user_id'] != $user_data['id']) {
          // Only display delete button if user is not currently logged in user
          echo "<td><a href=\"edit_user?id={$user_data['id']}\"><i class=\"fa fa-pencil\" style=\"color: red;\"></i></a>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0)\" onclick=\"confirmDelete('{$user_data['id']}', '{$user_data['username']}')\"><i class=\"fa fa-trash\" style=\"color: red;\"></i></a></td>"; 
        } else {
          echo "<td><span class='badge badge-danger badge-sm'>Cannot delete user</span> ";
        }
        echo "</tr>";
        $count++;
      }
    ?> 
  </tbody>
</table>


<script>
function confirmDelete(id, name) {
    var result = confirm(`Do you want to delete ${name}?`);
    if (result) {
        window.location.href = `delete_user?id=${id}`;
    }
}
</script>


    <?php
      $total_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
      $total_pages = ceil($total_users / $limit);
    ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
    <p><a href="logout">Logout</a></p>
  </div>
<a href="https://wa.me/+2348142256444" target="_blank" class="whatsapp-link">
  <i class="fa fa-whatsapp whatsapp-icon fa-2x"></i>
</a>
 
</body>
</html>

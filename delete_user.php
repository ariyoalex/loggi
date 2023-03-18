<?php
  // Connect to the database
  include_once 'db.php';

  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user
    mysqli_query($conn, "DELETE FROM users WHERE id=" . mysqli_real_escape_string($conn, $id));

    // Redirect to dashboard with success message
    header("Location: dashboard?msg=deleted");
    exit();
  }
?>

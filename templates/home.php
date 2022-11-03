<?php
    $title = "Welcome to Scriptorium";
    $description = 'Welcome to Scriptorium';
    require 'styles/head_style.php'; 
    ?>

  <div>
    <p>Hellooooo</p>
  </div>

  <?php 
    if(isset($_SESSION["id"])){ // if the user is logged in
        echo "<div><p>Welcome back, " . $_SESSION["username"] . "!";
    }
?>

<?php require 'styles/foot_style.php'; ?>
<?php
    $title = "Welcome to Scriptorium";
    $description = 'Welcome to Scriptorium';
    require 'styles/head_style.php';
    ?>

<?php 
    if(isset($_SESSION["id"])){ // if the user is logged in
        echo "<div><p>Welcome back, " . $_SESSION["username"] . "!";
    }
  ?>

<h3>List of Scripts</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
  <thead>
  <tr style="background-color:#B0B0B0">
    <th width="25%"><b>Title</b></th>
    <th width="25%"><b>Blurb</b></th>        
    <th width="25%"><b>Genre</b></th>
    <th width="25%"><b>Datetime</b></th>

  </tr>
  </thead>
<?php foreach ($list_of_scripts as $script_info): ?>
  <tr>
     <td><?php echo $script_info["title"]; ?></td>
     <td><?php echo $script_info["blurb"]; ?></td>        
     <td><?php echo $script_info["genre"]; ?></td>
     <td><?php echo $script_info["datetime"]; ?></td>

  </tr>
<?php endforeach; ?>
</table>
</div>   

<?php require 'styles/foot_style.php'; ?>
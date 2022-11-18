<?php
    $title = "Welcome to Scriptorium";
    $description = 'Welcome to Scriptorium';
    require 'styles/head_style.php';
    ?>

<?php 
    if(isset($_SESSION["id"]) && $_SESSION["id"] != -1){ // if the user is logged in
        echo "<div><p>Welcome back, " . $_SESSION["username"] . "!";
    }
  ?>
  
<section>
<h3>List of Scripts</h3>

  <?php foreach ($list_of_scripts as $script_info): ?>

    <div class="container-fluid fp-script-box">
      <div class="row">
        <div class="col-md-4 date">
            <h5><?php echo date("F j, Y, g:i a", strtotime($script_info["datetime"])); ?></h5>
        </div>
        <div class="col-md-8 genre">
          <h5><?php echo $script_info["genre"]; ?></h5>
        </div>
      </div>
      <div class="col-md-12 title">
        <h2><?php echo $script_info["title"]; ?></h2>
      </div>
      <div class="row">
        <div class="col-md-12 blurb">
          <p><?php echo $script_info["blurb"]; ?></p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 readmore">
          <?php $scriptlink = "?command=fullscript&script=".$script_info['script_id']; ?>
          <span><a href="<?php echo $scriptlink; ?>">Read more ⟶</a></span>
        </div>
        <div class="col-md-9 author">
          <?php $userlink = "?command=userpage&user=".$script_info['user_id']; ?>
          <span><a href="<?php echo $userlink; ?>"><?php echo $script_info["display_name"]; ?></a></span>
        </div>
      </div>
    </div>

    <?php endforeach; ?>
</section>

<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <span>
            <?php if ($page > 1) {
            echo '<a href="?command=home&page='.($page-1).'&sortby='.
                ($sortby).'&order='.($order).'">';
            echo ("⟵ Page " . ($page-1));
          };
          ?>
          </a>
        </span>
      </div>
      <div class="col-md-4">
        <span>Page <?php echo $page ?></span>
      </div>
      <div class="col-md-4">
        <span>
          <?php if($is_more){
            echo '<a href="?command=home&page='.($page+1).'&sortby='.
              ($sortby).'&order='.($order).'">';
            echo ("Page " . ($page+1). " ⟶");
          };
          ?>
            </a>
        </span>
      </div>

</section>

<?php require 'styles/foot_style.php'; ?>
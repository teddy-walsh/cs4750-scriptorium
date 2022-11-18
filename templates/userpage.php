<?php
    $title = "Userpage";
    $description = 'Userpage';
    require 'styles/head_style.php';
    ?>

<section>
    <div class="container-fluid fp-script-box">
    	<div class="col-md-12 up-name">
    		<h2><?php echo($info["display_name"]); ?></h2>
    	</div>
    	<div class="row">
    		<div class="col-md-12 up-bio">
    			<p><?php echo($info["bio"]); ?></p>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12 up-url">
                <?php $userurl = $info["URL"]; ?>
    			<span><a href="<?php echo $userurl; ?>"><?php echo $userurl; ?></a></span>
    		</div>
    	</div>
    </div>
</section>

<section>
<h3>All of my Scripts</h3>
  <?php foreach ($list_of_scripts as $script_info): ?>

    <div class="container-fluid fp-script-box">
      <div class="row">
        <div class="col-md-3 fp-date">
            <h5><?php echo $script_info["datetime"]; ?></h5>
        </div>
        <div class="col-md-9 genre">
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
        <div class="col-md-12 readmore">
          <?php $scriptlink = "?command=fullscript&script=".$script_info['script_id']; ?>
          <span><a href="<?php echo $scriptlink; ?>">Read more ⟶</a></span>
        </div>
      </div>
    </div>

    <?php endforeach; ?>
</section>


<?php require 'styles/foot_style.php'; ?>		
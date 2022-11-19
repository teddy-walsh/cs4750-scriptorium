<?php
    $title = "Userpage";
    $description = 'Userpage';
    require 'styles/head_style.php';
    ?>
<section>
    <div class="d-flex justify-content-center">
        <div class="script-form">
            <form action="?command=userpage" method="post">
                <div class="form-group">
                    <div class="row">

                        <div class="col">
                            <label id="name" name="name">Name</label>
                            <input type="text" class="form-control" name="name" required 
                                value="<?php echo $info["display_name"]; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label id="bio" name="bio">Bio</label>
                            <textarea class="form-control" rows="3" name="bio" style="height:200px;"
                          <?php echo($owner); ?>><?php echo($info["bio"]); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                        <?php $userurl = $info["URL"]; ?>
                        <span><a href="<?php echo $userurl; ?>"><?php echo $userurl; ?></a></span>
                        <?php if($owner == "enabled") { ?>
                            <label id="url" name="url">URL</label>
                            <textarea class="form-control" rows="3" name="url" 
                            id="url" 
                            <?php echo($owner); ?>><?php echo $info["URL"]; ?></textarea>
                        <?php } ?>
                        <!-- Add constraint that it must start with HTTP... or else it defaults to localhost -->
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($owner == "enabled") {
                                echo '<input type="submit" class="btn btn-md btn-warning" 
                                id="submit" name="btnSave" value="Update">';
                            }; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
                                  <?php
                    if (!empty($message)) {
                        echo $message;
                    }
                ?>
</section>
<section>
<h3>All of my Scripts</h3>
  <?php if (!empty($list_of_scripts)) {
    foreach ($list_of_scripts as $script_info): ?>

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

    <?php endforeach; 
    }; ?>
    
</section>


<?php require 'styles/foot_style.php'; ?>		
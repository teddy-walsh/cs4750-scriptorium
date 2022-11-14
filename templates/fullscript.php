<?php
    $title = "Scriptpage";
    $description = 'Scriptpage';
    require 'styles/head_style.php';
    ?>


<section>
    <div class="d-flex justify-content-center">
      <div class="script-form">
        <form action="?command=fullscript" method="post">
          <div class="form-group">
            <div class="row">

              <div class="col"><label id="title" name="title">Title
                  <input type="text" class="form-control" name="title" required 
                  value="<?php echo $script["title"]; ?>" 
                  <?php echo($owner); ?>>
              </div>
              <div class="col"><label id="genre" name="genre">Genre
                  <input type="text" class="form-control" name="genre" required 
                  value="<?php echo $script["genre"]; ?>" 
                  <?php echo($owner); ?>>
              </div>
              <div class="col"><label id="datetime" name="datetime">Date & Time</label><input type="text" class="form-control" name="datetime" id="datetime" 
                disabled readonly value="<?php echo $script["datetime"]; ?>"> 
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col"><label id="description" name="description">Description</label><textarea class="form-control" rows="3" name="description" id="description" 
                <?php echo($owner); ?>><?php echo $script["blurb"]; ?></textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col"><label id="script" name="script">Put your script here</label><textarea class="form-control" rows="3" name="script" style="height:200px;"
                <?php echo($owner); ?>><?php echo $script["script_body"]; ?></textarea>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <?php if ($owner == "enabled") {
                        echo '<input type="submit" class="btn btn-md btn-warning" 
                        id="submit" name="btnSave" value="Update">';
                    }; ?>
                </div>
                <div class="col-md-6">
                    <?php if ($owner == "enabled") {
                        echo '<input type="hidden" id="script-id" 
                            name="script_id" value="'. $script["script_id"]. '">';
                        echo <<< EOT
                            <input type="submit" class="btn btn-md btn-danger" 
                            id="submit" name="btnDelete" value="Delete" onclick="return confirm('Are you sure?')">
                        EOT;
                    }; ?>
                </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
                                  <?php
                    if (!empty($message)) {
                        echo $message;
                    }
                ?>
</section>

<section>
    Comments go here, I Guess
</section>
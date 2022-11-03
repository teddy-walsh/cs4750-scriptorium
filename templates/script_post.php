<?php
    $title = "Welcome to Scriptorium";
    $description = 'Welcome to Scriptorium';
    require 'styles/head_style.php'; 
    ?>

    <?php 
    if(!(isset($_SESSION["id"]))) { // if the user is not logged in, send them to the login page
        header("Location: ?command=login");
    }
    ?>

    <div class="d-flex justify-content-center">
      <div class="script-form">
        <form action="?command=script-post" method="post">
          <h1 class="test" id="colorchange">New Script</h1>
          <div class="form-group">
            <div class="row">

            <!--   Can replace the values with php if ($script_to_update != null) echo  -->
            <!-- $script_to_update["title"]  LATER -->

              <div class="col"><label id="title" name="title">Title
                  <input type="text" class="form-control" name="title" required 
                  value="Title">
              </div>
              <div class="col"><label id="genre" name="genre">Genre
                  <input type="text" class="form-control" name="genre" required 
                  value="Genre">
              </div>
              <div class="col"><label id="datetime" name="datetime">Date & Time</label><input type="text" class="form-control" name="datetime" id="datetime" disabled readonly> 
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col"><label id="description" name="description">Description</label><textarea class="form-control" rows="3" name="description" id="description">Put a brief description of your script here.
              </textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col"><label id="script" name="script">Put your script here</label><textarea class="form-control" rows="3" name="script" style="height:200px;"></textarea>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <input type="submit" class="btn btn-md" id="submit" name="myBtn1" value="Save">
            <!-- <input class="btn btn-md" id="myBtn" name="myBtn2" value="Post!"> -->
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

<?php require 'styles/foot_style.php'; ?>
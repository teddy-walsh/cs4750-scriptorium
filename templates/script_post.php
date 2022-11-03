<?php
    $title = "Welcome to Scriptorium";
    $description = 'Welcome to Scriptorium';
    require 'styles/head_style.php'; 
    ?>

    <div class="d-flex justify-content-center">
      <div class="script-form">
        <form action="" method="post">
          <h1 class="test" id="colorchange">New Script</h1>
          <div class="form-group">
            <div class="row">
              <div class="col"><label id="title" name="title">Title
                  <input type="text" class="form-control" name="name" required value="<?php if ($friend_to_update != null) echo $friend_to_update['name'] ?>" />
              </div>
              <div class="col"><label id="genre" name="genre">Genre
                  <input type="text" class="form-control" name="name" required value="<?php if ($friend_to_update != null) echo $friend_to_update['name'] ?>" />
              </div>
              <div class="col"><label id="datetime" name="datetime">Date & Time</label><input type="text" class="form-control" name="datetime" id="datetime">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col"><label id="description" name="description">Description</label><textarea class="form-control" rows="3" name="blurb" id="mon2"></textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col"><label id="story" name="story">Contents</label><textarea class="form-control" rows="3" name="contents"></textarea>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <input class="btn btn-md" id="myBtn" name="myBtn1" value="Save">
            <input class="btn btn-md" id="myBtn" name="myBtn2" value="Post!">
          </div>
        </form>
      </div>
    </div>
  </div>

<?php require 'styles/foot_style.php'; ?>
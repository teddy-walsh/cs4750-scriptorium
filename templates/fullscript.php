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

                        <div class="col">
                            <label id="title" name="title">Title</label>
                            <input type="text" class="form-control" name="title" required 
                                value="<?php echo $script["title"]; ?>" 
                                <?php echo($owner); ?>>
                        </div>
                        <div class="col">
                            <label id="genre" name="genre">Genre</label>
                            <input type="text" class="form-control" name="genre" required 
                                value="<?php echo $script["genre"]; ?>" 
                                <?php echo($owner); ?>>
                        </div>
                        <div class="col">
                            <label id="datetime" name="datetime">Date & Time</label>
                            <input type="text" class="form-control" name="datetime" id="datetime" 
                                disabled readonly value="<?php echo $script["datetime"]; ?>"> 
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label id="description" name="description">Description</label>
                            <textarea class="form-control" rows="3" name="description" 
                            id="description" 
                            <?php echo($owner); ?>><?php echo $script["blurb"]; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label id="script" name="script">Put your script here</label>
                            <textarea class="form-control" rows="3" name="script" style="height:200px;"
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
                                  <?php
                    if (!empty($message)) {
                        echo $message;
                    }
                ?>
</section>

<hr>

<section>
    
    <h1>Comments</h1>
    <?php if(isset($_SESSION['id']) && $_SESSION['id'] != -1){
        $member = "enabled";
        } else {
          $member = "hidden";
          echo("<h3>Please log in to reply</h3>");
        };
    ?>


    <?php if (!empty($parent_comments)) {
      foreach ($parent_comments as $pcomment): ?>
      <div><label id="comments" name="comments"></label></div>
          <div class="comment-block">
              <div class="row">
                  <div class="col-md-8 commenter">
                      <span>Posted by:
                        <?php $userlink = "?command=userpage&user=".$pcomment['user_id']; ?>
                        <a href="<?php echo $userlink; ?>"><?php echo $pcomment["display_name"]; ?></a>
                      </span>
                  </div>
                  <div class="col-md-4 comment-date">
                      <span><?php echo $pcomment["time"]; ?></span>
                  </div>
              </div>
              <hr>
              <div class="row blurb">
                  <span><?php echo $pcomment["comments_text"]; ?></span>
              </div>
          </div>
      </div>

      <!-- Comment Reply Box, one for each root comment -->
      <div>
          <div class="form-group row" <?php echo($member) ?>>
              <div class="col-md-1">
                  <span><h3>↳</h3></span>
              </div>
              <div class="col-md-9">
                  <form action="?command=fullscript" method="post">
                      <input type="text" class=".input-lg form-control" name="comment_text" 
                          id="description" placeholder="Reply to the above"/>
                      <input type="hidden" id="script-id" name="script_id" 
                          value="<?php echo ($script["script_id"]) ?>"/>
                      <input type="hidden" id="comment-id-parent" name="comment-id-parent" 
                          value="<?php echo ($script["comment-id-parent"]) ?>"/>
              </div>
              <div class="col-md-2">
                  <input type="submit" class="btn btn-lg btn-warning" 
                      id="comment" name="btnCommentReply" value="Post" disabled/>
                  </form>
              </div>
          </div>
      </div>
    <?php endforeach; 
    } //this bracket goes to the IF way at the top do not delete
    ?> 
<br>

    <!-- Root reply box -->
    
      <div class="form-group row" <?php echo($member) ?>>
          <div class="col-md-10">
            <form action="?command=fullscript" method="post" >
              <input type="text" class=".input-lg form-control" name="comment_text" id="description" placeholder="Comment on the script">
              <input type="hidden" id="script-id" 
                                name="script_id" value="<?php echo ($script["script_id"]) ?>">
              <input type="hidden" id="comment-id-parent" name="comment-id-parent" 
                value="<?php //echo ($script["comment-id-parent"]) ?>">
          </div>
          <div class="col-md-2">
              <input type="submit" class="btn btn-lg btn-warning" 
              id="comment" name="btnScriptReply" value="Post">
            </form>
          </div>
      </div>
</section>
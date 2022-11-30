<?php
$title = "Scriptpage";
$description = 'Scriptpage';
require 'styles/head_style.php';
?>

<section>
    <div class="container-fluid fp-script-box">
        <div class="row">
            <div class="col-md-10 ">
                <form action="?command=fullscript" method="post">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row ">
                                <label id="title" name="title">Title</label>
                                <input type="text" class="form-control" name="title" required value="<?php echo $script["title"]; ?>" <?php echo ($owner); ?>>
                            </div>
                            <div class="row bordertables">
                                <label id="genre" name="genre">Genre</label>
                                <input type="text" class="form-control" name="genre" required value="<?php echo $script["genre"]; ?>" <?php echo ($owner); ?>>
                            </div>
                            <div class="row bordertables">
                                <label id="description" name="description">Description</label>
                                <textarea class="form-control" rows="3" name="description" id="description" <?php echo ($owner); ?>><?php echo $script["blurb"]; ?></textarea>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="row bordertables">
                                <label id="datetime" name="datetime">Date & Time</label>
                                <input type="text" class="form-control" name="datetime" id="datetime" disabled readonly value="<?php echo date("F j, Y, g:i a", strtotime($script["datetime"])); ?>">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row bordertables">
                        <label id="script" name="script"><?php if ($owner == "enabled") {
                                                                    echo "Edit Your Script";
                                                                } else {
                                                                    echo "Script Body";
                                                                }

                                                                ?></label>
                        <textarea class="form-control" rows="3" name="script" style="height:200px;" <?php echo ($owner); ?>><?php echo $script["script_body"]; ?></textarea>
                    </div>
                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ($owner == "enabled") {
                                echo '<input type="submit" class="btn btn-lg btn-warning" 
                                id="submit" name="btnSave" value="Update">';
                            }; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($owner == "enabled") {
                                echo '<input type="hidden" id="script_id" 
                                    name="script_id" value="' . $script["script_id"] . '">';
                                echo <<< EOT
                                    <input type="submit" class="btn btn-lg btn-danger" 
                                    id="submit" name="btnDelete" value="Delete" onclick="return confirm('Are you sure?')">
                                EOT;
                            }; ?>
                        </div>
                    </div>  
                </form>
            </div>

            <div class="col-md-2 bordertables">

            <label id="" name="votes"><?php echo "Score: " . $script_score;  ?> </label>
                         <!-- BEGIN VOTE FORM -->
                            <span>
                            <form action="?command=fullscript" method="post">
                            <input type="hidden" id="script_id" name="script_id" value=<?php echo $script["script_id"]?>>                                
                            <input type="hidden" id="direction" name="direction" value="1">
                                <button type="btnScriptVote" name="btnScriptVote" value="btnScriptVote" >
                                    ↑
                                </button>

                            </form>
                            <form action="?command=fullscript" method="post">
                                <input type="hidden" id="script_id" name="script_id" value="<?php echo ($script["script_id"]) ?>">
                                <input type="hidden" id="direction" name="direction" value="0">
                                <button type="btnScriptVote" name="btnScriptVote" value="btnScriptVote" >
                                    •
                                </button>

                            </form>
                            <form action="?command=fullscript" method="post">
                                <input type="hidden" id="script_id" name="script_id" value="<?php echo ($script["script_id"]) ?>">
                                <input type="hidden" id="direction" name="direction" value="-1">
                                <button type="btnScriptVote" name="btnScriptVote" value="btnScriptVote" >
                                    ↓
                                </button>  
                            </form>
                            </span>
            </div>

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
    <?php if (isset($_SESSION['id']) && $_SESSION['id'] != -1) {
        $member = "enabled";
    } else {
        $member = "hidden";
        echo ("<h3>Please log in to reply</h3>");
    };
    ?>


    <?php if (!empty($root_comments)) {
        foreach ($root_comments as $rootcomment) : ?>
            <div><label id="comments" name="comments"></label></div>
            <div class="comment-block">
                <div class="row">
                    <div class="col-md-7 commenter">
                        <span>Posted by:
                            <?php $userlink = "?command=userpage&user=" . $rootcomment['user_id']; ?>
                            <a href="<?php echo $userlink; ?>"><?php echo $rootcomment["display_name"]; ?></a>
                        </span>
                    </div>
                    <div class="col-md-5 comment-date">
                        <span><?php
                                echo date("F j, Y, g:i a", strtotime($rootcomment["time"])); ?>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row blurb">
                    <div class="col-md-10 commenter">
                        <span><?php echo $rootcomment["comments_text"]; ?></span>
                    </div>
                    <div class="col-md-2 score">
                        <span><?php echo "Score: " . $comment_id_to_score[$rootcomment["comment_id"]]; ?></span>
                        <!-- BEGIN VOTE FORM -->
                        <span>
                            <form action="?command=fullscript" method="post">
                            <input type="hidden" id="script_id" name="script_id" value=<?php echo $script["script_id"]?>>                                
                            <input type="hidden" id="direction" name="direction" value="1">
                            <input type="hidden" id="comment-id" name="comment_id" value=<?php echo $rootcomment['comment_id'];?>>

                            <button type="btnCommentVote" name="btnCommentVote" value="btnCommentVote" >
                                    ↑
                                </button>

                            </form>
                            <form action="?command=fullscript" method="post">
                            <input type="hidden" id="script_id" name="script_id" value=<?php echo $script["script_id"]?>>                                
                            <input type="hidden" id="direction" name="direction" value="0">
                            <input type="hidden" id="comment-id" name="comment_id" value=<?php echo $rootcomment['comment_id'];?>>
                            <button type="btnCommentVote" name="btnCommentVote" value="btnCommentVote" >
                                    •
                                </button>

                            </form>
                            <form action="?command=fullscript" method="post">
                            <input type="hidden" id="script_id" name="script_id" value=<?php echo $script["script_id"]?>>                                
                            <input type="hidden" id="direction" name="direction" value="-1">
                            <input type="hidden" id="comment-id" name="comment_id" value=<?php echo $rootcomment['comment_id'];?>>
                            <button type="btnCommentVote" name="btnCommentVote" value="btnCommentVote" >
                                    ↓
                                </button>  
                            </form>
                            </span>
                        <!-- END VOTE FORM -->
                    </div>
                </div>
            </div>
            </div>

            <!-- Child comments -->
            <?php
            if (!empty($child_comments)) {
                foreach ($child_comments as $childcomment) :
                    if ($rootcomment["comment_id"] != $childcomment["comment_parent"]) {
                        continue;
                    };
            ?>
                    <div class="row">
                        <div class="col-md-1">
                            <h3>↳</h3>
                        </div>
                        <div class="col-md-11">

                            <div class="child-block">
                                <div class="row">
                                    <div class="col-md-7 commenter">
                                        <span>Posted by:
                                            <?php $userlink = "?command=userpage&user=" . $childcomment['user_id']; ?>
                                            <a href="<?php echo $userlink; ?>"><?php echo $childcomment["display_name"]; ?></a>
                                        </span>
                                    </div>
                                    <div class="col-md-5 comment-date">
                                        <span><?php
                                                echo date("F j, Y, g:i a", strtotime($childcomment["time"])); ?></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row blurb">
                                    <div class="col-md-10 commenter">
                                        <span><?php echo $childcomment["comments_text"]; ?></span>
                                    </div>
                                    <div class="col-md-2 score">
                                        <span><?php echo "Score: " . $comment_id_to_score[$childcomment["comment_id"]];?></span>
                                                              <!-- BEGIN VOTE FORM -->
                        <span>
                            <form action="?command=fullscript" method="post">
                            <input type="hidden" id="script_id" name="script_id" value=<?php echo $script["script_id"]?>>                                
                            <input type="hidden" id="direction" name="direction" value="1">
                            <input type="hidden" id="comment-id" name="comment_id" value=<?php echo $childcomment['comment_id'];?>>

                            <button type="btnCommentVote" name="btnCommentVote" value="btnCommentVote" >
                                    ↑
                                </button>

                            </form>
                            <form action="?command=fullscript" method="post">
                            <input type="hidden" id="script_id" name="script_id" value=<?php echo $script["script_id"]?>>                                
                            <input type="hidden" id="direction" name="direction" value="0">
                            <input type="hidden" id="comment-id" name="comment_id" value=<?php echo $childcomment['comment_id'];?>>
                            <button type="btnCommentVote" name="btnCommentVote" value="btnCommentVote" >
                                    •
                                </button>

                            </form>
                            <form action="?command=fullscript" method="post">
                            <input type="hidden" id="script_id" name="script_id" value=<?php echo $script["script_id"]?>>                                
                            <input type="hidden" id="direction" name="direction" value="-1">
                            <input type="hidden" id="comment-id" name="comment_id" value=<?php echo $childcomment['comment_id'];?>>
                            <button type="btnCommentVote" name="btnCommentVote" value="btnCommentVote" >
                                    ↓
                                </button>  
                            </form>
                            </span>
                        <!-- END VOTE FORM -->
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
            <?php endforeach;
            } //this bracket goes to the IF way at the top do not delete
            ?>


            <!-- Comment Reply Box, one for each root comment -->
            <div>
                <div class="form-group row" <?php echo ($member) ?>>
                    <div class="col-md-1">
                        <span></span>
                    </div>
                    <div class="col-md-9">
                        <form action="?command=fullscript" method="post">
                            <input type="text" class=".input-lg form-control" name="comment_text" id="description" placeholder="Reply to the above" />
                            <input type="hidden" id="script_id" name="script_id" value="<?php echo ($script["script_id"]) ?>" />
                            <input type="hidden" id="parent_comment_id" name="parent_comment_id" value="<?php echo ($rootcomment["comment_id"]) ?>" />
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-lg btn-warning" id="comment" name="btnCommentReply" value="Post" />
                        </form>
                    </div>
                </div>
            </div>
    <?php endforeach;
    } //this bracket goes to the IF way at the top do not delete
    ?>
    <br>

    <!-- Root reply box -->

    <div class="form-group row" <?php echo ($member) ?>>
        <div class="col-md-10">
            <form action="?command=fullscript" method="post">
                <input type="text" class=".input-lg form-control" name="comment_text" id="description" placeholder="Comment on the script">
                <input type="hidden" id="script_id" name="script_id" value="<?php echo ($script["script_id"]) ?>">
        </div>
        <div class="col-md-2">
            <input type="submit" class="btn btn-lg btn-warning" id="comment" name="btnScriptReply" value="Post">
            </form>
        </div>
    </div>
</section>
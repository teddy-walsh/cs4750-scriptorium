<?php
    $title = "Scriptpage";
    $description = 'Scriptpage';
    require 'styles/head_style.php';
    ?>

<section>
    <div class="container-fluid fp-script-box">
        <div class="row">
            <div class="col-md-3 fp-date">
                <h5><?php echo $script["datetime"]; ?></h5>
            </div>
            <div class="col-md-9 fp-genre">
                <h5><?php echo $script["genre"]; ?></h5>
            </div>
        </div>
        <div class="col-md-12 fp-title">
            <h2><?php echo $script["title"]; ?></h2>
        </div>
        <div class="row">
            <div class="col-md-12 fp-blurb">
                <p><?php echo $script["blurb"]; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 fp-blurb">
                <p><?php echo $script["script_body"]; ?></p>
            </div>
        </div>
    </div>
</section>
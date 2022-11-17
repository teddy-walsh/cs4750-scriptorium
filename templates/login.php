<?php
    $title = "Login to Scriptorium";
    $description = 'Scriptorium Login Page';
    require 'styles/head_style.php'; 
    
    // on login screen, redirect to home if already logged in
    if(isset($_SESSION['id']) && $_SESSION['id'] != -1){
        header('location:?command=home');
    }
    ?>

        <div class="container main">
            <div class="row login-banner">
                <h1>Please login to your account below.</h1>
                <hr>
                <br>
                <p></p>        
            </div>

            <div class="row justify-content-center">
                <div class="col-sm-4">

                <form action="?command=login" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" placeholder="Username" id="username" name="username"/>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password"/>
                        <div id="pwhelplen" class="form-text"></div>
                        <div id="pwhelpuc" class="form-text"></div>
                        <div id="pwhelplc" class="form-text"></div>
                        <div id="pwhelpnum" class="form-text"></div>
                    </div>
                    <div class="text-center">                
                    <button type="submit" class="btn btn-primary" id="submit">Login</button>
                    <a href="?command=logout" class="btn btn-danger cancel">Cancel</a>
                    </div>
                </form>
                <?php
                    if (!empty($message)) {
                        echo $message;
                    }
                ?>
                </div>
            </div>
        </div>



<?php require 'styles/foot_style.php'; ?>

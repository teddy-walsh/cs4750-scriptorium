<?php
    $title = "Scriptorium - User Creation";
    $description = 'Scriptorium User Creation';
    require 'styles/head_style.php'; 
    
    // on login screen, redirect to home if already logged in
    if(isset($_SESSION["id"]) && $_SESSION["id"] != -1){
        header('location:?command=home');
    }
    ?>

        <div class="container main">
            <div class="row login-banner">
                <h1>Create your account below.</h1>
                <hr>
                <br>
                <p></p>        
            </div>

            <div class="row justify-content-center">
                <div class="col-sm-4">

                <form action="?command=account-create" method="post" onsubmit="return validate();">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" placeholder="First" id="fname" name="fname" required/>
                        <input type="text" class="form-control" placeholder="Middle (optional)" id="mname" name="mname"/>
                        <input type="text" class="form-control" placeholder="Last" id="lname" name="lname" required/>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="email@host.com" id="email" name="email"/>
                    </div>
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
                    <button type="submit" class="btn btn-primary" id="submit">Create Account</button>
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

<script>
    function validate() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;

        if (email.length > 0 && password.length > 8) {
            return true;
        }

        alert("Please enter a long enough email and password.")
        return false;
    }

    // Password validate function 
    function passwordValidate(len=8) {
        var password = document.getElementById("password");
        var submit = document.getElementById("submit");
        var pwhelp = document.getElementById("pwhelp");
        var passval = password.value;
        var uppercase = /[A-Z]/.test(passval);
        var lowercase = /[a-z]/.test(passval);
        var number = /[0-9]/.test(passval);
        console.log(uppercase);
        // check pass length
        if (passval.length < len) {
            password.classList.add("is-invalid");
            submit.disabled = true;
            pwhelplen.textContent = "Please enter a "+len +"-character password.";
        } else {
            password.classList.remove("is-invalid");
            submit.disabled = false;
            pwhelplen.textContent = "";
        }
        // check pass uppercase
        if (!uppercase) {
            password.classList.add("is-invalid");
            submit.disabled = true;
            pwhelpuc.textContent = "Passwords must contain at least 1 uppercase letter (A-Z).";
        } else {
            password.classList.remove("is-invalid");
            submit.disabled = false;
            pwhelpuc.textContent = "";
        }
        // check pass lowercase
        if (!lowercase) {
            password.classList.add("is-invalid");
            submit.disabled = true;
            pwhelplc.textContent = "Passwords must contain at least 1 lowercase letter (a-z).";
        } else {
            password.classList.remove("is-invalid");
            submit.disabled = false;
            pwhelplc.textContent = "";
        }
        // check pass number
        if (!number) {
            password.classList.add("is-invalid");
            submit.disabled = true;
            pwhelpnum.textContent = "Passwords must contain at least 1 number (0-9).";
        } else {
            password.classList.remove("is-invalid");
            submit.disabled = false;
            pwhelpnum.textContent = "";
        }
    }

    document.getElementById("password").addEventListener("keyup", function() {
        passwordValidate(8);
    });
</script>

<?php require 'styles/foot_style.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0">
        <meta name="authors" content="Maxim Gorodchanin, 
            Neha Krishnakumar, Teddy Walsh, Claire Yoon">
        <meta name="description" content="<?= $description ?>" />  
        <title><?= $title?></title>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

        <script type="text/javascript" src="Scripts/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="Scripts/bootstrap.min.js"></script>

        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Wendy One' rel='stylesheet'>

        <!-- LESS -->
<!--         <link rel="stylesheet/less" type="text/css" href="styles/styles.less" />
        <script src="https://cdn.jsdelivr.net/npm/less" ></script> -->
        <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

        <!-- CSS Stylesheet -->
        <link rel="stylesheet" type="text/css" href="styles/style.css">

    </head>

<body>

    <!-- Claire -->
  <div class="row">

    <nav class="navbar navbar-expand-md navbar-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="?command=home">Scriptorium</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavbar">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="?command=script-post">Post a New Script</a>
            </li>
            <li class="nav-item">
              <?php 
                    if(!isset($_SESSION['id'])){ // if the user is not logged in, show the two buttons
                        echo <<< EOT
                            <a href="?command=login" class="btn btn-outline-light me-2" role="button">Login</a>
                            <a href="?command=account-create" class="btn btn-warning" role="button">Sign-up</a>
                        EOT;
                    } else {
                        echo <<< EOT
                            <a href="?command=logout" class="btn btn-danger" role="button">Logout</a>
                        EOT;
                    }
                ?>

            </li>
          </ul>
        </div>
      </div>
    </nav>
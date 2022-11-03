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

        <!-- LESS -->
<!--         <link rel="stylesheet/less" type="text/css" href="styles/styles.less" />
        <script src="https://cdn.jsdelivr.net/npm/less" ></script> -->
        <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

        <!-- CSS Stylesheet -->
        <link rel="stylesheet" type="text/css" href="styles/style.css">
    </head>

<body>

    <!-- <header class="p-3 text-bg-dark"> -->
        <header class="p-3 header-color">
        <div class="container">
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
              <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
              <li><a href="#" class="nav-link px-2 text-white">Home</a></li>
              <li><a href="#" class="nav-link px-2 text-white">Features</a></li>
              <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
              <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown" aria-expanded="false" >Scripts</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>

            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
              <input type="search" class="form-control form-control-dark text-bg-dark" placeholder="Search..." aria-label="Search">
            </form>

            <div class="text-end">
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


            </div>
          </div>
        </div>
      </header>
        
      </div>
    </div>
  </header>
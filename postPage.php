<?php
require("connect-db.php");
require("script-db.php");

$list_of_friends = getAllFriends();
$friend_to_update = null;
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['btnAction']) && $_POST['btnAction'] == 'Add') {
    addFriend($_POST['name'], $_POST['major'], $_POST['year']);
    $list_of_friends = getAllFriends();
  } else if (!empty($_POST['btnAction']) && $_POST['btnAction'] == 'Update') {
    $friend_to_update = getFriendByName($_POST['friend_to_update']);
  } else if (!empty($_POST['btnAction']) && $_POST['btnAction'] == 'Delete') {
    deleteFriend($_POST['friend_to_delete']);
    $list_of_friends = getAllFriends();
  }
  if (!empty($_POST['btnAction']) && $_POST['btnAction'] == 'Confirm update') {
    updateFriend($_POST['name'], $_POST['major'], $_POST['year']);
    $list_of_friends = getAllFriends();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="your name">
  <meta name="description" content="include some description about your page">
  <title>Scriptorium</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link href='https://fonts.googleapis.com/css?family=Wendy One' rel='stylesheet'>
  <style>
    .navbar {
      font-family: 'Wendy One';
      font-size: 18px;
      padding-right: 1em;
      text-align: right;
      background-color: rgba(255, 255, 255, 0.5);
      color: #636363;
    }

    .navbar-brand {
      font-family: 'Wendy One';
      font-size: 28px;
      color: #464646;
    }

    .navbar-toggler {
      color: #636363;

    }

    .nav-link {
      text-align: right;
      color: #636363;
    }

    footer {
      padding: 15px;
      background-color: rgba(255, 255, 255, 0.5);
      margin-top: auto;
      color: #636363;
      position: absolute;
      bottom: 0;
      width: 100%;
    }

    @media only screen and (max-width: 768px) {

      /* For mobile phones and tablets: */
      [class*="col-"] {
        width: 100%;
      }
    }

    @media only screen and (min-width: 768px) {
      .col-1 {
        width: 8.33%;
      }

      .col-2 {
        width: 16.66%;
      }

      .col-3 {
        width: 25%;
      }

      .col-4 {
        width: 33.33%;
      }

      .col-5 {
        width: 41.66%;
      }

      .col-6 {
        width: 50%;
      }

      .col-7 {
        width: 58.33%;
      }

      .col-8 {
        width: 66.66%;
      }

      .col-9 {
        width: 75%;
      }

      .col-10 {
        width: 83.33%;
      }

      .col-11 {
        width: 91.66%;
      }

      .col-12 {
        width: 100%;
      }
    }

    body {
      font-family: 'Wendy One';
      font-size: 22px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      max-width: 100%;
      max-height: 100%;
      background: url("https://images.unsplash.com/photo-1559239115-ce3eb7cb87ea?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1088&q=80") no-repeat center;
      background-size: cover;
    }

    .form-control {
      font-family: 'Wendy One';
      height: 40px;
      box-shadow: none;
      color: #969fa4;
    }

    .form-control:focus {
      border-color: #5cb85c;
    }

    .form-control,
    .btn {
      background-color: rgba(178, 223, 217, 0.6);
      border-radius: 3px;
      border-radius: 15px;
    }

    .script-form {
      width: 800px;
      float: right;
      margin-top: 80px;
      padding: 30px 0;
      font-size: 15px;
      position: relative;
      text-align: center;
    }

    .script-form h1 {
      font-family: 'Wendy One';
      color: white;
      margin: 0 0 15px;
      position: relative;
      text-align: center;
      background: #65c5c0;
      border-radius: 12px;
    }

    .script-form h1:hover {
      color: white;
      background: #464646;;
      border-radius: 12px;
    }

    .script-form form {
      font-family: 'Wendy One';
      border-radius: 3px;
      margin-bottom: 15px;
      background-color: rgba(255, 255, 255, 0.5);
      padding: 30px;
      border-radius: 25px;
    }

    .script-form .form-group {
      margin-bottom: 20px;
    }

    .script-form .btn {
      margin-top: 20px;
      font-size: 20px;
      font-weight: bold;
      margin-left: 20px;
      margin-right: 20px;
      width: 160px;
      outline: none !important;
      background: #65c5c0;
      color: white;
      border-radius: 18px;
    }

    .script-form .row div:first-child {
      padding-right: 10px;
    }

    .script-form .row div:last-child {
      padding-left: 10px;
    }

    .text-center {
      color: #636363;
    }

    .contents {
      position: relative;
      top: 30px;
      font-size: 60px !important;
      font-family: 'Lato', sans-serif !important;
      font-weight: 400;
      color: white;
      width: 400px;
      line-height: 100px !important;
      opacity: 0.2;
    }

    label {
      font-size: 20px;
    }
  </style>
</head>

<body>
  <div class="row">

    <nav class="navbar navbar-expand-md navbar-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="postPage.php">Scriptorium</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="postPage.php">Post a New Script</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Sign In</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
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

    <footer class="footer-container">
      <div class="container">
        <div class="row">
        </div>
      </div>
      <div class="text-center">&copy; CNTM</div>
    </footer>
</body>
</html>
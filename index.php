<?php
session_start();
$action = "";
if (isset($_GET['action'])) {
    $action = htmlspecialchars($_GET['action']);
    if ($action == "logout" || $action == "login") {
        $tmp = $_SESSION['searchQuery'];
        session_unset();
        $_SESSION['searchQuery'] = $tmp;
    }
}
//connect to the data base
//require_once "passwords.php";
$user = "201CTeam6";
$password = "NoPassword";
$mysqli = mysqli_connect("35.201.215.85", $user, $password, "appReview");
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die;
}
//log in
function pwdV($mysqli)
{
    if (isset($_POST['user']) && isset($_POST['pass'])) {
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['pass'] = $_POST['pass'];
    }
    if (isset($_SESSION['user']) && isset($_SESSION['pass'])) {
        $username = $_SESSION['user'];
        $pwd = $_SESSION['pass'];
    }
    if (isset($username) && isset($pwd)) {
        $res = mysqli_query($mysqli, "SELECT user, password from users order by user");
        if (!$res) {
            echo "error on sql - $mysqli->error";
        } else {
            $f = true;
            while ($row = mysqli_fetch_assoc($res)) {
                if ($username === $row['user']) {
                    if (password_verify($pwd, $row['password'])) {
                        return 0;
                    } else {
                        return 1;
                    }
                    $f = false;
                }
            }
            if ($f) {
                return 2;
            }
        }
    }
    return 3;
}
$pV = pwdV($mysqli);
//signup
function signV($mysqli)
{
    if (isset($_POST['usr']) && isset($_POST['pwd1']) && isset($_POST['pwd2'])) {
        $username = $_POST['usr'];
        $pwd1 = $_POST['pwd1'];
        $pwd2 = $_POST['pwd2'];
    }
    if (isset($pwd2) && isset($pwd1) && isset($username)) {
        if ($pwd1 != $pwd2) {
            return 1;
        }
        $res = mysqli_query($mysqli, "SELECT user from users order by user");
        if (!$res) {
            echo "error on sql - $mysqli->error";
        } else {
            $f = true;
            while ($row = mysqli_fetch_assoc($res)) {
                if ($username === $row['user']) {
                    $f = false;
                }
            }
            if (!$f) {
                return 2;
            }
        }
        $hash = password_hash($pwd1, PASSWORD_DEFAULT);
        $res2 = mysqli_query($mysqli, "INSERT INTO users (user, password, division) VALUE ('$username', '$hash', 3);");
        $_SESSION['usr'] = $username;
        return 0;
    }
    return 3;
}
$sV = signV($mysqli);
//search
if (isset($_POST['searchQuery'])) {
    $_SESSION['searchQuery'] = htmlspecialchars($_POST['searchQuery']);
}
if (!isset($_SESSION['searchQuery'])) {
    $_SESSION['searchQuery'] = "";
}
$searchQuery = $_SESSION['searchQuery'];
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="./images/logo.ico">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/mainCSS.css">

    <title>appReview</title>
</head>

<body>

    <!-- anything having to do with the nav bar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <a class="navbar-brand" href="#">appReview</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav">

                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.youtube.com/">Youtube</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=otherPage">Go elsewhere</a>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav mr-auto">
            <?php
            if ($pV == 0) {
                ?>
                <li class="nav-item"><a class="nav-link" href="#">Current User: <?php echo $_SESSION['user']; ?></a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=logout"> Logout</a></li>
            <?php
        } else {
            ?>
                <li class="nav-item"><a class="nav-link" href="index.php?action=signup"> Sign up</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=login"> Login</a></li>
            <?php
        }
        ?>

        </ul>

    </nav>


    <!-- content below navbar -->
    <?php
    if ($action == "login" && $pV != 0) {
        // login form 
        ?>
        <div class="container">

            <h1>Please log in</h1>
            <form method='post' action="<?php print $_SERVER['PHP_SELF']; ?>?action=login">
                <div class="loginForm">
                    <div>
                        <label for="user">Username: </label>
                        <input class="form-control" type="text" name="user">
                    </div>
                    <div>
                        <label for="pass">Password: </label>
                        <input class="form-control" type="password" name="pass">
                    </div>
                    <p class="loginAlert">
                        <?php
                        if ($pV == 1) {
                            echo 'The Password is invalid!';
                        } else if ($pV == 2) {
                            echo 'The Username are invalid!';
                        }
                        ?>
                    </p>
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Log in</button>
                </div>
            </form>

        </div>
    <?php
} else if ($action == "signup" && $sV == 0) {
    // successful sign up
    ?>
    <div class="container">

        <h2>Welcome, User: <?php echo $_SESSION['usr']; ?></h2>
        <a href="index.php">Click here to continue.</a>
        
    </div>
<?php
} else if ($action == "signup" && $sV != 0) {
    // signup form 
    ?>
        <div class="container">

            <h1>Please sign up</h1>
            <form method='post' action="<?php print $_SERVER['PHP_SELF']; ?>?action=signup">
                <div class="loginForm">
                    <div>
                        <label for="usr">Username: </label>
                        <input class="form-control" type="text" name="usr">
                    </div>
                    <div>
                        <label for="pwd1">Password: </label>
                        <input class="form-control" type="password" name="pwd1">
                    </div>
                    <div>
                        <label for="pwd2">Verify Password: </label>
                        <input class="form-control" type="password" name="pwd2">
                    </div>
                    <p class="loginAlert">
                        <?php
                        if ($sV == 1) {
                            echo 'The Verify Password is not same as the Password!';
                        } else if ($sV == 2) {
                            echo 'The Username is already exist!';
                        }
                        ?>
                    </p>
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Sign up</button>
                </div>
            </form>

        </div>
    <?php
} else if ($action == "login" && $pV == 0) {
    // successful login message
    ?>
    <div class="container">

        <h2>Welcome back, User: <?php echo $_SESSION['user']; ?></h2>
        <a href="index.php">Click here to continue.</a>
        
    </div>
<?php
} else if ($action == "otherPage") {
    // probably won't get used for now
    ?>
        <div class="words">
            <h1>Welcome to another magical page</h1>
        </div>
    <?php
} else if ($searchQuery != "") {
    // Handle normal searches/displaying apps
    ?>
        <div class="search">
            <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post" class="form-inline">
                <input class="form-control mr-sm-2" type="text" placeholder="You searched <?php echo $searchQuery ?>" aria-label="Search" name="searchQuery">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
        <!-- app displaying results - - - - - - - - - - - - - - - - -->
        <div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed float-md-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          <!-- stuff that shows up before dropping down: --> 
          <img src="Star-icon.png" height="50" width="50" alt="">STAR analytics -- 4.7 -- $4.99
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        Description/prettier information
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed float-md-left" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <!-- stuff that shows up before dropping down: -->
          <img src="Star-icon.png" height="50" width="50" alt="">STAR stocks -- 4.3 -- $9.99
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        Description/prettier information 
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed float-md-left" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <!-- stuff that shows up before dropping down: -->
          <img src="Star-icon.png" height="50" width="50" alt="">STAR suites -- 3.9 -- $24.99 
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
        Description/prettier information 
      </div>
    </div>
  </div>
</div>
    <!-- end app displaying results -->

    <?php
} else {
    ?>
        <!-- form/cgi/classes interaction test w/ search bar -->
        <div class="mainSearch">
            <img src="./images/logo.png">
            <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post">
                <input class="form-control mr-sm-2" type="text" placeholder="What are you looking for?" aria-label="Search" name="searchQuery">
                <br>
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    <?php
}
?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>

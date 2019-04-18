<?php
session_start();

$action = "";
if (isset($_GET['action'])) {
    $action = htmlspecialchars($_GET['action']);
    if ($action == "logout") {
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
            <ul class="navbar-nav mr-auto">

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
        <ul class="nav navbar-nav navbar-right">


        </ul>
        <?php
        if ($pV == 0) {
            ?>
            <li><a href="#"><span class="glyphicon glyphicon-user"></span><?php echo $_SESSION['user']; ?></a></li>
            <li><a href="index.php?action=logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
        <?php
    } else {
        ?>
            <li><a href="index.php?action=login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <?php
    }
    ?>
    </nav>


    <!-- content below navbar -->
    <?php

    if ($action == "login" && $pV != 0) {
        ?>
        <div class="container">

            <h1>Please sign in</h1>
            <div class="form-group">
                <form method='post' action="<?php print $_SERVER['PHP_SELF']; ?>?action=login">
                    User: <input type="text" name="user">
                    Password: <input type="password" name="pass">
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Log in</button>
                </form>
                <p>
                    <?php

                    if ($pV == 1) {
                        echo 'The Password is invalid!';
                    } else if ($pV == 2) {
                        echo 'The Username are invalid!';
                    }
                    ?>
                </p>
            </div>
        </div>
    <?php
} else  if ($searchQuery != "") {
    ?>
        <div class="search">
            <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post" class="form-inline">
                <input class="form-control mr-sm-2" type="text" placeholder="You searched <?php echo $searchQuery ?>" aria-label="Search" name="searchQuery">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
        <?php
} else if ($action == "otherPage") {
    ?>
    <div class="words">
        <h1>Welcome to another magical page</h1>
     </div>
    <?php
} else {
    ?>
        <!-- a comment line -->


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
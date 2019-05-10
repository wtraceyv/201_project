<?php
session_start();

$action = "";
$appApproval = ""; 
$appInteractionId = -1; 

if (isset($_GET['action'])) {
    $action = htmlspecialchars($_GET['action']);
    if ($action == "logout" || $action == "login") {
        $tmp = $_SESSION['searchQuery'];
        session_unset();
        $_SESSION['searchQuery'] = $tmp;
    }
    if ($action == "default") {
        $_SESSION['searchQuery'] = "";
    }
}

if (isset($_GET['appApproval'])) {
    $appApproval = htmlspecialchars($_GET['appApproval']);
}

if (isset($_GET['appInteractionId'])) {
    $appInteractionId = $_GET['appInteractionId'];
}

//connect to the database
// mysql --host=35.201.215.85 --database="appReview" --user=201CTeam6 --password=NoPassword
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
        $res = mysqli_query($mysqli, "SELECT user, password, division from users order by user");
        if (!$res) {
            echo "error on sql - $mysqli->error";
        } else {
            $f = true;
            while ($row = mysqli_fetch_assoc($res)) {
                if ($username === $row['user']) {
                    if (password_verify($pwd, $row['password'])) {
                        if ($row['division']==1) {
                            return 5;
                        } else {
                            return 0;
                        }
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


// Sign up
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


// submitting new apps
function addApp($mysqli)
{
    if (isset($_POST['appName']) && isset($_POST['category']) && isset($_POST['appDescription']) && isset($_POST['price'])) {
        $appName = $_POST['appName'];
        $category = $_POST['category'];
        $appDescription = $_POST['appDescription'];
        $price = $_POST['price'];
        // put some sort of smart checking here
        // skipping for now
        $res = mysqli_query($mysqli, "INSERT INTO apps (appName, category, appDescription, price, approved) VALUE ('$appName', '$category', '$appDescription', '$price', false);");
        if (!$res) {
            echo "error on sql - $mysqli->error";
        }
        $_SESSION['appName'] = $appName;
        return 0;
    } else {
        return 1; // something is incomplete, don't add anything 
    }
}
$addApp = addApp($mysqli);



//           ADMIN PAGE ABILITIES HERE     
// return query for current UNAPPROVED apps for admin page 
function getAppsToApprove($mysqli) {
    $res = mysqli_query($mysqli, "SELECT * from apps WHERE approved=0;"); 
    return $res; 
}
$appsToApprove = getAppsToApprove($mysqli); 


if ($appApproval == "delete") { 
    $res = mysqli_query($mysqli, "DELETE FROM apps WHERE appId='$appInteractionId';"); 
    $appApproval = ""; 
} else if ($appApproval == "approve") {
    $res = mysqli_query($mysqli, "UPDATE apps SET approved=1 WHERE appId='$appInteractionId'");
    $appApproval = ""; 
}



//                                     SEARCHING FUNCTIONS
if (isset($_POST['searchQuery'])) {
    $_SESSION['searchQuery'] = htmlspecialchars($_POST['searchQuery']);
}
if (!isset($_SESSION['searchQuery'])) {
    $_SESSION['searchQuery'] = "";
}
$searchQuery = $_SESSION['searchQuery'];
function search($mysqli, $searchQuery, $sortBy)
{
    if (strcasecmp($searchQuery, "all") == 0) { // so we can see all
        if ($sortBy != "") {
            $res = mysqli_query($mysqli, "SELECT * from apps WHERE approved=1 ORDER BY $sortBy;");
        } else {
            $res = mysqli_query($mysqli, "SELECT * from apps WHERE approved=1;");
        }
        return $res;
    }
    // implement filter deal here too ...
    if ($sortBy != "") {
        $res = mysqli_query($mysqli, "SELECT * from apps WHERE appName LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%' AND approved=1 ORDER BY $sortBy;");
    } else {
        $res = mysqli_query($mysqli, "SELECT * from apps WHERE appName LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%' AND approved=1;");
    }
    return $res;
}
$sortBy = "";
if (isset($_POST['sort'])) {
    $sortBy = $_POST['sort'];
}
$searchResult = search($mysqli, $searchQuery, $sortBy);



// html doc starts below
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

                <?php if ($action == "" || $action == "default") { ?>
                    <li class="nav-item active">
                    <?php } else { ?>
                    <li class="nav-item">
                    <?php
                }
                ?>
                    <a class="nav-link" href="index.php?action=default">Home</a>
                </li>
                <?php
                if ($pV == 5) {
                    ?>
                    <?php if ($action == "admin") { ?>
                        <li class="nav-item active">
                        <?php } else { ?>
                        <li class="nav-item">
                        <?php
                    }
                    ?>
                        <a class="nav-link" href="index.php?action=admin">Approve Apps</a>
                    </li>
                <?php
                } 
                ?>

                <?php if ($action == "submitApp") { ?>
                    <li class="nav-item active">
                    <?php } else { ?>
                    <li class="nav-item">
                    <?php
                }
                ?>
                    <a class="nav-link" href="index.php?action=submitApp">Submit</a>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav mr-auto">
            <?php
            if ($pV == 0 || $pV == 5) {
                ?>
                <li class="nav-item"><a class="nav-link" href="#">Current User: <?php echo $_SESSION['user']; ?></a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=logout"> Logout</a></li>
            <?php
        } else {
            ?>
                <a class="navbar-brand" href="index.php?action=signup"> Sign up</a>
                <a class="navbar-brand" href="index.php?action=login"> Login</a>
            <?php
        }
        ?>

        </ul>

    </nav>


    <!-- content below navbar -->
    <?php
    if ($action == "login" && ($pV != 0 && $pV != 5)) {
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
                        <input class="form-control" placeholder="User name" type="text" name="usr">
                    </div>
                    <div>
                        <input class="form-control" placeholder="Password" type="password" name="pwd1">
                    </div>
                    <div>
                        <input class="form-control" placeholder="Confirm password" type="password" name="pwd2">
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
} else if ($action == "login" && ($pV == 0 || $pV == 5)) {
    // successful login message
    ?>
        <div class="container">

            <h2>Welcome back, user: <?php echo $_SESSION['user']; ?></h2>
            <a href="index.php">Click here to continue.</a>

        </div>
    <?php
} else if ($action == "admin" && $pV == 5) {
    //admin page
    ?>
        <div class="container">
            <h2>Welcome, Admin!</h2>
            <h5>Apps to approve: </h5>
                <?php
                $_SESSION['currentApp'] = ""; 
                $appsListed = 0; 
                while ($row = mysqli_fetch_assoc($appsToApprove)) {
                    if ($appsListed == 0) {?>
                    <div class="row">
                    <?php
                    }
                    $appsListed = $appsListed + 1;
                ?>
                    <div class="col-sm-4">
                        <div class="card" style="width: 12rem;">
                            <img class="card-img-top" src="images/Star-icon.png" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title"></h5>
                                <p class="card-text"></p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"></li>
                                <li class="list-group-item"><strong>Category:</strong>
                                <p class="card-text"><?php print $row['category'];?></p>
                                </li>
                                <li class="list-group-item">
                                <strong>Description:</strong>
                                <p class="card-text"><?php print $row['appDescription'];?></p>
                                </li>
                                <li class="list-group-item"><strong>$<?php print $row['price'];?></strong></li>
                            </ul>
                            <div class="card-body">
                            <span>
                                <!-- reference to php function that deletes... -->
                                <a href="index.php?appApproval=delete&action=admin&appInteractionId=<?php print $row['appId'] ?>" class="btn btn-danger">DENY</a>
                                <a href="index.php?appApproval=approve&action=admin&appInteractionId=<?php print $row['appId'] ?>" class="btn btn-primary">APPROVE</a>
                            </span>
                            </div>
                        </div>
                    </div>
                    <?php
                if ($appsListed == 3) { ?>
                    </div>
                    <?php
                    $appsListed = 0; 
                }
            } ?>
            </div>

            
            <?php 
} else if ($action == "submitApp" && $addApp != 0) {
    // APP REQUEST FORM
    ?>
        <h1 class="words container-fluid">Welcome! We love taking new apps!</h1>
        <h5>Complete the form below to submit a new app and we'll see about approving it.</h5>

        <form method='post' action="<?php print $_SERVER['PHP_SELF']; ?>?action=submitApp">
            <div class="align-center">
                <div class="submitAppForm">
                    <div class="col-md-4 mb-3">
                        <input type="text" placeholder="App name" name="appName">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" placeholder="Category" name="category">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" placeholder="A short description" name="appDescription">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input size="6"  placeholder="Price" type="number" step=".01" name="price">
                    </div>
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Submit proposed app</button>
                </div>
            </div>
        </form>

    <?php
} else if ($action == "submitApp" && $addApp == 0) {
    // successful app submitted
    ?>
        <div class="container">

            <h2>Great! We hope <?php echo $_SESSION['appName']; ?> makes the cut!</h2>
            <a href="index.php">Click here to continue.</a>

        </div>
    <?php

} else if ($action == "appPage") {
    $res = mysqli_query($mysqli, "SELECT * from apps WHERE appId=$appInteractionId;"); 
    while ($row = mysqli_fetch_assoc($res)) {?>
        
              <div class="row">
                  <div class="col-md-6 pull-left">
                      <h1 style="float: left;"><?php print $row['appName'];?></h1>
                  </div>
                  <div class="col-md-6 pull-right">
                      <h2><button class="btn btn-success btn-large">$<?php print $row['price'];?></button></h2>
                  </div>
              </div>

              <div class="row">
                <div class="pull-left">
                  <img src="images/Star-icon.png" class="img-thumbnail" style="height: 160px; width: 160px;">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 container-fluid">
                  <img src="images/star_wars_endor.png" style="height: 400px; width: 800px;">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                    <h4>Some description bois</h4>
                <p><?php print $row['appDescription'];?></p>
                </div>
                <div class="col-md-6">
                    <h4><?php print $row['category'];?></h4>
                    <h4>Developers</h4>
                    <h4>Platforms</h4>
                    <h4>Versions</h4>
                </div>
                
              </div>

              <div class="container-fluid">
                <h2>Reviews</h2>
                <ul class="list-group">

                <li class="list-group-item">User69 gave 2 stars. --<em>"This app sucks ass man, don't do it ... okay do it"</em></li>

                </ul>
              </div>
        <?php
    }

} else if ($searchQuery != "") {
    // Handle normal searches/displaying apps
    ?>
        <div class="search">
            <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post" class="form-inline">
                <input class="form-control mr-sm-2" type="text" placeholder="You searched <?php echo $searchQuery ?>" aria-label="Search" name="searchQuery">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>

                <label for="sort">Sort by: </label>
                <select class="form-control" name="sort">
                    <option value="appName">Name</option>
                    <option value="price">Price</option>
                    <option value="ratings">Ratings</option>
                    <option value="downloads">Downloads</option>
                    <option value="category">Category</option>
                </select>
            </form>
        </div>

        <!-- app displaying results - - - - - - - - - - - - - - - - -->
        <?php
        while ($row = mysqli_fetch_assoc($searchResult)) {
            // <?php print $row['appId']; 
            ?>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="heading<?php print $row['appId']; ?>">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed float-md-left" type="button" data-toggle="collapse" data-target="#collapse<?php print $row['appId']; ?>" aria-expanded="false" aria-controls="collapse<?php print $row['appId']; ?>">
                                <!-- stuff that shows up before dropping down: -->
                                <span style="float:left;">
                                    <img src="./images/Star-icon.png" height="50" width="50" alt="">
                                </span>
                                <span style="float:left;">
                                    <h2><?php print "{$row['appName']}" ?></h2>
                                </span>
                            </button>
                        </h2>
                    </div>

                    <div id="collapse<?php print $row['appId']; ?>" class="collapse" aria-labelledby="heading<?php print $row['appId']; ?>" data-parent="#accordionExample">
                        <div class="card-body">
                            <span style="float:left;">
                                <h2>Description:</h2>
                                <h4><?php print "{$row['appDescription']}" ?></h4>
                            </span>
                            <span style="float:right;">
                                <h4><?php print "{$row['category']}" ?></h4>
                                <h3>$<?php print "{$row['price']}" ?></h3>
                                <h3><a class="btn btn-primary" href="index.php?action=appPage&appInteractionId=<?php echo $row['appId'] ?>" role="button">More Details</a></h3>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
    ?>
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
                <select class="my-4 my-sm-0" name="sort">
                    <option value="" disabled selected>Sort..</option>
                    <option value="appName">Name</option>
                    <option value="price">Price</option>
                    <option value="ratings">Ratings</option>
                    <option value="downloads">Downloads</option>
                    <option value="category">Category</option>
                </select>

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

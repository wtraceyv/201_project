<?php
session_start();
//require_once "passwords.php";
$user = "201CTeam6";
$password = "NoPassword";
$mysqli = mysqli_connect("35.201.215.85", $user, $password, "appReview");
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die;
}

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
?>
<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
   <link rel="stylesheet" href="../css/mainCSS.css">

   <title>Login</title>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
      <a class="navbar-brand" href="../main.html">appReview</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
         aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
         <ul class="navbar-nav mr-auto">

          <li class="nav-item active">
             <a class="nav-link" href="../main.html">Home</a>
          </li>
          <li class="nav-item">
             <a class="nav-link" href="https://www.youtube.com/">Youtube</a>
          </li>
          <li class="nav-item">
             <a class="nav-link" href="../redirectedPage.html">Go elsewhere</a>
          </li>
         </ul>
      </div>
   </nav>
    <div class="container">

        <h1>Please sign in</h1>
        <div class="form-group">
            <form method='post' action="<?php print $_SERVER['PHP_SELF']; ?>">
                User: <input type="text" name="user">
                Password: <input type="password" name="pass">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Sing in</button>
            </form>
            <p>
                <?php
                if (pwdV($mysqli) == 1) {
                    echo 'The Password is invalid!';
                } else if (pwdV($mysqli) == 2) {
                    echo 'The Username are invalid!';
                }
                ?>
            </p>
        </div>
    </div>
</body>

</html>
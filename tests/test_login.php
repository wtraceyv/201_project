<?php
//connect to the data base
// mysql --user=201CTeam6 --password=NoPassword --host=35.201.215.85 --database="appReview"
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
?>
<!doctype html>
<html>

<body>
    <form method='post' action="<?php print $_SERVER['PHP_SELF']; ?>">

        <label for="user">Username: </label>
        <input class="form-control" type="text" name="user">

        <label for="pass">Password: </label>
        <input class="form-control" type="password" name="pass">

        <p>
            <?php
            if ($pV == 1) {
                echo 'The Password is invalid!';
            } else if ($pV == 2) {
                echo 'The Username are invalid!';
            }
            ?>
        </p>
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Log in</button>

    </form>
    <p>If no additional message displayed, you successfully logged in</p>
</body>

</html>
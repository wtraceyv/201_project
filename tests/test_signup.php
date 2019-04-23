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
?>
<!doctype html>
<html>

<body>
    <form method='post' action="<?php print $_SERVER['PHP_SELF']; ?>">

        <label for="usr">Username: </label>
        <input class="form-control" type="text" name="usr">

        <label for="pwd1">Password: </label>
        <input class="form-control" type="password" name="pwd1">

        <label for="pwd2">Verify Password: </label>
        <input class="form-control" type="password" name="pwd2">

        <p>
            <?php
            if ($sV == 1) {
                echo 'The Verify Password is not same as the Password!';
            } else if ($sV == 2) {
                echo 'The Username is already exist!';
            }
            ?>
        </p>
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Sign up</button>

        <p>If no addtional message displays, you succeffully signed up</p>
    </form>
</body>

</html>
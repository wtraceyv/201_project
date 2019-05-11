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
function pwdV($mysqli, $user, $pass)
{
    
        $res = mysqli_query($mysqli, "SELECT user, password, division, admin, moderator from users order by user");
        if (!$res) {
            echo "error on sql - $mysqli->error";
        } else {
            $f = true;
            while ($row = mysqli_fetch_assoc($res)) {
                if ($user === $row['user']) {
                    if (password_verify($pass, $row['password'])) {
                        if ($row['admin']==1) {
                            return 5;
                        } else if ($row['moderator']==1) {
                            return 4;
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
    
    return 3;
}
?>

<!doctype html>
<html>

<body>
    <!-- print buttons according to whether tests make sense or not --> 
    <?php
        // set some example inputs ...
        // good login example (expect 0): 
        $user1 = "test"; 
        $pass1 = "test"; 
        // good user, bad pass (expect 1): 
        $user2 = "test"; 
        $pass2 = "bluhbluhsdf"; 
        // bad user (expect 2):
        $user3 = "dingdongbingbong"; 
        $pass3 = "test"; 
        // both bad (expect 2):  
        $user4 = "why me"; 
        $pass4 = "weiufnj"; 

        
        if (pwdV($mysqli, $user1, $pass1) === 5) {
            ?>
                <button type="button" class="btn btn-success btn-block">Good user and pass</button>
            <?php
        } else {
            ?>
                <button type="button" class="btn btn-danger btn-block">Good user and pass</button>
            <?php
        }

        if (pwdV($mysqli, $user2, $pass2) === 1) {
            ?>
                <button type="button" class="btn btn-success btn-block">Bad pass</button>
            <?php
        } else {
            ?>
                <button type="button" class="btn btn-danger btn-block">Bad pass</button>
            <?php
        }

        if (pwdV($mysqli, $user3, $pass3) === 2) {
            ?>
                <button type="button" class="btn btn-success btn-block">Bad user</button>
            <?php
        } else {
            ?>
                <button type="button" class="btn btn-danger btn-block">Bad user</button>
            <?php
        }

    ?>



    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>

</html>

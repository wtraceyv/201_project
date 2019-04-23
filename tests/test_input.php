<?php
if (isset($_POST['searchQuery'])) {
    $_SESSION['searchQuery'] = htmlspecialchars($_POST['searchQuery']);
}
if (!isset($_SESSION['searchQuery'])) {
    $_SESSION['searchQuery'] = "";
}
$searchQuery = $_SESSION['searchQuery'];
?>
<!DOCTYPE html>
<html>
    <body>
        <form method="POST" action="test_input.php">
            <input type="text" name="searchQuery">
            <input type="submit">
        </form>
        <p><?php echo 'you searched: '.$_SESSION['searchQuery']?></p>
    </body>
</html>

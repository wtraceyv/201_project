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
//SEARCHING FUNCTION
if (isset($_POST['searchQuery'])) {
    $_SESSION['searchQuery'] = htmlspecialchars($_POST['searchQuery']);
}
if (!isset($_SESSION['searchQuery'])) {
    $_SESSION['searchQuery'] = "";
}
$searchQuery = $_SESSION['searchQuery'];
function search($mysqli, $searchQuery)
{
    if (strcasecmp($searchQuery, "all") == 0) { // so we can see all
        $res = mysqli_query($mysqli, "SELECT * from apps;");
        return $res;
    }

    // implement filter deal here too ...

    $res = mysqli_query($mysqli, "SELECT * from apps WHERE appName LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%';");
    return $res;
}
$searchResult = search($mysqli, $searchQuery);
?>
<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="search">
        <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post" class="form-inline">
            <input class="form-control mr-sm-2" type="text" placeholder="You searched <?php echo $searchQuery ?>" aria-label="Search" name="searchQuery">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>

            <div class="btn-group">
                <button type="button" class="btn btn-info">Sort by</button>
                <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                    <button class="dropdown-item" type="button">Name (default)</button>
                    <button class="dropdown-item" type="button">Price</button>
                    <button class="dropdown-item" type="button">Ratings</button>
                    <button class="dropdown-item" type="button">Downloads</button>
                    <button class="dropdown-item" type="button">Category</button>
                </div>
            </div>
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
                            <img src="./images/Star-icon.png" height="50" width="50" alt=""><?php print "{$row['appName']} -- {$row['price']}" ?>
                        </button>
                    </h2>
                </div>

                <div id="collapse<?php print $row['appId']; ?>" class="collapse" aria-labelledby="heading<?php print $row['appId']; ?>" data-parent="#accordionExample">
                    <div class="card-body">

                        <p><?php print "{$row['appDescription']}" ?></p>
                    </div>

                </div>
            </div>
        </div>
    <?php
}
?>
    <!-- end app displaying results -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
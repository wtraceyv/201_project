<?php

session_start(); //create a session

//YOU WILL PUT YOUR FORM HANDLING CODE HERE
if(isset($_POST['searchQuery'])){
  $_SESSION['searchQuery'] = htmlspecialchars($_POST['searchQuery']);
}
if(!isset($_SESSION['searchQuery'])){
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
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
   <link rel="stylesheet" href="./mainCSS.css">

   <title>Search Result</title>
</head>
<body>

   <!-- anything having to do with the nav bar -->
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
   <p class="words">You searched <?php echo $searchQuery ?></p>
   
   
   <!-- Optional JavaScript -->
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
      crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
      crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
      crossorigin="anonymous"></script>
</body>

</html>

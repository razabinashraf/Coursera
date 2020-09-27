<?php
    session_start();
    require_once "pdo.php";
    if ( !isset($_SESSION["account"]) ) {
        die("Not logged in");
    }
    $stmt = $pdo->query("SELECT make, year, mileage FROM autos");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<title>RAZA ILTHAMISH</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo(htmlentities($_SESSION['account'])) ?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['success']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
if ( isset($_SESSION['failure']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
?>
<h2>Automobiles</h2>
<ul>
<p>
</ul>
<p>
  <a href="add.php">Add New</a> |
  <a href="logout.php">Logout</a>
</p>
<ul>
<?php
	if(!empty($rows)){
        foreach ($rows as $row){
            echo ("<li>");
            echo htmlentities($row['make'].' '.$row['year'].' '.$row['mileage']);
            echo ("</li>");
        }
    }
?>
</ul>
</div>
</html>

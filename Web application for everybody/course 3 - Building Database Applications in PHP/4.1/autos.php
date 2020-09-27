<?php
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}
require_once "pdo.php";
$failure = false;
$success = false;
if ((isset($_POST['year'])) && (isset($_POST['mileage'])) && (isset($_POST['make'])))
{
    if ((is_numeric($_POST['mileage'])) && (is_numeric($_POST['year'])))
    {
        if (strlen($_POST['make'])>1)
        {
            $sql = "INSERT INTO autos (make,year,mileage)
                      VALUES (:make, :year, :mileage)";
            //echo ("<pre>\n".$sql."\n</pre>\n");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':make'=>$_POST['make'],
                ':mileage'=>$_POST['mileage'],
                ':year'=>$_POST['year']));
            $success = "Record inserted";
        }
         else {
            $failure = "Make is required";
          }
    }
     else {
        $failure = "Mileage and year must be numeric";
      }
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
<div class="container">
<body>
<h1>Tracking data for <?php echo(htmlentities($_GET['name'])) ?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $success !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
}
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="POST"><br/><br/>
<label for="make">make</label>
<input type="text" name="make" id="make"><br/>
<label for="year">year</label>
<input type="text" name="year" id="year"><br/>
<label for="mileage">mileage</label>
<input type="text" name="mileage" id="mileage"><br/>
<input type="submit" value="Add">
<input type="submit" name="logout" value="logout">
</form>
<h2>Automobiles</h2>
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
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>

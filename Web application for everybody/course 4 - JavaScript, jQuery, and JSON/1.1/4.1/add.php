<?php
session_start();
require_once "pdo.php";
if ( !isset($_SESSION["account"]) ) {
    die("Not logged in");
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
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
            $_SESSION['success'] = "Record inserted";
            header('Location: view.php');
            return;
        }
         else {
            $_SESSION['failure'] = "Make is required";
          }
    }
     else {
        $_SESSION['failure'] = "Mileage and year must be numeric";
      }header( 'Location: add.php' ) ;
      return;
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
<h1>Tracking data for <?php echo(htmlentities($_SESSION['account'])) ?></h1>
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
<form method="POST"><br/><br/>
<label for="make">make</label>
<input type="text" name="make" id="make"><br/>
<label for="year">year</label>
<input type="text" name="year" id="year"><br/>
<label for="mileage">mileage</label>
<input type="text" name="mileage" id="mileage"><br/>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="cancel">
</form>

</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>

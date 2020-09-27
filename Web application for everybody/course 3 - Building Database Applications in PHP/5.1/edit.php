<?php
require_once "pdo.php";
session_start();
if ( !isset($_SESSION["account"]) ) {
    die("Not logged in");
}

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year']) && isset($_POST['mileage'])
     && isset($_POST['auto_id']) ) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;
    }

    if ((!is_numeric($_POST['mileage'])) || (is_numeric($_POST['year']))) {
        $_SESSION['error'] = 'Milage and Year must be numeric';
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;
    }

    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage
            WHERE auto_id = :auto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':auto_id' => $_POST['auto_id']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that auto_id is present
if ( ! isset($_GET['auto_id']) ) {
  $_SESSION['error'] = "Missing auto_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header( 'Location: index.php' ) ;
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>RAZA ILTHAMISH</title>

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

</head>
<body>
  <div class="container">
<h1>Editing Automobile</h1>
<?php

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$n = htmlentities($row['make']);
$e = htmlentities($row['model']);
$p = htmlentities($row['year']);
$m = htmlentities($row['mileage']);
$auto_id = $row['auto_id'];
?>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $n ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $e ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $p ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $m ?>"></p>
<input type="hidden" name="auto_id" value="<?= $auto_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>

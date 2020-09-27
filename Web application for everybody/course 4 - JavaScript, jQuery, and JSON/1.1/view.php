<?php
    session_start();
    require_once "pdo.php";

    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
<h1>Profile information</h1>
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
if (empty($row)) {
  $_SESSION['error'] = 'Bad value for profile_id';
  header( 'Location: index.php' ) ;
  return;
}
?>
<p>First Name:<?= $row['first_name']?></p>
<p>Last Name:<?= $row['last_name']?></p>
<p>Email:<?= $row['email']?></p>
<p>Headline:<br/> <?= $row['headline']?></p>
<p>Summary:<br/> <?= $row['summary']?>
<p><a href="index.php">Done</a></p>
<ul>
<p>
</ul>
<ul>
</ul>
</div>
</html>

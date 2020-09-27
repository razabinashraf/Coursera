<?php
    session_start();
    require_once "pdo.php";
    require_once "util.php";

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

flashmessage();
if (empty($row)) {
  $_SESSION['error'] = 'Bad value for profile_id';
  header( 'Location: index.php' ) ;
  return;
}
$f = htmlentities($row['first_name']);
$l = htmlentities($row['last_name']);
$e = htmlentities($row['email']);
$h = htmlentities($row['headline']);
$s = htmlentities($row['summary']);
?>
<p>First Name:<?= $f ?></p>
<p>Last Name:<?= $l ?></p>
<p>Email:<?= $e ?></p>
<p>Headline:<br/> <?= $h?></p>
<p>Summary:<br/> <?= $s ?>
<?php
$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :profile_id");
$stmt->execute(array(":profile_id" => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!empty($rows)){
  echo('<p>Position</p><ul>');
  foreach ($rows as $row){
    echo('<li>'.htmlentities($row['year']).':'.htmlentities($row['description']).'</li>');
  }
  echo('</ul>');
}

 ?>
<p><a href="index.php">Done</a></p>
<ul>
<p>
</ul>
<ul>
</ul>
</div>
</html>

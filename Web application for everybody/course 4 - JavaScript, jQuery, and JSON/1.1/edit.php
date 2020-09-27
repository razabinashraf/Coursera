<?php
require_once "pdo.php";
session_start();
if ( !isset($_SESSION["account"]) ) {
    die("Not logged in");
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline'])
     && isset($_POST['profile_id']) && isset($_POST['save']) ) {

       //Data validation
         if (((strlen($_POST['first_name']))<1) || ((strlen($_POST['last_name']))<1)
               || ((strlen($_POST['email']))<1) || ((strlen($_POST['summary']))<1)
               || ((strlen($_POST['headline']))<1)) {
           $_SESSION['error'] = 'All fields are required';
           header('Location: add.php');
           return;
         }
         if ( !strstr($_POST['email'],'@')){
           $_SESSION['error'] = 'Email must have an at-sign (@)';
           header('Location: add.php');
           return;
         }

    $sql = "UPDATE profile SET first_name = :first_name, summary = :summary,
            last_name = :last_name, email = :email, headline = :headline
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: first_name sure that profile_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
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

$f = htmlentities($row['first_name']);
$l = htmlentities($row['last_name']);
$e = htmlentities($row['email']);
$h = htmlentities($row['headline']);
$s = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>
<form method="post">
<p>first_name:
<input type="text" name="first_name" value="<?= $f ?>"></p>
<p>last_name:
<input type="text" name="last_name" value="<?= $l ?>"></p>
<p>email:
<input type="text" name="email" value="<?= $e ?>"></p>
<p>headline:
<input type="text" name="headline" value="<?= $h ?>"></p>
<p>summary:
<input type="text" name="summary" value="<?= $s ?>"></p>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p><input type="submit" value="Save" name="save"/>
<input type="submit" value="Cancel" name="cancel"/></p>
</form>

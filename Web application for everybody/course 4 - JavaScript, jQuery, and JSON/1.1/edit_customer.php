<?php
require_once "pdo.php";
session_start();
if ((!isset($_SESSION['driver'])) && (!isset($_SESSION['admin']))){
    die("Not logged in");
}

if ( isset($_POST['name']) && isset($_POST['email'])
     && isset($_POST['mobile']) && isset($_POST['iqama'])
     && isset($_POST['customer_id']) ) {

    // Data validation
    if ( strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?customer_id=".$_POST['customer_id']);
        return;
    }

    if ((!is_numeric($_POST['iqama'])) || (!is_numeric($_POST['mobile']))) {
        $_SESSION['error'] = 'Iqama and mobile must be numeric';
        header("Location: edit.php?customer_id=".$_POST['customer_id']);
        return;
    }

    $sql = "UPDATE customer SET name = :name,
            email = :email, mobile = :mobile, iqama = :iqama
            WHERE customer_id = :customer_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':mobile' => $_POST['mobile'],
        ':iqama' => $_POST['iqama'],
        ':customer_id' => $_POST['customer_id']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
}


// Guardian: name sure that customer_id is present
if ( !isset($_GET['customer_id'])) {
  $_SESSION['error'] = "Missing customer id";
  header('Location: index.php');
  return;
}
$stmt = $pdo->prepare("SELECT * FROM customer where customer_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['customer_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for customer_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Masafi Water</title>

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
<h1>Editing Window</h1>
<?php

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$n = htmlentities($row['name']);
$e = htmlentities($row['email']);
$m = htmlentities($row['mobile']);
$i = htmlentities($row['iqama']);
$customer_id = $row['customer_id'];
?>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $n ?>"></p>
<p>E-mail:
<input type="text" name="email" value="<?= $e ?>"></p>
<p>Mobile:
<input type="text" name="mobile" value="<?= $m ?>"></p>
<p>Iqama:
<input type="text" name="iqama" value="<?= $i ?>"></p>
<input type="hidden" name="customer_id" value="<?= $customer_id ?>">
<p><input type="submit" value="Save"/>
<a href="view_customer.php">Cancel</a></p>
</form>

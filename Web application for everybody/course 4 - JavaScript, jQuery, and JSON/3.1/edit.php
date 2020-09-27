<?php
require_once "pdo.php";
require_once "util.php";
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
       $msg = validateprofile();
       if ($msg !== true) {
         $_SESSION['error']=$msg;
         header('Location: edit.php?profile_id='.$_GET['profile_id']);
         return;
       }
       $msg = validatePos();
       if ($msg !== true) {
         $_SESSION['error']=$msg;
         header('Location: edit.php?profile_id='.$_GET['profile_id']);
         return;
       }

       $stmt = $pdo->prepare("DELETE FROM position WHERE profile_id = :zip");
       $stmt->execute(array(':zip' => $_POST['profile_id']));

       $rank = 1;
       for($i=1; $i<=9; $i++) {
         if ( ! isset($_POST['year'.$i]) ) continue;
         if ( ! isset($_POST['desc'.$i]) ) continue;
       $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
       $stmt->execute(array(
         ':pid' => $_GET['profile_id'],
         ':rank' => $rank,
         ':year' => $_POST['year'.$i],
         ':desc' => $_POST['desc'.$i]));
       $rank++;
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
$totalpos=0;
$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :profile_id");
$stmt->execute(array(":profile_id" => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $rows_test){
  $totalpos=$totalpos+1;
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
flashmessage();

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
<p>Position:
<input type="submit" id="addPos" value="+">
<div id="position_fields">
  <?php
  foreach ($rows as $row) {
    $rank = $row['rank'];
    $year = $row['year'];
    $desc = $row['description'];
    echo '<div id="position' . $rank . '">.';
 echo '<p>Year: <input type="text" name="year' . $rank . '" value="' . $year . '" />';
 echo '<input type="button" value="-" onclick="$(' . "'#position" . $rank . "').remove();return false;" . '">';
 echo "</p>";
 echo '<textarea name="desc' . $rank . '" rows="8" cols="80">';
 echo $desc;
 echo "</textarea>";
 echo "</div>";
  }
   ?>
</div>
</p>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p><input type="submit" value="Save" name="save"/>
<input type="submit" value="Cancel" name="cancel"/></p>
</form>
<script>
countPos = <?php echo($totalpos); ?>;

$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>

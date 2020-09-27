<?php
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
session_start();
if ( !isset($_SESSION["account"]) ) {
    die("Not logged in");
}
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
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
       updateprofile($pdo,$_POST['profile_id']);
       deleteposition($pdo,$_POST['profile_id']);
       insertposition($pdo,$_POST['profile_id']);
       deleteeducation($pdo,$_POST['profile_id']);
       inserteducation($pdo,$_POST['profile_id']);
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
}
//selecting old data to display
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
$f = htmlentities($row['first_name']);
$l = htmlentities($row['last_name']);
$e = htmlentities($row['email']);
$h = htmlentities($row['headline']);
$s = htmlentities($row['summary']);
$profile_id = $row['profile_id'];

$totaledu=0;
$stmt = $pdo->prepare("SELECT year,name,rank FROM education JOIN institution ON
                        education.institution_id = institution.institution_id WHERE profile_id = :prof ORDER BY rank");
$stmt->execute(array(":prof" => $_GET['profile_id']));
$rows_education = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows_education as $row){
  $totaledu=$totaledu+1;
}
$totalpos=0;
$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :profile_id");
$stmt->execute(array(":profile_id" => $_GET['profile_id']));
$rows_position = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows_position as $row){
  $totalpos=$totalpos+1;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>RAZA ILTHAMISH</title>
</head>
<body>
  <div class="container">
<h1>Editing Automobile</h1>
<?php

flashmessage();

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
<p>
Education: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
  <?php
  foreach ($rows_education as $row) {
    $rank = $row['rank'];
    $year = $row['year'];
    $name = $row['name'];
    echo '<div id="edu' . $rank . '">.';
  echo '<p>Year: <input type="text" name="edu_year' . $rank . '" value="' . $year . '" />';
  echo '<input type="button" value="-" onclick="$(' . "'#edu" . $rank . "').remove();return false;" . '">';
  echo "</p>";
  echo '<input type="text" size="80"  name="edu_school' . $rank . '" value="'. $name .'" class="school"
  />';
  echo "</div>";
  }
   ?>
</div>
</p>
<p>Position:
<input type="submit" id="addPos" value="+">
<div id="position_fields">
  <?php
  foreach ($rows_position as $row) {
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
countEdu = <?php echo($totaledu); ?>;

$(document).ready(function(){
    window.console && console.log('Document ready called');

    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
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
            <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });
    $('.school').autocomplete({
        source: "school.php"
    });

});
</script>

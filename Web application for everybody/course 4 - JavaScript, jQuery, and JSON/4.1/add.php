<?php
session_start();
require_once "pdo.php";
require_once "util.php";
require_once "head.php";
if ( !isset($_SESSION["account"]) ) {
    die("ACCESS DENIED");
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
if ((isset($_POST['email'])) && (isset($_POST['headline']))
          && (isset($_POST['first_name'])) && (isset($_POST['last_name']))
                  && (isset($_POST['summary']))) {

      $msg = validateprofile();
      if ($msg !== true) {
        $_SESSION['error']=$msg;
        header('Location: add.php');
        return;
      }
      $msg = validatePos();
      if ($msg !== true) {
        $_SESSION['error']=$msg;
        header('Location: add.php');
        return;
      }


      insertprofile($pdo);
      $profile_id=$pdo->lastInsertId();
      insertposition($pdo,$profile_id);
      inserteducation($pdo,$profile_id);
           $_SESSION['success'] = "Record added";
           header('Location: index.php');
           return;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>RAZA ILTHAMISH</title>
<!-- Latest compiled and minified CSS -->
</head>
<div class="container">
<body>
<h1>Adding Profile For <?php echo(htmlentities($_SESSION['account'])) ?></h1>
<?php
flashmessage();
?>
<form method="POST"><br/><br/>
<label for="first_name">first_name</label>
<input type="text" name="first_name" id="first_name"><br/>
<label for="last_name">last_name</label>
<input type="text" name="last_name" id="last_name"><br/>
<label for="email">email</label>
<input type="text" name="email" id="email"><br/>
<label for="headline">headline</label>
<input type="text" name="headline" id="headline"><br/>
<label for="summary">summary</label>
<input type="text" name="summary" id="summary"><br/>
<p>
Education: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
</div>
</p>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="cancel">
</form>
<script>
countPos = 0;
countEdu = 0;

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

</div>
</body>

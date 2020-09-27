<?php
session_start();
require_once "pdo.php";
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


            $sql = "INSERT INTO profile (user_id,first_name,last_name,email,headline,summary)
                      VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
            ##echo ("<pre>\n".$sql."\n</pre>\n");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':first_name'=>$_POST['first_name'],
                ':last_name'=>$_POST['last_name'],
                ':email'=>$_POST['email'],
                ':user_id'=>$_SESSION['uid'],
                ':summary'=>$_POST['summary'],
                ':headline'=>$_POST['headline']));
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<div class="container">
<body>
<h1>Adding Profile For <?php echo(htmlentities($_SESSION['account'])) ?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['success']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
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
<input type="submit" value="Add">
<input type="submit" name="cancel" value="cancel">
</form>

</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>

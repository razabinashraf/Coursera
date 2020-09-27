<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}


$salt = 'XyZzy12*_';


// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header( 'Location: login.php' ) ;
        return;
    } else {
        if ( strstr($_POST['email'],'@')) {
          $sql = ("SELECT email, password, user_id FROM users WHERE email = :email AND password =:pass");
          $stmt = $pdo->prepare($sql);

          $stmt->execute(array(
              ':email' => $_POST['email'],
              ':pass' => md5($salt.$_POST['pass'])));
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if ( $row == true ){
              error_log("Login success ".$_POST['email']);
              $_SESSION["account"] = $_POST["email"];
              $_SESSION['uid'] = $row['user_id'];
              $_SESSION["success"] = "Logged in.";
              header( 'Location: index.php' ) ;
              return;
          } else {
            error_log("Login fail ".$_POST['email']." $check");
            $_SESSION['error'] = "Incorrect password.";
            header( 'Location: login.php' ) ;
            return;
            }
        } else {
              $_SESSION['error'] = "Email must have an at-sign (@)";
              error_log("Login fail ".$_POST['email']);
              header( 'Location: login.php' ) ;
              return;
}
}
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>RAZA ILTHAMISH</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION["error"]) ) {
    echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
    unset($_SESSION["error"]);
}

?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="email" id="id_1724"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<script>
function doValidate() {
  console.log('Validating...');
  try {
    pw = document.getElementById('id_1723').value;
    console.log("Validating pw="+pw);
    if (pw == null || pw == "") {
      alert("Both fields must be filled out");
      return false;
    }
    return true;
  } catch(e) {
    em = document.getElementById('id_1724').value;
    console.log("Validating pw="+em);
    if (em == null || em == "") {
      alert("Both fields must be filled out");
    }
    return false;
}
}
</script>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
</div>
</body>

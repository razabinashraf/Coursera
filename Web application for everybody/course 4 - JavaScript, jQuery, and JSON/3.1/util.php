<?php
//function to display any error messages if set
function flashmessage(){
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
}
//validating position parametres
function validatePos() {
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    if ( strlen($year) == 0 || strlen($desc) == 0 ) {
      return "All fields are required";
    }

    if ( ! is_numeric($year) ) {
      return "Position year must be numeric";
    }
  }
  return true;
}
//Data validation
function validateprofile() {
          if (((strlen($_POST['first_name']))<1) || ((strlen($_POST['last_name']))<1)
                || ((strlen($_POST['email']))<1) || ((strlen($_POST['summary']))<1)
                || ((strlen($_POST['headline']))<1)) {
            return 'All fields are required';
          }
          if ( !strstr($_POST['email'],'@')) {
            return 'Email must have an at-sign (@)';
          }
          return true;
}

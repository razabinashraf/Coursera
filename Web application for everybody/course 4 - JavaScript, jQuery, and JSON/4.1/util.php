<?php
require_once "pdo.php";
require_once "head.php";
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
//validating positions Entered
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
//function to insert profile
function insertprofile($pdo) {
  $sql = "INSERT INTO profile (user_id,first_name,last_name,email,headline,summary)
            VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
      ':first_name'=>$_POST['first_name'],
      ':last_name'=>$_POST['last_name'],
      ':email'=>$_POST['email'],
      ':user_id'=>$_SESSION['uid'],
      ':summary'=>$_POST['summary'],
      ':headline'=>$_POST['headline']));
}
//function to insert position VALUES
function insertposition($pdo,$profile_id) {
  $rank = 1;
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;
  $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
  $stmt->execute(array(
    ':pid' => $profile_id,
    ':rank' => $rank,
    ':year' => $_POST['year'.$i],
    ':desc' => $_POST['desc'.$i]));
  $rank++;
 }
}
//function to get education data
function geteducation($pdo,$profile_id) {
  $stmt = $pdo->prepare("SELECT year,name FROM education JOIN institution ON
                          education.institution_id = institution.institution_id WHERE profile_id = :prof ORDER BY rank");
  $stmt->execute(array(":prof" => $profile_id));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//function to get positions
function getposition($pdo,$profile_id) {
  $stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :profile_id");
  $stmt->execute(array(":profile_id" => $profile_id));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function deleteposition($pdo,$profile_id) {

    $stmt = $pdo->prepare("DELETE FROM position WHERE profile_id = :zip");
    $stmt->execute(array(':zip' => $profile_id));
}
//function to update profile
function updateprofile($pdo,$profile_id) {
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
      ':profile_id' => $profile_id));
}
function deleteeducation($pdo,$profile_id) {
  $stmt = $pdo->prepare("DELETE FROM education WHERE profile_id = :zip");
  $stmt->execute(array(':zip' => $profile_id));
}
//function to updte Education
function inserteducation($pdo,$profile_id) {
  $rank = 1;
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['edu_year'.$i]) ) continue;
    if ( ! isset($_POST['edu_school'.$i]) ) continue;
    $iid = false;
    $stmt = $pdo->prepare("SELECT institution_id FROM institution WHERE name = :name");
    $stmt->execute(array(':name' => $_POST['edu_school'.$i]));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false) $iid = $row['institution_id'];

    if ($row === false){
      $stmt = $pdo->prepare("INSERT INTO institution (name) VALUES (:name)");
      $stmt->execute(array(':name' => $_POST['edu_school'.$i]));
      $iid = $pdo->lastInsertId();
    }

      $sql = $pdo->prepare("INSERT INTO education (profile_id, rank, year, institution_id) VALUES ( :pid, :rank, :year, :iid)");
      $sql->execute(array(
        ':pid' => $profile_id,
        ':rank' => $rank,
        ':year' => $_POST['edu_year'.$i],
        ':iid' => $iid));
      $rank++;
 }
}

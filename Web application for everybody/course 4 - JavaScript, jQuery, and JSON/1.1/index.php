<?php
    session_start();
    require_once "pdo.php";

    $stmt = $pdo->query("SELECT first_name, last_name, headline, profile_id FROM profile");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<h1>Resume Registry</h1>


<?php

if ( isset($_SESSION['success']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}


if (isset($_SESSION['account'])) {
  if (!empty($rows)) {
  echo('<table border="1">'."\n");
  echo('<thead><tr>
  <th>Name</th>
  <th>Headline</th>
  <th>Action</th>
  </tr></thead>');
  foreach ($rows as $row){
    echo "<tr><td>";
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'])." ".$row['last_name'].'</a>');
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</td></tr>\n");
  }
  }
    echo('<p>
      <a href="add.php">Add New Entry</a> |
      <a href="logout.php">Logout</a>
    </p>');

}
if (!isset($_SESSION['account'])) {
if (!empty($rows)) {
  echo('<table border="1">'."\n");
  echo('<thead><tr>
  <th>Name</th>
  <th>Headline</th>
  </tr></thead>');
  foreach ($rows as $row){
    echo "<tr><td>";
    echo(htmlentities($row['first_name'])." ".$row['last_name']);
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td></tr>\n");

  }
  echo('<p><a href="login.php">Please log in</a></p>');
}
}
?>
<ul>
<p>
</ul>
<ul>
</ul>
</div>
</html>

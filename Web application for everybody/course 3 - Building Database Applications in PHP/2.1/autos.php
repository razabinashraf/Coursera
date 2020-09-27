<?php
require_once "pdo.php";

if ( isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']))  {
       $stmt = $pdo->prepare("INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)");
       $stmt->execute(array(
           ':mk' => $_POST['make'],
           ':yr' => $_POST['year'],
           ':mi' => $_POST['mileage'])
           );
?>

<!DOCTYPE html>
<html>
<head>
<title>Faiz Bin Saleem's Automobile Tracker</title>
</head>
<body>
<div class="container">
<h1>Tracking Autos for <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="c6a0a7afbc86a1aba7afaae8a5a9ab">[email&#160;protected]</a></h1>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>

<p>
  <?php
  echo "<pre>\n";
  $stmt = $pdo->query("SELECT * FROM misc");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<table border="1">'."\n";
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo($row['make']);
    echo("</td><td>");
    echo($row['year']);
    echo("</td><td>");
    echo($row['mileage']);
    echo("</td></tr>\n");
}
echo "</table>\n";
?>
</p>
</div>
</body>
</html>

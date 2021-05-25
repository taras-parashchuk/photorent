<!DOCTYPE html>
<html>
<body>


<?php
$servername = "localhost";
$username = "mysql";
$password = "mysql";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";


$que1 = "SELECT ID, post_title FROM wp_posts WHERE post_status ='product'";

$sql = "SELECT description FROM wp_term_taxonomy WHERE taxonomy = 'post_translations' ";
$products = $conn->query($que1);


echo "<br>";
echo "<b>Number of products:</b>". $products->num_rows."<br>";

if ($products->num_rows > 0) {
  // output data of each row
  while($row = $products->fetch_assoc()) {
    echo "id: " . $row["ID"]. " - TITLE: " . $row["post_title"]. " " ."<br>";
 }
  


echo "<br>";


?>




</body>
</html>
<?php
$servername = "localhost";
$username = "mysql";
$password = "mysql";
$dbname = "smoggmk";

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully<br>";
$sql = "select max(ID) from wp_posts";	
$result = $conn->query($sql);	
//print_r($conn->query($sql));



echo "<br>";
//echo $result->field_count;
$rows   = $result->fetch_all(MYSQLI_ASSOC);
$max_id = $rows[0]["max(ID)"];
echo $max_id;

?>
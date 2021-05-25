<!DOCTYPE html>
<html>
<body>



<?php

$today   = getdate();
$logfile = fopen("inv-".$today[mday]."-".$today[month]."-".$today[year]."-".$today[hours]."-".$today[minutes].".log", "w");

$servername = "localhost";
$username = "mysql";
$password = "mysql";
$dbname = "smoggmk";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


//echo "Connected successfully<br>";
//echo "<br>";
$txtf = "Connected successfully \n";

// Get maximum post ID
$sql = "select max(ID) from wp_posts";	
$result = $conn->query($sql);	
$rows   = $result->fetch_all(MYSQLI_ASSOC);
$max_id = $rows[0]["max(ID)"];


//echo "Max Id: ".$max_id . "<br>";
$txtf = $txtf ."Max Id: ".$max_id . "\n";
//echo "<br>";


// Get the  post_type = 'product'
$sql_product = "SELECT ID, post_title FROM wp_posts WHERE post_type ='product' AND post_status='publish'";
$products = $conn->query($sql_product);

//echo "Products: " . $products->num_rows."<br>";
$txtf = $txtf. "Products: " . $products->num_rows."\n";
//echo "<br>";

$sql_inventory = "SELECT * FROM wp_posts WHERE post_type = 'inventory'";
$inventory = $conn->query($sql_inventory);

//echo "Inventory records: ".$inventory->num_rows. "<br>";
$txtf = $txtf."Inventory records: ".$inventory->num_rows. "\n";
//echo "<br>";

$del_inventory = "DELETE FROM wp_posts WHERE post_type = 'inventory'";
$conn->query($del_inventory);

//echo "Inventory deleted";
$txtf = $txtf. "Inventory deleted \n";
//echo "<br>";

$del_wp_rnb_inventory_product = "TRUNCATE TABLE wp_rnb_inventory_product";
$conn->query($del_wp_rnb_inventory_product);
//echo "TABLE wp_rnb_inventory_product TRUNCATED";
$txtf = $txtf. "TABLE wp_rnb_inventory_product TRUNCATED \n";
//echo "<br>";

echo "DISP :". $disp = str_replace("\n","<br>",$txtf);
$txtf = "";


$updated_inventory = 0;
$error_updating_inventory = 0;
$products_count = 0;

$dateTimeVariable = date("Y-m-d H:i:s");

$post_author   = '591';
$post_date     = $dateTimeVariable;
$post_date_gmt = $dateTimeVariable;
$post_content  = '';
//$post_title    = m_post_title;
$post_excerpt  = '';
$post_status   = 'publish';
$comment_status= 'closed';
$ping_status   = 'closed';

$post_name     = ''; // ??? 
$guid          = ''; // ???
$post_type     = 'inventory';


echo "Time is: ". $dateTimeVariable;
echo "<br>";

 $new_inventory_id = $max_id + 1;
 $item_num = 0;
echo "<b>".$new_inventory_id."</b>";
  // output data of each row

echo "<table style='border:1px solid black'>";
echo "<tr><td>Num</td><td>Prod ID</td><td>Inv ID</td><td>Title</td><td>wp_posts</td><td>inv tbl</td></tr>";

  while($row = $products->fetch_assoc()) {
  
                  

                  $product_title = $row["post_title"];
                  $product_title = addslashes($product_title);
                 	$product_id = $row["ID"];
             
     							// Створюємо новий товар post_type = 'inventory', отримуємsо inventory ID
  
$sql = "INSERT INTO wp_posts(ID, post_author, post_content, post_title, post_excerpt, post_status,  comment_status, ping_status, post_name, guid, post_type, to_ping,pinged,post_content_filtered,post_date,post_date_gmt) VALUES ($new_inventory_id,'591', '', '$product_title', '', 'publish', 'closed', 'closed', '', '', 'inventory','','','',CURDATE(),CURDATE())";

//echo $sql."<br>";


if ($conn->query($sql) === TRUE) 
                               { 
             // echo $txtf = "<p>Product ID: ".$new_inventory_id." inserted into 'wp_posts'"."<br>";

              $updated_inventory = $updated_inventory + 1;
             // $new_inventory_id = $conn->$insert_id;
             // echo "<p><span style='color:green;'>new_inventory_id</span>".$new_inventory_id."<p>";
              $resp_wp_posts = "1";
              $sqlrnv = "INSERT INTO wp_rnb_inventory_product(inventory, product) VALUES ($new_inventory_id, $product_id)";
              $style = "";
                 if ($conn->query($sqlrnv) === TRUE) { $resp_rnb_inv = "1"; }
            
               
                               }
                        else     {

              $txtf =" Error Product ID: ".$new_inventory_id."  \n"; 
           
              $txtf = $txtf. "Error inserting into wp_post: " . $sql . "<br>".$conn->error."\n";
             
             // fwrite($logfile,$sql."\n");
              $txtf = $txtf. "----------------------------------------------------------------------------------------------------- \n";
              fwrite($logfile,$txtf);

              $style = "background-color";  
              $resp_wp_posts = "0";
              $resp_rnb_inv = "0";
              $error_updating_inventory = $error_updating_inventory + 1;
              $style = 'background-color: #f2f2f2;';  

                                 } 
       
    //  echo "Prod ID: ". $product_id." Inventory ID: " .$new_inventory_id. " Title: ".$product_title. "<br>";
      echo "<tr style='$style'><td>".$item_num."</td><td>".$product_id."</td><td>".$new_inventory_id."</td><td>".$product_title."</td><td style='color:red;'>".$resp_wp_posts."</td><td>".$resp_rnb_inv."</td style='color:red;'></tr>";
     
      $resp_wp_posts = "0";
      $resp_rnb_inv  = "0";
     
      $new_inventory_id = $new_inventory_id + 1;         
      $item_num = $item_num + 1;         

      $new_inventory_id =  $new_inventory_id +1;
}

      echo "</table>";

  
          echo "<br>";

          $txtf = $txtf."<b>Number of products:</b>". $products_count ."\n";
          fwrite($logfile, $txt);

              $txtf = $txtf."Products added to inventory: ".$updated_inventory."\n";
          fwrite($logfile, $txt);

          $txtf = $txtf."Errors updating inventory: ".$error_updating_inventory."\n";



          fwrite($logfile,$txtf);
          fclose($logfile);


?>

</body>
</html>



<!--
//inventory wp_posts -> post_type = inventory 
//wp_rnb_inventory_product "inventory"  "product"


/*

wp_posts
---------------------------------------------

post_author       591

post_date               2021-05-22 18:59:35
post_date_gmt           2021-05-15 17:48:37
post_content            ""
post_title              post title
post_excerpt            ""
post_status             publish
comment_status          closed
ping_status             closed

post_name               "" ??? 
guid                    "" ???
post_type                inventory

post_password           ""

to_ping                 ""
pinged                  ""
post_modified           ""
post_modified_gmt       ""
post_content_filtered   ""
post_parent              0

menu_order               0 

post_mime_type          ""
comment_count           ""

*/

 /*                  
 $sql = "INSERT INTO wp_posts(post_author, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, guid, post_type ) VALUES ('$post_author', '$post_content', '$product_title', '$post_excerpt', '$post_status', '$comment_status', '$ping_status', '$post_name', '$guid', '$post_type')";
   */

DELETE FROM `wp_posts` WHERE post_type = "inventory";
TRUNCATE TABLE wp_rnb_inventory_product;

-->


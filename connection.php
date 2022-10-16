<?php
$servername = "DATABASE SERVER NAME";
$username = "DATABASE USER NAME";
$password = "PASSWORD";
$dbname = "DATABASE NAME";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$allitems = "SELECT * FROM `inventory`;";
$items = $conn->query($allitems);
$products = array();
$i = 0;
if($items->num_rows > 0):
    while($row = $items->fetch_assoc()){
        $productData = array(
            'item_code' => $row['item_code'],
            'quantity' => $row['quantity']
        );
        $products[$i] = $productData;
        $i++;
    }
else:
    echo "No Products";
endif;
$conn->close();
?>
<?php
include('woocommerce.php');

$products = $woocommerce->get('products');

echo json_encode($products, JSON_PRETTY_PRINT);
?>




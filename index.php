<?php
include('connection.php');
include('woocommerce.php');
$data = file_get_contents('DOMAIN_NAME/products.php');

$data = json_decode($data, true);
$db_productKey = array();
$api_productKey = array();

foreach ($data as $d):
    //get product data from api
    $api_ID = 'products/'.$d['id'];
    $api_SKU = $d['sku'];
    $api_ManageStock = $d['manage_stock'];
    $api_stock_status = $d['stock_status'];
    foreach($products as $product):
        //get product data from database
        $db_SKU = $product['item_code'];
        $db_Qty = $product['quantity'];
        if($api_SKU == $db_SKU):
            //if manage stock is true
            if(!empty($api_ManageStock)):
                $update = [
                    'stock_quantity' => $db_Qty,
                ];
                //print_r($update);
                $woocommerce->put($api_ID, $update);
            else:
                echo '<p class="note">'.$api_SKU.' No stock indicated. Please allow manage stock at woocommerce setting.</p><br>';
            endif;
        endif;
    endforeach;
endforeach;

foreach($products as $product):
    array_push($db_productKey, $product['item_code']);
endforeach;
foreach($data as $d):
    array_push($api_productKey, $d['sku']);
endforeach;

?>


<style>
    table, th, td {
        border: 1px solid #cecece;
        padding: 10px;
    }
    p.note,
    span.outofstock{
        color: red;
    }
    span.instock {
        color: green;
    }
</style>

<h1>DATA COMPARISON</h1>
<p>Products found.</p>
<table>
    <tr>
        <th>REST API</th>
        <th>DATABASE</th>
    </tr>

<?php
foreach ($data as $d):
    foreach($products as $product):
        if($d['sku'] == $product['item_code']):
?>
    <tr>
        <td><?php echo $d['sku']." - ".$d['stock_quantity']; ?></td>
        <td><?php echo $product['item_code']." - ".$product['quantity']; ?></td>
    </tr>
<?php
        endif;
    endforeach;
endforeach;
?>
</table>
<hr>
<br>
<p>Products in <strong>Woocommerce</strong> Information.</p>
<table>
    <tr>
        <th>SKU</th>
        <th>NAME</th>
        <th>STATUS</th>
        <th>QTY</th>
    </tr>

<?php
foreach ($data as $d):
    $pid = $d['sku'];
    $pname = $d['name'];
    $pmstock = $d['manage_stock'];
    $pstatus = "";
    $pqty = "";
    if(!empty($pmstock)):
        $pqty = $d['stock_quantity'];
        if($pqty > 0 || $pqty != "0"):
            $pstatus = '<span class="instock">IN STOCK</span>';            
        else:
            $pstatus = '<span class="outofstock">OUT OF STOCK</span>';
        endif;
    else:
        $pqty = "Stock not indicated";
        if($d['stock_status'] == "instock"):
            $pstatus = '<span class="instock">IN STOCK</span>';
        else:
            $pstatus = '<span class="outofstock">OUT OF STOCK</span>';
        endif;

    endif;
?>
    <tr>
        <td><?php echo $pid; ?></td>
        <td><?php echo $pname; ?></td>
        <td><?php echo $pstatus; ?></td>
        <td><?php echo $pqty; ?></td>
    </tr>
<?php
endforeach;
?>
</table>
<br>
<hr>
<br>
<p>Products in <strong>Woocommerce</strong> that are <strong>NOT FOUND</strong> in <strong>Database</strong>.</p>
<table>
    <tr>
        <th>SKU</th>
        <th>QTY</th>
    </tr>

<?php
foreach ($data as $d):
    if(!in_array($d['sku'], $db_productKey)):
?>
    <tr>
        <td><?php echo $d['sku']; ?></td>
        <td><?php echo $d['stock_quantity']; ?></td>
    </tr>
<?php
    endif;
endforeach;
?>
</table>
<br>
<hr>
<br>
<p>Products in <strong>Database</strong> that are <strong>NOT FOUND</strong> in <strong>Woocommerce</strong>.</p>
<table>
    <tr>
        <th>SKU</th>
        <th>QTY</th>
    </tr>

<?php
foreach ($products as $product):
    if(!in_array($product['item_code'], $api_productKey)):
?>
    <tr>
        <td><?php echo $product['item_code']; ?></td>
        <td><?php echo $product['quantity']; ?></td>
    </tr>
<?php
    endif;
endforeach;
?>
</table>

<!-- <pre><?php //print_r($data); ?></pre>
<pre><?php //print_r($products); ?></pre> -->
<?php
require_once "connection.php";

$product_id = $_POST['product'];

// echo $product_id;
$attributes = [];
$variation_attributes = [];
foreach ($_POST as $id => $data) {
   
    if ($id != "product" && $data != "" && $data != [""]) {
        $q = "SELECT * from sears_categories_attributes where attribute LIKE $id.'_%' AND attribute_type like '%VARIATION%'";
        $result = mysqli_query($con,$q);
        if($result){

            $row = $result->fetch_assoc();
            $variation = $row[0] ?? null;

            if ($variation != null) {
            $variation_attributes[$id] = $data; 
            } else {
                $attributes[$id] = $data; 
            }
        }else{
            $attributes[$id] = $data;
        }
      }
   
}
$p_q = "SELECT * from products where product_id=$product_id";
$products = mysqli_query($con,$p_q);

$rows = $products->fetch_assoc();
if(count($rows)){

    $products = [$rows];
    $id = $products[0]['id'];
    $sears_attributes = serialize($attributes);
    $sears_variation_attributes=serialize($variation_attributes);
    // echo ;
    $upq = "UPDATE products SET sears_attributes='$sears_attributes',sears_variation_attributes='$sears_variation_attributes' where id=$id";
    $que = mysqli_query($con,$upq);
    if($que){
        $response = [
            $product_id,
            'success'=>true
        ];
    }else{
        $response = mysqli_error($con);
    }
}
echo json_encode($response);
?>
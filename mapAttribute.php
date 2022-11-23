<?php
require_once "connection.php";

$p = "SELECT products.product_id,products.title,  products.sears_attributes,products.sears_variation_attributes,
    sears_categories.name,sears_categories.class_id
        FROM products join sears_categories on sears_categories.id = products.sears_category ORDER BY product_id DESC";
$q = mysqli_query($con,$p);
              
  $product = $q->fetch_assoc();
  $product_count = count($product);
  $final = [];
  $rec = 0;
  
   while($value = $q->fetch_assoc()){
      $final[$rec]['id']             = $value['product_id'];
      $final[$rec]['products']       = $value['title'];
      $final[$rec]['sears_category'] = $value['name'];
      $final[$rec]['attributes']     = $value['sears_attributes'];
      $final[$rec]['variation_attributes']   = $value['sears_variation_attributes'];
      $final[$rec]['class_id']       = $value['class_id'];
      $rec++;                            
  }
  $response['iTotalDisplayRecords'] = $product_count;
  $response['iTotalRecords']        = $product_count;
  $response['sEcho'] = 1;
  $response['aaData'] = $final;
  
  echo json_encode($response);
?>
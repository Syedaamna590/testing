<?php
require_once "connection.php";

$class_id = $_GET['class_id'];
$product_id = $_GET['product_id'];
// $class_id = 482310;
// $product_id = 116167;
// $product_id = 113842;
$q = "SELECT attribute,attribute_type,free_text,multiple_choice,
            attribute_values from sears_categories_attributes where item_class_id = $class_id";
$result = mysqli_query($con,$q);

// $attributes = $result->fetch_assoc();


$attrs = array(); 
$q1 = "SELECT sears_attributes from products  WHERE product_id=$product_id"; 
$q1 = mysqli_query($con,$q1);
$data = $q1->fetch_assoc();

// print_r(is_null($data['sears_attributes']));
// $data = array_column($q1, 'sears_attributes');

$v = "SELECT sears_variation_attributes from products WHERE product_id=$product_id"; 
$v = mysqli_query($con,$v);
$variation_data = $v->fetch_assoc();
         
if(count($data) > 0 && !is_null($data['sears_attributes'])){
 
    $attrs = $data['sears_attributes'];
} 
if(count($variation_data) > 0 && !is_null($variation_data['sears_variation_attributes'])){
    $attrs = $attrs + $variation_data['sears_variation_attributes'];
 
} 
// var_dump(count($data) > 0 && $data[0] != false,$data) ;
$response = '<div>';
while ($value = $result->fetch_assoc()) {
    // print_r($value).'<br><br>';
    $attribute = explode('_',$value['attribute']);
    if ($value['free_text'] == "yes") {
        $val = array_key_exists($attribute[0],$attrs) ? $attrs[$attribute[0]] : "";
        $response .="<div class='form-group'><label for='".$attribute[0]."'>".$attribute[1] . '   <i> ( ' . $value['attribute_type'] . " )</i> </label><input id='".$attribute[0]."' class='form-control' type='text' name='".$attribute[0]."' value='".$val."' /></div>";
        continue;
    }elseif ($value['multiple_choice'] == "yes") {
        $response .= "<div class='form-group'><label for='".$attribute[0]."'>".$attribute[1] . '   <i> ( ' . $value['attribute_type'] . " )</i> </label><select multiple class='form-control' name='".$attribute[0]."[]' id='".$attribute[0]."'><option value='' selected>Select</option>";
        foreach(explode(',',$value['attribute_values']) as $options){
            if(array_key_exists($attribute[0],$attrs)){
                if (in_array($options,$attrs[$attribute[0]])) {
                    $response .= "<option value='".$options."' selected>".$options."</option>";
                } else {
                    $response .= "<option value='".$options."'>".$options."</option>";
                }
            } else {
                $response .= "<option value='".$options."'>".$options."</option>";
            }
        }
        $response .= "</select></div>";
        continue;    
    }else{
        $response .="<div class='form-group'><label for='".$attribute[0]."'>". $attribute[1] . '   <i> ( ' . $value['attribute_type'] . " )</i> </label><select class='form-control' name='".$attribute[0]."' id='".$attribute[0]."'><option value='' selected>Select</option>";
        foreach(explode(',',$value['attribute_values']) as $options){
            if(array_key_exists($attribute[0],$attrs)){
                if ($attrs[$attribute[0]] == $options) {
                    $response .= "<option value='".$options."' selected>".$options."</option>";
                } else {
                    $response .= "<option value='".$options."'>".$options."</option>";
                }
            } else {
                $response .= "<option value='".$options."'>".$options."</option>";
            }
        }
        $response .= "</select></div>";
        continue;    
    }
}
$response .= '</div>';
// print_r($response);
echo json_encode($response);

?>
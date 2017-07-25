<?php
session_start();
include "header.php";

 
// get the product id
$site = isset($_GET['SiteName']) ? $_GET['SiteName'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";
$name = isset($_GET['name']) ? $_GET['name'] : "";
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : "";
 
/*
 * check if the 'cart' session array was created
 * if it is NOT, create the 'cart' session array
 */
if(!isset($_SESSION['cart_items'])){
    $_SESSION['cart_items'] = array();
}
 
// check if the item is in the array, if it is, do not add
if(array_key_exists($id, $_SESSION['cart_items'])){
    // redirect to product list and tell the user it was added to cart
    header('Location: Products.php?SiteName='.$site.'&action=exists&id' . $id . '&name=' . $name);
}
 
// else, add the item to the array
else{
    $_SESSION['cart_items'][$id]=$name;
 
    // redirect to product list and tell the user it was added to cart
    header('Location: Products.php?SiteName='.$site.'&action=added&id' . $id . '&name=' . $name);
}
?>
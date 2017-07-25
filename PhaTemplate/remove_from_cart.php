<?php
session_start();
 
// get the product id
$site = isset($_GET['SiteName']) ? $_GET['SiteName'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";
$name = isset($_GET['name']) ? $_GET['name'] : "";
 
// remove the item from the array
unset($_SESSION['cart_items'][$id]);
 
// redirect to product list and tell the user it was added to cart
header('Location: cart.php?SiteName='.$site.'&action=removed&id=' . $id . '&name=' . $name);
?>
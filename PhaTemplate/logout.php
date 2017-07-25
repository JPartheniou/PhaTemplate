<?php
session_start();
include "include2.php";
$site = $_SESSION['site'];
$page = $_SESSION['home'];
$a = $_SESSION['a'];
/*if($where!='siteManagement.php' || $where!='userMaintenance.php' || $where!='productMaintenance.php' || $where!='pageMaintenance.php' || $where!='orders.php' || $where!='addButton.php' || $where!='addPage.php' || $where!='addProduct.php' || $where!='addUser.php' || $where!='editProduct.php'){
$pha=1;
}*/
session_destroy();
if($site != NULL && $page != NULL && $a==1){/*($where!='siteManagement.php' || $where!='userMaintenance.php' || $where!='productMaintenance.php' || $where!='pageMaintenance.php' || $where!='orders.php' || $where!='addButton.php' || $where!='addPage.php' || $where!='addProduct.php' || $where!='addUser.php' || $where!='editProduct.php')*/
header("Location: ../../../".$site."/".$page."");
}else{
header("Location: ../../../PhaTemplate/Home");
}
?>
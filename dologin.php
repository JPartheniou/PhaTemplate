<?php
session_start();
include "include.php";

//check that the posted field have values
if ((empty ( $_POST ['username']))|| (empty($_POST['pass']))){
	header ("Location: login.php");
	exit;
}

connectDB();

$safe_username = mysqli_real_escape_string($mysqli, $_POST ['username']);

$safe_password = mysqli_real_escape_string($mysqli, $_POST ['pass']);

$mysqlstatemnt = "select * from Users where UserName = '" . $safe_username . "' and  Password = '" .$safe_password. "'";

$result=mysqli_query($mysqli, $mysqlstatemnt) or die (mysql_error($mysqli));
//echo $mysqlstatemnt;
if (mysqli_num_rows($result)==1){
	$rows=mysqli_fetch_array($result);
	$_SESSION['id']=$rows['ID'];
	$_SESSION['user']=$rows['UserName'];
	$_SESSION['Admin']=$rows['is_Admin'];
	header("Location: index.php");
	//echo 'Welcome ' . $_SESSION['user'];
}
else{
	header ("Location: login.php");
	exit;
}
?>
<?php
session_start();
include "include.php";



//check that the posted field have values


			
connectDB();
$safe_Fname = mysqli_real_escape_string($mysqli, trim ($_POST ['Fname']));
$safe_Lname = mysqli_real_escape_string($mysqli, trim ($_POST ['Lname']));
$safe_Uname = mysqli_real_escape_string($mysqli, trim ($_POST ['Uname']));
$safe_pass = mysqli_real_escape_string($mysqli, trim ($_POST ['pass']));
$safe_pass2 = mysqli_real_escape_string($mysqli, trim ($_POST ['pass2']));
$safe_email = mysqli_real_escape_string($mysqli, trim ($_POST ['email']));

if ((empty ( $safe_Fname)) || (empty($safe_Lname)) || (empty ( $safe_Uname)) || (empty ( $safe_pass))||(empty ( $safe_email))){
	$display_html .= "You have empty fields. Be sure to fill up all the fields.";
			$_SESSION['MSG'] = $display_html;
	header ("Location: register.php");
	exit;
}


$sql2 = "select UserName, Email from Users";
$result2 = mysqli_query($mysqli, $sql2) or die(mysqli_error($mysqli));
if(mysqli_num_rows($result2)>0){
	while($rows=mysqli_fetch_array($result2)){
		$UserName2 = $rows['UserName'];
		$Email2 = $rows['Email'];

	if($safe_Uname == $UserName2){
			$display_html .= "This UserName already exists";
			$_SESSION['MSG'] = $display_html;
	header ("Location: register.php");
	exit;
	}else if($safe_email == $Email2){
	$display_html .= "This Email already exists";
	$_SESSION['MSG'] = $display_html;
	header ("Location: register.php");
	exit;
	}}}
	 if($safe_pass != $safe_pass2){
	$display_html .= "Password Confirmation Failed.";
	$_SESSION['MSG'] = $display_html;
	header ("Location: register.php");
	exit;
}else{

$mysqlstatemnt2 = "insert into Users (FirstName, LastName, UserName, Password, Email, is_Admin) values ('" .$safe_Fname. "','" .$safe_Lname. "','" .$safe_Uname. "','" .$safe_pass. "','" .$safe_email. "', '0')";
//echo $mysqlstatemnt2;
$result=mysqli_query ($mysqli, $mysqlstatemnt2) or die (mysqli_error($mysqli));
header ("Location: index.php");


}
?>
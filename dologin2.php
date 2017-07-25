<?php
session_start();
include "header.php";
$_SESSION['MSG']='';
//check that the posted field have values
if ((empty ( $_POST ['Email']))|| (empty($_POST['pass']))){
	header ("Location: ../../../login.php?SiteName=".$_SESSION['site']."&PageName=".$_SESSION['page']."");
	$_SESSION['MSG']='Email and/or Password were empty. Please enter your details again.';
	exit;
}

connectDB();

$safe_Email = mysqli_real_escape_string($mysqli, $_POST ['Email']);

$safe_password = mysqli_real_escape_string($mysqli, $_POST ['pass']);

$sitesql = "select * from Sites where SiteName = '" . $_SESSION['site'] . "'";
$siteresult=mysqli_query($mysqli, $sitesql) or die (mysql_error($mysqli));
if (mysqli_num_rows($siteresult)==1){
	$siterows=mysqli_fetch_array($siteresult);
	$siteid = $siterows['Site_ID'];
	$userid = $siterows['User_ID'];
}




$mysqlstatemnt = "select * from Users where Email = '" .$safe_Email. "' and  Password = '" .$safe_password. "'";

$result=mysqli_query($mysqli, $mysqlstatemnt) or die (mysql_error($mysqli));


//echo $mysqlstatemnt;
if (mysqli_num_rows($result)>0){
	
	while($rows=mysqli_fetch_array($result)){
	if($rows['Site_ID'] == $siteid || $rows['is_Admin'] == 1 || $rows['ID'] == $userid){
		
		$_SESSION['id']=$rows['ID'];
		$_SESSION['user']=$rows['UserName'];
		$_SESSION['Admin']=$rows['is_Admin'];
		$_SESSION['FirstName']=$rows['FirstName'];
		$_SESSION['LastName']=$rows['LastName'];
		$_SESSION['Site_ID']=$rows['Site_ID'];
		$_SESSION['Email']=$rows['Email'];
			if($_SESSION['Site_ID'] ==  1){
			header ("Location: ../../../siteManagement.php");
			exit;
			}else{
			header("Location: ../../../".$_SESSION['site']."/".$_SESSION['home']."");
			exit;
			}
	}}
	
		if($_SESSION['id']==''){
			header ("Location: ../../../login.php?SiteName=".$_SESSION['site']."&PageName=".$_SESSION['page']."");
			$_SESSION['MSG']='Email and/or Password were wrong. Please enter your details again.';
		}
	
	
}
else{
	//header ("Location: ../../../login.php?SiteName=".$_SESSION['site']."&PageName=".$_SESSION['page']."");
	header ("Location: ../../../login.php?SiteName=".$_SESSION['site']."&PageName=".$_SESSION['page']."");
	$_SESSION['MSG']='Email and/or Password were wrong. Please enter your details again.';
	exit;
	//exit;
}
?>
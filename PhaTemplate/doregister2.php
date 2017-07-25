<?php
session_start();

include "header.php";

//check that the posted field have values

$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
			

$safe_Fname = mysqli_real_escape_string($mysqli, trim ($_POST ['Fname']));
$safe_Lname = mysqli_real_escape_string($mysqli, trim ($_POST ['Lname']));
$safe_Uname = mysqli_real_escape_string($mysqli, trim ($_POST ['Uname']));
$safe_pass = mysqli_real_escape_string($mysqli, trim ($_POST ['pass']));
$safe_pass2 = mysqli_real_escape_string($mysqli, trim ($_POST ['pass2']));
$safe_email = mysqli_real_escape_string($mysqli, trim ($_POST ['email']));

if ((empty ( $safe_Fname)) || (empty($safe_Lname)) || (empty ( $safe_Uname)) || (empty ( $safe_pass))||(empty ( $safe_email))){
	$display_html .= "You have empty fields. Be sure to fill up all the fields.";
			$_SESSION['MSG'] = $display_html;
	header ("Location: ../../../register.php?SiteName=".$_SESSION['site']."&PageName=".$_SESSION['page']."");
	exit;
}

$sitesql = "select * from Sites where SiteName = '" . $_SESSION['site'] . "'";
$siteresult=mysqli_query($mysqli, $sitesql) or die (mysql_error($mysqli));
if (mysqli_num_rows($siteresult)==1){
	$siterows=mysqli_fetch_array($siteresult);
	$siteid = $siterows['Site_ID'];
}
 if($safe_pass != $safe_pass2){
	$display_html .= "Password Confirmation Failed.";
	$_SESSION['MSG'] = $display_html;
	header ("Location: ../../../register.php?SiteName=".$_SESSION['site']."&PageName=".$_SESSION['page']."");
	exit;}
		
$sql2 = "select * from Users where Site_ID = '" . $siteid . "' and Email = '" . $safe_email . "'";
$result2 = mysqli_query($mysqli, $sql2) or die(mysqli_error($mysqli));
if(mysqli_num_rows($result2)>0){
	while($rows=mysqli_fetch_array($result2)){
		$UserName2 = $rows['UserName'];
		$Email2 = $rows['Email'];
		$Site_ID2 = $rows['Site_ID'];
		
	if($safe_email == $Email2 && $Site_ID2 == $siteid){
	$display_html .= "This Email already exists";
	$_SESSION['MSG'] = $display_html;
	header ("Location: ../../../register.php?SiteName=".$_SESSION['site']."&PageName=".$_SESSION['page']."");
	exit;
	}}
	
}else{
if($siteid == 1){
	$adminrank = 2;
}else{
	$adminrank = 0;
}

$mysqlstatemnt2 = "insert into Users (FirstName, LastName, UserName, Password, Email, is_Admin, Site_ID) values ('" .$safe_Fname. "','" .$safe_Lname. "','" .$safe_Uname. "','" .$safe_pass. "','" .$safe_email. "', '" .$adminrank . "', '" .$siteid. "')";
//echo $mysqlstatemnt2;
$result=mysqli_query ($mysqli, $mysqlstatemnt2) or die (mysqli_error($mysqli));

$mysqlstatemnt3 = "select * from Users where Email = '" . $safe_email . "' and  Password = '" .$safe_pass. "' and Site_ID = '" . $siteid . "'";

$result3=mysqli_query($mysqli, $mysqlstatemnt3) or die (mysql_error($mysqli));
//echo $mysqlstatemnt;
if (mysqli_num_rows($result3)==1){
	$rows3=mysqli_fetch_array($result3);
	$_SESSION['id']=$rows3['ID'];
	$_SESSION['user']=$rows3['UserName'];
	$_SESSION['Admin']=$rows3['is_Admin'];
	$_SESSION['FirstName']=$rows3['FirstName'];
	$_SESSION['LastName']=$rows3['LastName'];
	$_SESSION['Site_ID']=$rows3['Site_ID'];
	$_SESSION['Email']=$rows3['Email'];
	if($_SESSION['Site_ID'] ==  1){
		$_SESSION['walkthrough']=1;
		
		header ("Location: ../../../siteManagement.php?");
		exit;
		}else{
	header ("Location: ../../../".$_SESSION['site']."/".$_SESSION['page']."");
	exit;
		}
	//echo 'Welcome ' . $_SESSION['user'];
}

}












?>
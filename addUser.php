<?php
session_start();
include "header.php";

$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
$page_title = "User Maintenance";
$current_page = 'Usermanagement';
if(($_SESSION['Admin']==1 || $_SESSION['id']==$User_ID) && $safe_site != ''){

?>
    <h3 style="color:red">
<?php if($_SESSION['display_html'] != ''){?>
<div class='container theme-showcase' role='main' id='msg'>
<?php if ($_SESSION['success'] == 1){?>
		<div class='alert alert-success'  align='center'>
        <?php }else{?>
        <div class='alert alert-danger'  align='center'>
        <?php }?>
		<strong><?php echo $_SESSION['display_html'];
		$_SESSION['display_html']='';
		$_SESSION['success']=0;} ?></strong>
        
		</div>
		</div>


</h3>



<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<h2> Add New User</h2>
<table class="table">
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="addUserForm">

<tr class="active">
     <td>First Name: </td>
     <td><input type="text" name="FirstName" size="50" required/></td>
</tr>	
<tr class="active">
     <td>Last Name: </td>
     <td><input type="text" name="LastName" size="50" required/></td>
</tr>
<tr class="active">
     <td>Nickname: </td>
     <td><input type="text" name="UserName" size="50" required/></td>
</tr>
<tr class="active">
     <td>Password: </td>
     <td><input type="text" name="Password" size="50" required/></td>
</tr>
<tr class="active">
     <td>Email: </td>
     <td><input type="email" name="Email" size="50" required/></td>
</tr>
 <?php if($_SESSION['Admin']==1){?>
<tr class="active">
     <td>Is Admin: </td>
     <td><select name="is_Admin">
     <option value="0">  <?php echo Normal;?> </option>
    
     <option value="1">  <?php echo Administrator;?> </option>
     
     <option value="2">  <?php echo Operator;?> </option></td>
     
</tr>
<?php }?>
<tr colspan="2"><td>
			<button TYPE="submit" name="insert" class="btn btn-success" title="Add data to the Database"><span class='glyphicon glyphicon-ok'></span> Add User</button> </td>
            <td align="right"><a class="btn btn-warning" href="../../../userMaintenance.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to User Maintenance</a></td>
</tr>
</form>
    
</table> 

</div>
<!-- CHECH IF ANYTHING WAS PRESSED : insert, delete or update -->
<?php
if (isset($_POST['insert'])) {
//This gets all the other information from the form
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$UserName = $_POST['UserName'];
$Password = $_POST['Password'];
$Email = $_POST['Email'];
$is_Admin = $_POST['is_Admin'];

if($is_Admin ==''){
$is_Admin=0;
}

 if ($FirstName=='' || $LastName =='' || $UserName =='' || $Password ==''|| $Email =='') {
        $_SESSION['display_html'] .= "Required information is missing... fill-in all fields first... ";
 }else { //Writes the information to the database
      $addUsersSqlQuery = "INSERT INTO Users (FirstName,LastName, UserName, Password, Email, is_Admin, Site_ID) 
                           VALUES ('$FirstName', '$LastName', '$UserName', '$Password', '$Email', '$is_Admin', '$Site_ID')";
      $insertResult = mysqli_query($mysqli, $addUsersSqlQuery) or 
                      die ($addUsersSqlQuery . " " .    mysqli_error($mysqli));
$_SESSION['success']=1;
    $_SESSION['display_html'] .= "User Added ";    
     unset($ID); unset($FirstName); unset($LastName); 
     unset($UserName); unset($Password);  unset($Email); unset($is_Admin);
    } //end of writing info to database 
	header ( "refresh:0;" );
} //end of i
}else{?>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<h2 align="center">The Page you try to access does not exist!</h2>
</div>
</div>
<?php }?>
<div class="container theme-showcase" role="main">
<?php
include "footer.php";
?>
</div>
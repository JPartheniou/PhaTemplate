<?php
session_start();
include "header.php";

$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
$page_title = "User Maintenance";
$current_page = 'Usermanagement';
if($_SESSION['Admin']==1 || $_SESSION['id']==$User_ID ){

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
		echo $LastNameq;
		$_SESSION['display_html']='';
		$_SESSION['success']=0;} ?></strong>
        
		</div>
		</div>


</h3>

<?php 
if (isset($_POST['delete'])) { 
    $pName = $_POST['pName'];
	$prName = $_POST['prName'];
	if($pName == $prName){
     $deleteUsersSqlQuery = "delete from Users where Email = '" . $prName . "' and Site_ID = '".$Site_ID."'";
     $deleteResult = mysqli_query($mysqli, $deleteUsersSqlQuery) or die ($deleteUsersSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= "The User with Email ".$prName." was deleted successfully!";
	$_SESSION['success']=1;}else{
		 $_SESSION['display_html'] .= "The Email of the User you tried to delete was not the same with the Email you typed.";
	}
	header ( "refresh:0;" );
	
	//$display_html .= $deleteUsersSqlQuery;
} //end of if DELETE button was clicked


if (isset($_POST['resetpass'])) { 
	$pName = $_POST['pName'];
	$prName = $_POST['prName'];
	if($pName == $prName){
		
		$sqlq = "select * from Users where Site_ID = '".$Site_ID."' and Email = '".$prName."'";
$resultq = mysqli_query($mysqli, $sqlq) or die(mysqli_error($mysqli));

if(mysqli_num_rows($resultq)>0){
	while($rowsq=mysqli_fetch_array($resultq)){
    	$LastNameq = $rowsq['LastName'];}} 

     
     $resetpassSqlQuery = "update Users set Password = '$LastNameq' where Email= '".$prName."' and Site_ID = '".$Site_ID."'";
     $resetResult = mysqli_query($mysqli, $resetpassSqlQuery) or die ($resetpassSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
    $_SESSION['success'] = 1;
	$_SESSION['display_html'] .=" The Password is reset.";}else{
	$_SESSION['display_html'] .="The Email you typed did not match the Email of the User you tried to reset his/her Password.";}
	header ( "refresh:0;" );
} //end of if DELETE button was clicked

if (isset($_POST['search'])) {
$searchcriteria = (trim($_POST['searchcriteria']) == "")?
die (header ("Location: ../../../userMaintenance.php?SiteName=".$_SESSION['site']."")):
mysqli_real_escape_string($mysqli, trim($_POST['searchcriteria']));
$safe_search = mysqli_real_escape_string($mysqli, $searchcriteria);

}

if (isset($_POST['doupdate'])) { 
     $ID = $_POST['ID'];
     $FirstName = $_POST['FirstName'];
     $LastName = $_POST['LastName'];
     $UserName = $_POST['UserName'];
     $Password = $_POST['Password'];
     $Email = $_POST['Email'];
	 $is_Admin = $_POST['is_Admin'];
	 
	 $updateUsersSqlQuery = "update Users set FirstName = '$FirstName', LastName= '$LastName',UserName ='$UserName', Password= '$Password', Email= '$Email', is_Admin= '$is_Admin' where ID = '$ID'";
    $updateResult = mysqli_query($mysqli, $updateUsersSqlQuery) or die ($updateUsersSqlQuery . " " .    mysqli_error($mysqli));
	$_SESSION['success']=1;
    $_SESSION['display_html'] .= "User " . $UserName . " Updated!";
	header ( "refresh:0;" );
	 //$display_html .= $updateUsersSqlQuery;
} //end of if doupdate button was clicked
?> 
   <div class="container theme-showcase" role="main">

      <div class="jumbotron">
      <h1 align="center"><?php echo $safe_site;?></h1>
      <h3 align="center"><a class="btn btn-large btn-success" href="../../../addUser.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-plus'></span> Add New User</a></h3>
    <h2 align="center">  Manage Existing Users</h2>
<br>
<div align="center">
<form method="POST" action="<?php PHP_SELF ?>">
	<input class="input-xlarge" name="searchcriteria" type="text" placeholder="Search Criteria.." style="height:35px;">
   <button type="submit" class="btn btn-info" name="search"><span class="glyphicon glyphicon-search"></span> Search User</button>
   
</form>
</div>
     <br />     <br />
    
<?php //DISPLAY LIST OF ALL Users
if($safe_search != ''){
	$sql = "select * from Users where Site_ID = '".$Site_ID."' and (upper(FirstName) like '%". strtoupper($safe_search) . "%' or upper(LastName) like '%" . strtoupper($safe_search) . "%' or upper(UserName) like '%" . strtoupper($safe_search) . "%' or upper(Email) like '%" . strtoupper($safe_search) . "%' )";
}else{
$sql = "select * from Users where Site_ID = '".$Site_ID."'";
}
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

if(mysqli_num_rows($result)>0){ 
?>
<table class="table" style="width:100%; max-width:100%; table-layout:fixed;">
<thead>
<td><b>First Name</b></td>
<td><b>Last Name</b></td>
<td><b>Nickname</b></td>
<td><b>Email</b></td>
<td width="30px"></td>
<?php if($_SESSION['Admin']==1){?>
<td width="100px"><b>Status</b></td>
<td width="30px"></td>
<?php }?>
<td><b>Actions</b></td>
<td width="30px"></td>
<td><b>Extra Actions</b></td>
</thead>
<tbody>
<?php while($rows=mysqli_fetch_array($result)){
    $ID = $rows['ID'];
    $FirstName = $rows['FirstName'];
    $LastName = $rows['LastName'];
	$UserName = $rows['UserName'];
	$Password = $rows['Password'];
	$Email = $rows['Email'];
	$is_Admin = $rows['is_Admin'];
        ?>
    
        <tr class="active">
        <form method="post" id="action2" action="<?php $_SERVER[PHP_SELF]?>"> 
        <?php //if update button was pressed change all plain text to textfields to enable inline editing 
            if (isset($_POST['update']) && $_POST['id']==$ID) { ?>
        <td><input type="text" name="FirstName" style="width:100%;" value="<?php echo $FirstName?>"></td>
        <td><input type="text" name="LastName" style="width:100%;" value="<?php echo $LastName?>"></td>
        <td><input type="text" name="UserName" style="width:100%;" value="<?php echo $UserName?>"></td>
        
        
        
        <td><input type="email" name="Email" style="width:100%;" value="<?php echo $Email?>"></td>
        <td width="30px"></td>
        <?php if($_SESSION['Admin']==1){?>
        <td width="150px"><select name="is_Admin"> 
              <option value="0" <?php if ($is_Admin == 0) echo "selected"; ?>>  <?php echo Normal;?> </option>
               
     <option value="1" <?php if ($is_Admin == 1) echo "selected"; ?>>  <?php echo Administrator;?> </option>
     
     <option value="2" <?php if ($is_Admin == 2) echo "selected"; ?>>  <?php echo Operator;?> </option>
     </select></td>
        <td width="30px"></td>
        <?php }?>
        <td><button type="submit" name="doupdate" class='btn btn-success' title="Save User"><span class='glyphicon glyphicon-cog'></span></button>
        <input type="hidden" name="ID" value="<?php echo $ID?>"/>
        <?php } else {  //These Users will be displayed in plain text ?>
        <td><?php echo $FirstName; ?></td>
        <td><?php echo $LastName; ?></td>
        <td><?php echo $UserName; ?></td>
        <td><?php echo $Email; ?></td>
        <td width="30px"></td>
        <?php if($_SESSION['Admin']==1){?>
        <td width="100px"><?php echo $is_Admin; ?></td>
        <td width="30px"></td>
        <?php }?>
        <td><button type="submit" name="update" class='btn btn-warning' title="Edit User"><span class='glyphicon glyphicon-cog'></span></button>
        <?php } // end of if update button was pressed ?>
        <input type="hidden" name="id" value="<?php echo $ID?>">
        
        <a data-toggle="modal" href="#myModal2" data-id="<?php echo $Email;?>" class='open-myModal2 btn btn-danger' title="Delete User"><span class='glyphicon glyphicon-trash'></span></a></td> 
        <td width="30px"></td>
        <td><a data-toggle="modal" href="#myModal3" data-id="<?php echo $Email;?>" class='open-myModal3 btn btn-warning' title="Reset Password"><span class='glyphicon glyphicon-flash'></span> Reset Password</a></td>
      </form>  
      
      </tr>
     <?php } //end of while loop that displays Users ?>
        </tbody>
	</table>
<?php } //end of if mysqli_num_rows >0 ?>
<h3 align="center"><a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a></h3>
 </div>                                                            

<?php } else {
	header( 'Location: index.php' ) ;
}?>

<?php
include "footer.php";
?>
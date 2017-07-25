<?php
session_start();
$page_title = "My Account";

include "header.php";?>

<?php
connectDB();

$uid = $_SESSION['id'];
$_SESSION['a']=1;

if (isset($_POST['changepass'])) { 
     $pName = $_POST['pName'];
	$prName = $_POST['prName'];
	$pass = $_POST['pass'];
     $passcon = $_POST['passcon'];
	if($pName == $prName && $pass==$passcon){
      
	 $updateUsersSqlQuery = "update Users set Password = '$pass' where ID = '$uid'";
    $updateResult = mysqli_query($mysqli, $updateUsersSqlQuery) or die ($updateUsersSqlQuery . " " .    mysqli_error($mysqli));
	$_SESSION['success']=1;
    $_SESSION['display_html'] .= "Your Password has been changed Successfully!";
	}else{
		$_SESSION['display_html'] .= "One or more of the fields were incorrect. Please try again.";
	}
	 //$display_html .= $updateUsersSqlQuery;
} //end of if doupdate button was clicked

if (isset($_POST['resetpass'])) { 
     $pName = $_POST['pName'];
	$prName = $_POST['prName'];
	if($pName == $prName){
      
	 $updateUsersSqlQuery = "update Users set Password = '$pName' where ID = '" . $uid . "'";
    $updateResult = mysqli_query($mysqli, $updateUsersSqlQuery) or die ($updateUsersSqlQuery . " " .    mysqli_error($mysqli));
	$_SESSION['success']=1;
    $_SESSION['display_html'] .= "Your Password has been reset Successfully! Your Password is now your Last Name!";
	}else{
		$_SESSION['display_html'] .= "The Last Name you typed was incorrect. Please try again.";
	}
	 //$display_html .= $updateUsersSqlQuery;
}




$sqlll = "SELECT LEFT(LastName, 1) as firstletter from Users where ID = '" . $uid . "'";

$resultll = mysqli_query($mysqli, $sqlll) or die(mysqli_error($mysqli));
if(mysqli_num_rows($resultll)>0){ 
 while($rowsll=mysqli_fetch_array($resultll)){
 $ll=$rowsll['firstletter'];
 }}



$sql = "select * from Users where ID = '" . $uid . "'";

$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
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
		$_SESSION['success']=0;
		} ?></strong>
        
		</div>
		</div>


</h3>

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<h2>Account Management</h2>
<?php if(mysqli_num_rows($result)>0){ 
?>
<table class="table" style="width:50%;">
<?php while($rows=mysqli_fetch_array($result)){
    $ID = $rows['ID'];
    $FirstName = $rows['FirstName'];
    $LastName = $rows['LastName'];
	$UserName = $rows['UserName'];
	$Password = $rows['Password'];
	$Email = $rows['Email'];
	$is_Admin = $rows['is_Admin'];
        ?>
<tr class="active"><td><b>First Name</b></td>
<td><?php echo $FirstName; ?></td>
</tr>
<tr class="active"><td><b>Last Name</b></td>
<td><?php echo $ll;?>******</td>
</tr>
<tr class="active"><td><b>Email</b></td>
<td><?php echo $Email; ?></td>
</tr>
<tr class="active"><td><b>Nickname</b></td>
<td><?php echo $UserName; ?></td>
</tr>
     <?php } //end of while loop that displays Users ?>
   
 <tr><td><a data-toggle="modal" href="#myModal2" data-id="<?php echo $Password;?>" class='open-myModal2 btn btn-warning' title="Change Password"><span class='glyphicon glyphicon-cog'></span> Change Password</a></td><td align="right"></td></tr>
    </table>
<?php } //end of if mysqli_num_rows >0 ?>


</div>
<?php
include_once('footer.php');
?>

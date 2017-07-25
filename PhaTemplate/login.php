<?php
session_start;
$page_title = "YoloSwag LogIn";
$current_page = "login";
include_once ('header.php');



if (isset($_POST['resetpass'])) { 
     $pName = $_POST['pName'];
	$prName = $_POST['prName'];
	$llllName = $_POST['llllName'];
	$sql2 = "select * from Users where Site_ID = '".$Site_ID."' and Email = '" .$prName. "' and LastName = '" .$llllName. "'";
$result2 = mysqli_query($mysqli, $sql2) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result2)>0){
	while($rows234=mysqli_fetch_array($result2)){
    $uid2123 = $rows234['ID'];}
	 $updateUsersSqlQuery = "update Users set Password = '$llllName' where ID = '" . $uid2123 . "'";
    $updateResult = mysqli_query($mysqli, $updateUsersSqlQuery) or die ($updateUsersSqlQuery . " " .    mysqli_error($mysqli));
	$_SESSION['success']=1;
    $_SESSION['MSG'] = "Your Password has been reset Successfully! Your Password is now your Last Name!";
}
$_SESSION['MSG'] = "The Email and/or the Last Name you Typed was wrong. Please try again.";
	
	 //$display_html .= $updateUsersSqlQuery;
}

?>


<h3 style="color:red">
<?php if($_SESSION['MSG'] != ''){?>
<div class='container theme-showcase' role='main' id='msg'>
<?php if ($_SESSION['success'] == 1){?>
		<div class='alert alert-success'  align='center'>
        <?php }else{?>
        <div class='alert alert-danger'  align='center'>
        <?php }?>
		<strong><?php echo $_SESSION['MSG']; 
		$_SESSION['MSG']='';
		$_SESSION['success']=0;
		} ?></strong>
        
		</div>
		</div>


</h3>

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      <h2 align="center">Enter Details</h2>

<form method="post" action="../../../dologin2.php?SiteName=<?php echo $safe_site?>&PageName=<?php echo $safe_page?>">
	Email: <input type="text" name="Email" id="Email"/>
    Password: <input type="password" name="pass" id="pass" />
    <button type="submit" class="btn btn-success" title="Login"><span class="glyphicon glyphicon-ok"></span> Login</button>                                       <a data-toggle="modal" href="#myModal3" data-id="<?php echo $LastName;?>" class='open-myModal3 btn btn-warning' title="Reset Password"><span class='glyphicon glyphicon-flash'></span> Reset Password</a>
    </form>

</div>
<h3>Not a member yet? Register <a href="../../../register.php?SiteName=<?php echo $safe_site?>&PageName=<?php echo $safe_page?>">Here!</a></h3>
<?php
include_once('footer.php');
?>
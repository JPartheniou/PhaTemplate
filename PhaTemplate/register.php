<?php 
session_start();
$page_title = "Register";
$current_page = "register";
include "header.php";
$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
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

<form method="post" action="doregister2.php?SiteName=<?php echo $safe_site;?>&PageName=<?php echo $safe_page;?>" onsumbit="return myFunction()">
<table class="table" style="width:50%">
<tr class="active"><td>First Name:</td><td><input type="text" name="Fname" id="Fname"/></td></tr>
<tr class="active"><td>Last Name:</td><td><input type="text" name="Lname" id="Lname"/></td></tr>
<tr class="active"><td>Email:</td><td><input type="email" name="email" id="email"/></td></tr>
<tr class="active"><td>Nickname:</td><td><input type="text" name="Uname" id="Uname"/></td></tr>
<tr class="active"><td>Password:</td><td><input type="password" name="pass" id="pass" /></td></tr>
<tr class="active"><td>Confirm Password:</td><td><input type="password" name="pass2" id="pass2" /></td></tr>
<tr hidden=""><td hidden=""></td></tr>
<tr colspan="2"><td></td><td><button type="submit" class="btn btn-success" title="Register"><span class="glyphicon glyphicon-ok"></span> Register</button></td></tr>
</table>
    </form>

</div>

<?php
include_once('footer.php');
?>

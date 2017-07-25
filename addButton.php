<?php
session_start();
include "header.php";
connectDB();
$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);


$sql10 = "select User_ID from Sites where Site_ID = '".$safe_siteid."'";

$result10 = mysqli_query($mysqli, $sql10) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result10)>0){
	while ($rows10 = mysqli_fetch_array($result10)){
		$User_ID10 = $rows10['User_ID'];
	}
}

if($_SESSION['walkthrough']==7){
	$_SESSION['display_html']='Fill in the Information, press "Add Button" and then go back to Site Management.';
		$_SESSION['success']=1;
		
}

if($_SESSION['walkthrough']==13){
	$_SESSION['display_html']='Fill in the Information, press "Add Button" and then go back to Site Management.';
		$_SESSION['success']=1;
		
}

?>

<h3 style="color:red">
<?php if($_SESSION['display_html'] != ''){?>
<div class='container theme-showcase' role='main' id='msg'>
<?php if ($_SESSION['success'] == 1){?>
		<div class='alert alert-success'  align='center'>
        <?php }else{?>
        <div class='alert alert-danger'  align='center'>
        <?php }?>
		<strong><?php echo $_SESSION['display_html'];if($_SESSION['walkthrough']==0 || $_SESSION['walkthrough']==8 || $_SESSION['walkthrough']==14){?>                  <a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a>
		<?php }$_SESSION['display_html']='';
		$_SESSION['success']=0;
		
		if($_SESSION['walkthrough']==7){
	$_SESSION['walkthrough']=8;
	$_SESSION['success']=1;
}

if($_SESSION['walkthrough']==13){
	$_SESSION['walkthrough']=14;
	$_SESSION['success']=1;
}} ?></strong>
        
		</div>
		</div>

</h3>

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<table class="table" >
<tbody>
<?php 
if($_SESSION['Admin']==1 || $User_ID == $_SESSION['id']){
?>
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="addDDButtonForm">

<h2 align="center">Add New Button</h2>
<tr class="active">
     <td>Active Button: </td>
     <td><input type="checkbox" name="ButtonActive" value="1"></td><td><em>If Active Button is selected then the button will be displayed on the navigation bar</em></td>
</tr>
<tr class="active">
     <td>Button Name: </td>
     <td><input type="text" name="ButtonName" size="50" required/></td><td><em>Button Name is the name by which, the button will be displayed on the Navigation Bar.</em></td>
</tr>
<tr class="active">
     <td>Drop Down Button: </td>
     <td><input type="checkbox" name="DDButton" value="1"></td><td><em>If Drop Down Button is selected then the button will open a drop down menu when it is pressed on the Navigation Bar.</em></td>
</tr>
<tr class="active">
<td>Page Link</td>
     <td> 
            <?php
            $PageList = "Select DISTINCT PageName from Pages where Site_ID = '".$Site_ID."'";
            $PageListResult = mysqli_query($mysqli,$PageList);
            ?>
            <select name="ButtonLink"> <?php
            if (mysqli_num_rows($PageListResult) > 0) {
                while ($data = mysqli_fetch_array($PageListResult)) {
                    $PageName8 = $data['PageName'];
                    ?>
                    <option value="<?php echo $PageName8;?>">
                    <?php echo $PageName8; ?> 
                    </option> 
                <?php
                }					
            }
            ?></select>
        </td><td><em>Page Link is the Page you will be redirected when you press the button. (If it is not a Drop Down Button)</em></td>
</tr>
<tr class="active">
     <td>Button Position: </td>
     <td><input type="number" name="ButtonPosition" size="2" required/></td><td><em>By changing the Button Position of your buttons, you change the order that they are displayed on the Navigation Bar. 1 will be the first button, 2 the second, etc.</em></td>
</tr>
<tr><td>
			<button TYPE="submit" name="insert" title="Add data to the Database" class="btn btn-success"><span class='glyphicon glyphicon-ok'></span> Add Button</button></td><td></td><td align="right"><a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a></td>
</tr>
</form>
    </tbody>
</table> 
</div>
</div>

<?php 
if (isset($_POST['insert'])) {
     $ButtonActive = $_POST['ButtonActive'];
     $ButtonName = $_POST['ButtonName'];
	 $DDButton = $_POST['DDButton'];
     $ButtonLink = $_POST['ButtonLink'];
     $ButtonPosition = $_POST['ButtonPosition'];
     
     if ($ButtonActive =='' || $ButtonName =='' || $ButtonLink == 'Select Page'){
        $_SESSION['display_html'] .= "Required information is missing... fill-in all fields first... ";
 }else { //Writes the information to the database
      $addProductsSqlQuery = "INSERT INTO Buttons (Site_ID, ButtonActive, ButtonName, DDButton, ButtonLink, ButtonPosition) 
                           VALUES ('$Site_ID', '$ButtonActive', '$ButtonName', '$DDButton', '$ButtonLink', '$ButtonPosition')";
      $insertResult = mysqli_query($mysqli, $addProductsSqlQuery) or 
                      die ($addProductsSqlQuery . " " .    mysqli_error($mysqli));
$_SESSION['success']=1;
    $_SESSION['display_html'] .= "Button Added! ";    
     
	 	unset($ButtonActive);
		unset($ButtonName);
		unset($DDButton);
		unset($ButtonLink);
		unset($ButtonPosition);
		
		header ( "refresh:0;" );
}
}
}else {
	header("Location: ../../../PhaTemplate/Home") ;
}?>
<div class="container theme-showcase" role="main">
<?php
include "footer.php";
?></div>
<?php
session_start();
include "header.php";
connectDB();
$safe_button = mysqli_real_escape_string($mysqli, $_GET['Button']);

$sql9 = "select * from Buttons where Button_ID = '".$safe_button."'";

$result9 = mysqli_query($mysqli, $sql9) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result9)>0){
	while ($rows9 = mysqli_fetch_array($result9)){
		$Site_ID9 = $rows9['Site_ID'];
		$ButtonName9 = $rows9['ButtonName'];
	}
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
		<strong><?php echo $_SESSION['display_html'];?>                  <a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a>
		<?php $_SESSION['display_html']='';
		$_SESSION['success']=0;} ?></strong>
        
		</div>
		</div>

</h3>

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<table class="table" >
<?php 
if($_SESSION['Admin']==1 || $User_ID == $_SESSION['id']){
?>
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="addDDButtonForm">
<h2 align="center"><?php echo $ButtonName9?></h2>
<h2>Add New Sub Button</h2>
<tr class="active">
     <td>Active Button: </td>
     <td><input type="checkbox" name="DDButtonActive" value="1"></td><td><em>If Active Button is selected then the button will be displayed on the Drop Down Menu.</em></td>
</tr>
<tr class="active">
     <td>Button Name: </td>
     <td><input type="text" name="DDButtonName" size="50" required/></td><td><em>Button Name is the name by which, the button will be displayed on the Drop Down Menu.</em></td>
</tr>
<tr class="active">
<td>Page Link</td>
     <td> 
            <?php
            $PageList = "Select DISTINCT PageName from Pages where Site_ID = '".$Site_ID9."'";
            $PageListResult = mysqli_query($mysqli,$PageList);
            ?>
            <select name="DDButtonLink"> <?php
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
        </td><td><em>Page Link is the Page you will be redirected when you press the button.</em></td>
</tr>
<tr class="active">
     <td>Button Position: </td>
     <td><input type="number" name="DDButtonPosition" size="2" required/></td><td><em>By changing the Button Position of your buttons, you change the order that they are displayed on the Drop Down Menu. 1 will be the first button, 2 the second, etc.</em></td>
</tr>
<tr><td>
			<button TYPE="submit" name="insert" title="Add data to the Database" class="btn btn-success"><span class='glyphicon glyphicon-ok'></span> Add DD Button</button></td><td></td><td align="right"><a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a></td>
</tr>
</form>
    
</table> 
</div>
</div>


<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      
<!--<h2 style="color:blue"> Add New Product</h2>-->
<h3><?php echo $ButtonName9?> Sub-Buttons</h3>

<table  class="table"  border="1">

<thead>
<td><b>Active</b></td>
<td><b>Button Name</b></td>
<td><b>Page Link</b></td>
<td><b>Position</b></td>
<td><b>Actions</b></td>
</thead>
<tbody>
<?php
$sql11 = "select * from DDButtons where Button_ID = '".$safe_button."' order by DDButtonPosition";
$result11 = mysqli_query($mysqli, $sql11) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result11)>0){
	while ($rows11 = mysqli_fetch_array($result11)){
		$DDButton_ID11 = $rows11['DDButton_ID'];
		$Button_ID11 = $rows11['Button_ID'];
		$Site_ID11 = $rows11['Site_ID'];
		$DDButtonActive11 = $rows11['DDButtonActive'];
		$DDButtonName11 = $rows11['DDButtonName'];
		$DDButtonLink11 = $rows11['DDButtonLink'];
		$DDButtonPosition11 = $rows11['DDButtonPosition'];
		
		?>	
		<tr class="active">
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
        <?php //if update button was pressed change all plain text to textfields to enable inline editing 
        if (isset($_POST['update']) && $_POST['DDButton_ID']==$DDButton_ID11) { ?>
        <td><input type="checkbox" name="DDButtonActive" <?php if ($DDButtonActive11 == 1){?> checked="checked" <?php } ?> value="1"></td>
        <td><input type="text" name="DDButtonName" value="<?php echo $DDButtonName11?>" required="required"></td>
        <td> 
            <?php
            $PageList = "Select DISTINCT PageName from Pages where Site_ID = '".$Site_ID9."'";
            $PageListResult = mysqli_query($mysqli,$PageList);
            ?>
            <select name="DDButtonLink"> <?php
            if (mysqli_num_rows($PageListResult) > 0) {
                while ($data = mysqli_fetch_array($PageListResult)) {
                    $PageName8 = $data['PageName'];
                    ?>
                    <option value="<?php echo $PageName8;?>"  <?php if ($DDButtonLink11 == $PageName8) echo "selected"; ?> >
                    <?php echo $PageName8; ?> 
                    </option> 
                <?php
                }					
            }
            ?></select>
        </td>
        <td><input type="number" name="DDButtonPosition" value="<?php echo $DDButtonPosition11?>" required="required"></td>
                
        <td><button type="submit" name="doupdate" class='btn btn-success'><span class='glyphicon glyphicon-cog'></span> Save Button</button>
        <input type="hidden" name="DDButton_ID" value="<?php echo $DDButton_ID11?>"> 
        <input type="hidden" name="Site_ID" value="<?php echo $Site_ID9?>"> 
        <?php } else {   ?>
        <td><?php echo $DDButtonActive11; ?></td>
        <td><?php echo $DDButtonName11; ?></td>
        <td><?php echo $DDButtonLink11; ?></td>
        <td><?php echo $DDButtonPosition11; ?></td>
             
        <td><button type="submit" name="update" class='btn btn-warning'><span class='glyphicon glyphicon-cog'></span> Edit Button</button>
        <?php } ?>
        <input type="hidden" name="DDButton_ID" value="<?php echo $DDButton_ID11?>">
        <input type="hidden" name="Site_ID" value="<?php echo $Site_ID11?>">
        <a data-toggle="modal" href="#myModal2" data-id="<?php echo $DDButtonName11;?>" class='open-myModal2 btn btn-danger'><span class='glyphicon glyphicon-trash'></span> Delete Button</a></td> 
      </form>
      <?php }?>
      </td> 
      </tr>
<?php		
}?>
	
<?php 
	
?>
</tbody>
	</table>

</div>
</div>
	
  
   
<?php 
if (isset($_POST['insert'])) {
     $DDButtonActive = $_POST['DDButtonActive'];
     $DDButtonName = $_POST['DDButtonName'];
     $DDButtonLink = $_POST['DDButtonLink'];
     $DDButtonPosition = $_POST['DDButtonPosition'];
     
     if ($DDButtonActive =='' || $DDButtonName =='' || $DDButtonLink == 'Select Page'){
        $display_html .= "Required information is missing... fill-in all fields first... ";
 }else { //Writes the information to the database
      $addProductsSqlQuery = "INSERT INTO DDButtons (Button_ID, Site_ID, DDButtonActive, DDButtonName, DDButtonLink, DDButtonPosition) 
                           VALUES ('$safe_button', '$Site_ID9', '$DDButtonActive', '$DDButtonName', '$DDButtonLink', '$DDButtonPosition')";
      $insertResult = mysqli_query($mysqli, $addProductsSqlQuery) or 
                      die ($addProductsSqlQuery . " " .    mysqli_error($mysqli));
$_SESSION['success']=1;
    $_SESSION['display_html'] .= "Button Added! ";    
     
	 	unset($DDButtonActive);
		unset($DDButtonName);
		unset($DDButtonLink);
		unset($DDButtonPosition);
		
		header ( "refresh:0;" );
}
}

if (isset($_POST['delete'])) { 

$DDButton_ID = $_POST['DDButton_ID'];
     $Button_ID = $_POST['Button_ID'];
	 $pName = $_POST['pName'];
	$prName = $_POST['prName'];
	if($pName == $prName){
     $deleteButtonSqlQuery = "delete from DDButtons where Button_ID = '" . $safe_button . "' and DDButtonName = '" . $prName . "'";
     $deleteResult = mysqli_query($mysqli, $deleteButtonSqlQuery) or die ($deleteButtonSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
	$_SESSION['display_html'] .= "Button " . $DDButtonName . " was Deleted Successfully!";
	$_SESSION['success']=1;}else{
		$_SESSION['display_html'] .="The Button Name you typed did not match the Name of the Product you tried to delete.";}
	header ( "refresh:0;" );
} //end of if DELETE button was clicked

if (isset($_POST['doupdate'])) { 
	 $DDButton_ID = $_POST['DDButton_ID'];
     $Site_ID = $_POST['Site_ID'];
     $DDButtonActive = $_POST['DDButtonActive'];
     $DDButtonName = $_POST['DDButtonName'];
     $DDButtonLink = $_POST['DDButtonLink'];
	 $DDButtonPosition = $_POST['DDButtonPosition'];
	 
     $updateButtonSqlQuery = "update DDButtons set DDButtonActive = '$DDButtonActive', DDButtonName= '$DDButtonName', DDButtonLink= '$DDButtonLink', DDButtonPosition= '$DDButtonPosition' where DDButton_ID = '$DDButton_ID'";
    $updateResult = mysqli_query($mysqli, $updateButtonSqlQuery) or die ($updateButtonSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= "Button " . $DDButtonName . " was Updated Successfully!";
	$_SESSION['success']=1;
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked
?>


<?php } else {
	header("Location: ../../../PhaTemplate/Home") ;
}?>
<div class="container theme-showcase" role="main">
<?php
include "footer.php";
?></div>
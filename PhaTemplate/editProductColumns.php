<?php
session_start();
include "header.php";
connectDB();



$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);


if($_SESSION['Admin']==1 || $User_ID == $_SESSION['id']){
$page_title = "YoloSwag Products";


if($_SESSION['walkthrough']==111){
	$_SESSION['display_html']='Check or Uncheck the columns to change the way that your Products are presented and then go back to Product Maintenance.';
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
		<strong><?php echo $_SESSION['display_html'];
		$_SESSION['display_html']='';
		if($_SESSION['walkthrough']!=111){?>
        <a class="btn btn-large btn-warning" href="../../../productMaintenance.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-right'></span>Go to Product Maintenance</a>
        <?php }
		$_SESSION['success']=0;
		
		if($_SESSION['walkthrough']==111){
	$_SESSION['walkthrough']=112;
	$_SESSION['success']=1;
}} ?></strong>
        
		</div>
		</div>

</h3>

<?php
$sql7 = "select * from Sites where SiteName = '".$safe_site."'";

$result7 = mysqli_query($mysqli, $sql7) or die (mysqli_error($mysqli));
			if(mysqli_num_rows($result7)>0){
	
	while ($rows7 = mysqli_fetch_array($result7)){
		$Site_ID7 = $rows7['Site_ID'];
		$User_ID7 = $rows7['User_ID'];
		$SiteName7 = $rows7['SiteName'];
		$Active7 = $rows7['Active'];
		$Rating7 = $rows7['Rating'];
		$Votes7 = $rows7['Votes'];
		$CreationDate7 = $rows7['CreationDate'];
		$LastUpdate7 = $rows7['LastUpdate'];}}



$sqlco = "select * from ProductColumns where Site_ID = '".$Site_ID."'";

$resultco = mysqli_query($mysqli, $sqlco) or die (mysqli_error($mysqli));?>


 
 
   
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      <?php if(mysqli_num_rows($resultco)>0){
		  while ($rowsco = mysqli_fetch_array($resultco)){
	$ActiveName = $rowsco['ActiveName'];
	$ActiveType = $rowsco['ActiveType'];
	$ActiveDescription = $rowsco['ActiveDescription'];
	$ActiveShortDesc = $rowsco['ActiveShortDesc'];
	$ActiveImage = $rowsco['ActiveImage'];
	$ActivePrice = $rowsco['ActivePrice'];
	$ActiveAvailability = $rowsco['ActiveAvailability'];
	$ActiveRating = $rowsco['ActiveRating'];
		  }
?>
<!--<h2 style="color:blue"> Add New Product</h2>-->
<h2> Edit Product Columns</h2>
<!--<table width="50%" class="boxbg all-round">-->
<table class="table" width="100%">
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="updateProductForm">

<tr class="active">
     <td>Product Name: </td>
     <td><input type="checkbox" name="ActiveName" <?php if ($ActiveName == 1){?> checked="checked" <?php } ?> value="1"></td><td width="10px">
    </td><td></td>
</tr>	
<tr class="active">
    <td>Product Type: </td>
    <td><input type="checkbox" name="ActiveType" <?php if ($ActiveType == 1){?> checked="checked" <?php } ?> value="1"></td><td width="10px">
    </td><td></td>
 </tr>
<tr class="active">
     <td>Description: </td>
     <td><input type="checkbox" name="ActiveDescription" <?php if ($ActiveDescription == 1){?> checked="checked" <?php } ?> value="1"></td><td width="10px">
    </td><td><em>This is a toggable Long Description. It will be shown when the user presses a specified button. To go to a new line type &ltbr /&gt </em></td>
</tr>	
<tr class="active">
     <td>Short Description: </td>
     <td><input type="checkbox" name="ActiveShortDesc" <?php if ($ActiveShortDesc == 1){?> checked="checked" <?php } ?> value="1"></td><td width="10px">
    </td><td><em>This is a Description of the Item. To go to a new line type &ltbr /&gt </em></td>
</tr>	
<tr class="active">
    <td>Product Image: </td>	
    <td><input type="checkbox" name="ActiveImage" <?php if ($ActiveImage == 1){?> checked="checked" <?php } ?> value="1">
    </td>
    <td></td><td></td>
</tr>
<tr class="active">            
    <td>Price: </td>
    <td><input type="checkbox" name="ActivePrice" <?php if ($ActivePrice == 1){?> checked="checked" <?php } ?> value="1"></td><td width="10px">
    </td><td><em>This is the price of the product in USD $.</em></td>
</tr>	
<tr class="active">            
    <td>Availability: </td>
    <td><input type="checkbox" name="ActiveAvailability" <?php if ($ActiveAvailability == 1){?> checked="checked" <?php } ?> value="1"></td><td width="10px">
    </td><td><em>This is the stock of this product.</em></td>
</tr>	
<tr class="active">            
    <td>Rating: </td>
    <td><input type="checkbox" name="ActiveRating" <?php if ($ActiveRating == 1){?> checked="checked" <?php } ?> value="1"></td><td width="10px">
    </td><td></td>
</tr>

<tr colspan="2"><td>

<button type="submit" name="updateproductcol"  class="btn btn-success btn-default" title="Add data to the Database"><span class="glyphicon glyphicon-ok"> Save Changes</span></button>
			 </td><td></td><td width="10px">
    </td>
			 
        <td align="right"><a href='productMaintenance.php?SiteName=<?php echo $safe_site;?>' class='btn btn-warning btn-default' width='20px;'><span class='glyphicon glyphicon-arrow-left'></span> Return to Product Maintenance</a></td>
</tr>
</form>
    </div>
</table> 
</div>
</div>
<?php
if (isset($_POST['updateproductcol'])) { 



    
	$ActiveName = $_POST['ActiveName'];
	$ActiveType = $_POST['ActiveType'];
	$ActiveDescription = $_POST['ActiveDescription'];
	$ActiveShortDesc = $_POST['ActiveShortDesc'];
	$ActiveImage = $_POST['ActiveImage'];
	$ActivePrice = $_POST['ActivePrice'];
	$ActiveAvailability = $_POST['ActiveAvailability'];
	$ActiveRating = $_POST['ActiveRating'];
     
	 
	 
	 
     $updateProductsSqlQuery = "update ProductColumns set ActiveName = '$ActiveName', ActiveType = '$ActiveType', ActiveDescription= '$ActiveDescription',ActiveShortDesc ='$ActiveShortDesc', ActiveImage= '$ActiveImage', ActivePrice= '$ActivePrice', ActiveAvailability= '$ActiveAvailability', ActiveRating= '$ActiveRating' where Site_ID = '$Site_ID7'";
    $updateResult = mysqli_query($mysqli, $updateProductsSqlQuery) or die ($updateProductsSqlQuery . " " .    mysqli_error($mysqli));
	
	 
	
			
	$_SESSION['success']=1;
    $_SESSION['display_html'] .= "Your Product Columns have been updated!";
    
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked?>


<div class='container theme-showcase' role='main'>
<?php



include "footer.php";
?>
</div>
<?php
}else{?>
	<h2 align="center">The Product you are trying to edit does not exist.</h2>
<?php }

}else{?>
	<h2 align="center">This Page does not exist.</h2>
<?php }?>
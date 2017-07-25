<?php
session_start();
include "header.php";
//connectDB();

if($_SESSION['walkthrough']==9){
	$_SESSION['display_html']='In this page you can Create, Edit and Delete your Products. Press "Create New Product" to continue.';
		$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==11){
	$_SESSION['display_html']='You have successfully created your Product(s). Now Press the "Edit Product Columns" Button to select which columns will be visible.';
		$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==112){
	$_SESSION['display_html']='You have successfully completed editing your Product Page. Go to Site Management to create your Item Button.';
		$_SESSION['success']=1;
}


?>
<script></script>

<h3 style="color:red">
<?php if($_SESSION['display_html'] != ''){?>
<div class='container theme-showcase' role='main' id='msg'>
<?php if ($_SESSION['success'] == 1){?>
		<div class='alert alert-success'  align='center'>
        <?php }else{?>
        <div class='alert alert-danger'  align='center'>
        <?php }?>
		<strong><?php echo $_SESSION['display_html']; if($_SESSION['walkthrough']==0 || $_SESSION['walkthrough']==10 || $_SESSION['walkthrough']==12 || $_SESSION['walkthrough']==112){?>
		<a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a>
		<?php }
		$_SESSION['display_html']='';
		$_SESSION['success']=0;
		if($_SESSION['walkthrough']==9){
	$_SESSION['walkthrough']=10;
	$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==11){
	$_SESSION['walkthrough']=111;
	$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==112){
	$_SESSION['walkthrough']=12;
	$_SESSION['success']=1;
}
		} ?></strong>
        
		</div>
		</div>

</h3>
<?php

$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_prod = mysqli_real_escape_string($mysqli, $_GET['Product']);

if($_SESSION['Admin']==1 || $User_ID == $_SESSION['id']){
$page_title = "YoloSwag Products";


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



?>



 
 
   


<?php

if (isset($_POST['delete'])) { 
$_SESSION['display_html']='';
	$pName = $_POST['pName'];
	$prName = $_POST['prName'];
	if($pName == $prName){
     
     $deleteProductsSqlQuery = "delete from Products where Name = '" . $prName . "' and Site_ID = '".$Site_ID7."'";
     $deleteResult = mysqli_query($mysqli, $deleteProductsSqlQuery) or die ($deleteProductsSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
	$_SESSION['display_html'] .= $prName . " was deleted successfully";
	$_SESSION['success']=1;
	}
	else{
		$_SESSION['display_html'] .="The Product Name you typed did not match the Name of the Product you tried to delete.";
		$_SESSION['success']=0;}
		header ( "refresh:0;" );
} //end of if DELETE button was clicked

if (isset($_POST['doupdate'])) { 
     $productID = $_POST['productID'];
     $Name = $_POST['Name'];
     $Type = $_POST['Type'];
     $Description = $_POST['Description'];
     $ShortDesc = $_POST['ShortDesc'];
     $Image = $_POST['Image'];
	 $Price = $_POST['Price'];
	 $Availability = $_POST['Availability'];
	 $Rating = $_POST['Rating'];
	 $Votes = $_POST['Votes'];
	 $Tags = $_POST['Tags'];
     $updateProductsSqlQuery = "update Products set Name = '$Name', Type= '$Type',Description ='$Description', ShortDesc= '$ShortDesc', Image= '$Image', Price= '$Price', Availability= '$Availability', Rating= '$Rating', Votes='$Votes', Tags='$Tags' where productID = '$productID'";
    $updateResult = mysqli_query($mysqli, $updateProductsSqlQuery) or die ($updateProductsSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= "Product " . $Name . " Updated!";
    $_SESSION['display_html']=1;
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked
?> 
    
<div class="container theme-showcase" role="main">
 <div class="jumbotron">
 <h1 align="center"><?php echo $safe_site;?></h1>
 <h3 align="center"><a class="btn btn-large btn-success" href="../../../addProduct.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-plus'></span> Create a New Product</a></h3>
    <!--<h2 style="color:blue">  Manage Existing Products</h2>-->
    <h2 align="center">Manage Products</h2>
    <h3 align="center"><a class="btn btn-large btn-warning" href="../../../editProductColumns.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-cog'></span> Edit Product Columns</a></h3>
<br/>
    
<?php //DISPLAY LIST OF ALL Products
$sql = "select * from Products where Site_ID = '".$Site_ID7."'";

$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result)>0){ 
?>
<table class="table" border="1">
<thead>
<td><b>Product Name</b></td>
<td><b>Type</b></td>
<td><b>Short Description</b></td>
<td><b>Image FileName</b></td>
<td><b>Price</b></td>
<td><b>Availability</b></td>
<td><b>Rating</b></td>
<td><b>Actions</b></td>
</thead>
<tbody>
<?php while($rows=mysqli_fetch_array($result)){
    $productID = $rows['productID'];
    $Name = $rows['Name'];
    $Type = $rows['Type'];
    $Description = $rows['Description'];
    $ShortDesc = $rows['ShortDesc'];
    $Image = $rows['Image'];
	$Price = $rows['Price'];
	$Availability = $rows['Availability'];
	$Rating = $rows['Rating'];
	$Votes = $rows['Votes'];
	$Tags = $rows['Tags'];
	if($Availability < 3 && $Availability != 0){
		$rowcolor = 'warning';
	}else if($Availability == 0){
		$rowcolor = 'danger';
	}else{
		$rowcolor = 'active';
	}
        ?>
        <tr class="<?php echo $rowcolor;?>">
        <td><?php echo $Name; ?></td>
        <td><?php echo $Type; ?></td>
        <td><?php echo $ShortDesc; ?>
        <br /><li class="information">
    <button id="button1">More Information</button>
    <ul id="Info" class="Info"><?php echo $Description?></ul></li></td>
        <td><a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox" style="display:inline-block;" width="100" height="100"><img src="../../../images/<?php echo $Image?>" width="100" height="100"></a></td>
        <td><?php echo $Price; ?></td>
        <td><?php echo $Availability; ?></td>
        <td><?php echo $Rating; ?></td>
        
        
        <td><a href='editProduct.php?SiteName=<?php echo $safe_site;?>&Product=<?php echo $productID;?>' title="Edit Product" class='btn btn-warning'><span class='glyphicon glyphicon-cog'></span></a><br/><br/>
        <?php  // end of if update button was pressed ?>
        
        
        <a data-toggle="modal" href="#myModal2" title="Delete Product" data-id="<?php echo $Name;?>" class='open-myModal2 btn btn-danger'><span class='glyphicon glyphicon-trash'></span></a></td> 
       
      </tr>
      
     <?php } //end of while loop that displays products ?>
        </tbody>
	</table>
	
	<table class="table">
	<tr>
      <td><a class="btn btn-warning" href="../../../sites.php"><span class='glyphicon glyphicon-arrow-left'></span> Go to Sites</a>
      
      <td align="right"><a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-right'></span> Go to Site Management</a></td></td>
      </tr></table>
<?php } //end of if mysqli_num_rows >0 ?>

</div>
                                              

<?php } else {
	header( 'Location: index.php' ) ;
}?>
<div class='container theme-showcase' role='main'>
<?php

include "footer.php";
?>
</div>

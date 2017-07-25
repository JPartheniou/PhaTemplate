<?php
session_start();
include "header.php";
connectDB();



$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_prod = mysqli_real_escape_string($mysqli, $_GET['Product']);

if($User_ID == $_SESSION['id']){
$page_title = "YoloSwag Products";

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
		$_SESSION['display_html']='';?>
        <a class="btn btn-large btn-warning" href="../../../productMaintenance.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-right'></span>Go to Product Maintenance</a>
        <?php
		$_SESSION['success']=0;} ?></strong>
        
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



$sql = "select * from Products where Site_ID = '".$Site_ID7."' and productID = '" . $safe_prod ."'";

$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));?>


 
 
   
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      <?php if(mysqli_num_rows($result)>0){
		  while ($rows = mysqli_fetch_array($result)){
	$productID = $rows['productID'];
	$Image = $rows['Image'];
	$pSite_ID = $rows['Site_ID'];
	$Name = $rows['Name'];
	$Type = $rows['Type'];
	$ShortDesc = $rows['ShortDesc'];
	$Desc = $rows['Description'];
	$Price = $rows['Price'];
	$Availability = $rows['Availability'];
	$Votes = $rows['Votes'];
	$Rating = $rows['Rating'];
	$Tags = $rows['Tags'];
		  }
?>
<!--<h2 style="color:blue"> Add New Product</h2>-->
<h2> Edit Product</h2>
<!--<table width="50%" class="boxbg all-round">-->
<table class="table" width="100%">
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="updateProductForm">

<tr class="active">
     <td>Product Name: </td>
     <td><input type="text" name="Name" size="50" value="<?php echo $Name;?>" required/></td><td width="10px">
    </td>
</tr>	
<tr class="active">
    <td>Product Type: </td>
    <td><input type="text" name="Type1" size="50" /> </td><td width="10px">
    </td><td> Or Select an existing Type 
        <?php 

            
            $TypeList = "Select DISTINCT Type from Products where Site_ID = '".$Site_ID7."'";
            $TypeListResult = mysqli_query($mysqli,$TypeList);
            ?>
            <select name="Type2"> <?php
            if (mysqli_num_rows($TypeListResult) > 0) {
                while ($data = mysqli_fetch_array($TypeListResult)) {
                    $TypeName = $data['Type'];
                    ?>
                    <option value="<?php echo $TypeName;?>" <?php if ($Type == $TypeName) echo "selected"; ?>>
                    <?php echo $TypeName; ?> 
                    </option> 
                <?php
                }					
            }
            ?>
    		
                </select>

    </td>
 </tr>
<tr class="active">
     <td>Description: </td>
     <td><textarea name="Description" cols="51" rows="10" required="required"><?php echo $Desc;?></textarea></td><td width="10px">
    </td><td><em>This is a toggable Long Description. It will be shown when the user presses a specified button. To go to a new line type &ltbr /&gt </em></td>
</tr>	
<tr class="active">
     <td>Short Description: </td>
     <td><textarea name="ShortDesc" cols="51" rows="10" required="required"><?php echo $ShortDesc;?></textarea></td><td width="10px">
    </td><td><em>This is a Description of the Item. To go to a new line type &ltbr /&gt </em></td>
</tr>	
<tr class="active">
    <td>Product Image: </td>	
    <td><?php if ($Image != NULL){?>
        	<a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox" style="display:inline-block;" width="100" height="100"><img src="../../../images/<?php echo $Image?>" width="500" height="500"></a>
        <?php }?></td><td width="10px">
    </td>
    <td><input type="hidden" name="size" value="3500000">
		<input type="file" name="filename" value="<?php echo $Image?>"> <br></e><em>Upload a Photo of the Product in gif or jpeg format. If the same file name is uploaded twice it will be overwritten! <br/> The Maxium size of File is 350kb. </em>
	
</tr>
<tr class="active">            
    <td>Price: </td>
    <td><input type="number" name="Price" value="<?php echo $Price?>" required/></td><td width="10px">
    </td><td><em>This is the price of the product in USD $.</em></td>
</tr>	
<tr class="active">            
    <td>Availability: </td>
    <td><input type="number" name="Availability" value="<?php echo $Availability?>" required/></td><td width="10px">
    </td><td><em>This is the stock of this product.</em></td>
</tr>	
<tr class="active">
     <td>Product Tags: </td>
     <td><input type="text" name="Tags" size="50" value="<?php echo $Tags?>" required/></td><td width="10px">
    </td><td><em>Here Type a Tag of your Product in order to be retrieved by the Search.</em></td>
</tr>	
<tr colspan="2"><td>
<input type="hidden" name="productID" value="<?php echo $productID?>"> 
<button type="submit" name="updateproduct"  class="btn btn-success btn-default" title="Add data to the Database"><span class="glyphicon glyphicon-ok"> Save Changes</span></button>
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
if (isset($_POST['updateproduct'])) { 


$target = "images/";

     $productID = $_POST['productID'];
     $Name = $_POST['Name'];
	 if($_POST['Type1']!=''){
    	 $Type = $_POST['Type1'];
	}else{
	 	$Type = $_POST['Type2'];
	 }
     $Description = $_POST['Description'];
     $ShortDesc = $_POST['ShortDesc'];
	 $info1 = basename($_FILES['filename']['name']);
		if($info1 != ''){
			$Image = $safe_site."_".$info1; 
		}
	 $target = $target . $Image;
	 $Price = $_POST['Price'];
	 $Availability = $_POST['Availability'];
	
	 $Tags = $_POST['Tags'];
	 
	 
	 
     $updateProductsSqlQuery = "update Products set Name = '$Name', Site_ID = '$Site_ID7', Type= '$Type',Description ='$Description', ShortDesc= '$ShortDesc', Image= '$Image', Price= '$Price', Availability= '$Availability', Tags='$Tags' where productID = '$productID'";
    $updateResult = mysqli_query($mysqli, $updateProductsSqlQuery) or die ($updateProductsSqlQuery . " " .    mysqli_error($mysqli));
	
	 if($info1 != ''){
			if(move_uploaded_file($_FILES['filename']['tmp_name'], $target) )
			{
				//Tells you if its all ok
				$_SESSION['display_html'] .= "The file ". basename($_FILES['filename']['name']). " has been uploaded, and your information has been added to the directory";
			}
			else {
				//Gives and error if its not
				$_SESSION['display_html'] .= "Sorry, there was a problem uploading". basename($_FILES['filename']['name']). ".";
			}
   }
	
	$_SESSION['success']=1;
    $_SESSION['display_html'] .= "Product " . $Name . " is Updated!";
    
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
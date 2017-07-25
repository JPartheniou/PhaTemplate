<?php
session_start();
include "header.php";
$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_prod = mysqli_real_escape_string($mysqli, $_GET['Product']);
//connectDB();

if($_SESSION['walkthrough']==10){
	$_SESSION['display_html']='Create your first Product filling all the fields, press "Add Product" and then go back to Product Maintenance.';
		$_SESSION['success']=1;
}?>
<script></script>

<h3 style="color:red">
<?php if($_SESSION['display_html'] != ''){?>
<div class='container theme-showcase' role='main' id='msg'>
<?php if ($_SESSION['success'] == 1){?>
		<div class='alert alert-success'  align='center'>
        <?php }else{?>
        <div class='alert alert-danger'  align='center'>
        <?php }?>
		<strong><?php echo $_SESSION['display_html'];
		$_SESSION['display_html']=''; if($_SESSION['walkthrough']==0 || $_SESSION['walkthrough']==11){?>
        <a class="btn btn-large btn-warning" href="../../../productMaintenance.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Product Maintenance</a>
        <?php }
		$_SESSION['success']=0;
		
		if($_SESSION['walkthrough']==10){
	$_SESSION['walkthrough']=11;
		$_SESSION['success']=1;
}} ?></strong>
        
		</div>
		</div>

</h3>
<?php



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



 
 
   


<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      
      <h1 align="center">Product Maintnance for <?php echo $safe_site?></h1>
<!--<h2 style="color:blue"> Add New Product</h2>-->
<h2> Add New Product</h2>
<!--<table width="50%" class="boxbg all-round">-->
<table class="table" width="100%">
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="addProductForm">

<tr class="active">
     <td>Product Name: </td>
     <td><input type="text" name="Name" size="50" required/></td><td></td>
</tr>	
<tr class="active">
    <td>Product Type: </td>
    <td><input type="text" name="Type1" size="50" required/> </td><td> Or Select an existing Type 
        <?php 

             $TypeList = "Select DISTINCT Type from Products where Site_ID = '".$Site_ID7."'";
            $TypeListResult = mysqli_query($mysqli,$TypeList);
            ?>
            <select name="Type2"> <?php
            if (mysqli_num_rows($TypeListResult) > 0) {
                while ($data = mysqli_fetch_array($TypeListResult)) {
                    $TypeName = $data['Type'];
                    ?>
                    <option value="<?php echo $Type;?>">
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
     <td><textarea name="Description" cols="51" rows="10" required="required"></textarea></td><td><em>This is a toggable Long Description. It will be shown when the user presses a specified button. To go to a new line type &ltbr /&gt </em></td>
</tr>	
<tr class="active">
     <td>Short Description: </td>
     <td><textarea name="ShortDesc" cols="51" rows="10" required="required"></textarea></td><td><em>This is a Description of the Item. To go to a new line type &ltbr /&gt </em></td>
</tr>	
<tr class="active">
    <td>Product Image: </td>	
    <td><input type="hidden" name="size" value="3500000">
		<input type="file" name="filename"> <br></e><em>Upload a Photo of the Product in gif or jpeg format. If the same file name is uploaded twice it will be overwritten! Maxium size of File is 350kb. </em>
	</td>	<td></td>
</tr>
<tr class="active">            
    <td>Price: </td>
    <td><input type="number" name="Price" required/></td><td><em>This is the price of the product in USD $.</em></td>
</tr>	
<tr class="active">            
    <td>Availability: </td>
    <td><input type="number" name="Availability" required/></td><td><em>This is the stock of this product.</em></td>
</tr>	
<tr class="active">
     <td>Product Tags: </td>
     <td><input type="text" name="Tags" size="50" required/></td>
     <td><em>Here Type a Tag of your Product in order to be retrieved by the Search.</em></td>
</tr>	
<tr colspan="2">
<br/>
<br/>
<td>
<button type="submit" name="insert"  class="btn btn-success" title="Add data to the Database"><span class="glyphicon glyphicon-ok"> Add Product</span></button>
			</td><td></td><td align="right"><a href='productMaintenance.php?SiteName=<?php echo $safe_site;?>' class='btn btn-warning btn-default' width='20px;'><span class='glyphicon glyphicon-arrow-left'></span> Return to Product Maintenance</a></td>
            
</tr>
</form>
    </div>
</table> 
</div>
</div>
<!-- CHECH IF ANYTHING WAS PRESSED : insert, delete or update -->
<?php
if (isset($_POST['insert'])) {
	$_SESSION['display_html']='';
//This gets all the other information from the form
$Name = $_POST['Name'];
if($_POST['Type1']!=''){
$Type = $_POST['Type1'];
}else{
$Type = $_POST['Type2'];
}
$Description = $_POST['Description'];
$ShortDesc = $_POST['ShortDesc'];
$Price = $_POST['Price'];
$Availability = $_POST['Availability'];
$Tags = $_POST['Tags'];
//This is the directory where images will be saved
$target = "images/";


$info1 = basename($_FILES['filename']['name']);
		if($info1 != NULL){
			$Image = $safe_site."_".$info1; 
		}
		$target = $target . $Image;

 if ($Name=='' || $Type =='' || $Description =='' || $ShortDesc ==''|| $Price ==''|| $Availability ==''){
        $_SESSION['display_html'] .= "Required information is missing... fill-in all fields first... ";
		$_SESSION['success']=0;
		header ( "refresh:0;" );
		
 }else { //Writes the information to the database
      $addProductsSqlQuery = "INSERT INTO Products (Name, Site_ID, Type, Description, ShortDesc, Image, Price, Availability, Rating, Votes, Tags) 
                           VALUES ('$Name', '$Site_ID7', '$Type', '$Description', '$ShortDesc', '$Image', '$Price', '$Availability', '3', '1', '$Tags')";
      $insertResult = mysqli_query($mysqli, $addProductsSqlQuery) or 
                      die ($addProductsSqlQuery . " " .    mysqli_error($mysqli));
	
    $_SESSION['display_html'] .= "Product Added. ";    
     unset($productID); unset($Name); unset($Type); 
     unset($Description); unset($ShortDesc);  unset($Image); unset($Price); unset($Availability); unset($Rating); unset($Votes); unset($Tags);
   
    //Writes the photo to the server
        if(move_uploaded_file($_FILES['filename']['tmp_name'], $target))
        {
        //Tells you if its all ok
        $_SESSION['display_html'] .= "The file ". basename( $_FILES['filename']['name']). " has been uploaded, and your information has been added to the directory";
		$_SESSION['success']=1;
		header ( "refresh:0;" );
        }
        else {
        //Gives and error if its not
        $_SESSION['display_html'] .= "Sorry, there was a problem uploading your file.";
		$_SESSION['success']=0;
		header ( "refresh:0;" );
        }//end move uploaded files
    } //end of writing info to database 
} //end of if INSERT button was clicked


}else{?>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      <h2 align="center">The Page you are trying to access does not exist!</h2>
      </div>
      </div>
      <?php	
}?>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
     
      <?php include 'footer.php';?>
      </div>

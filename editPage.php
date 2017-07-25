<?php
session_start();
if($_SESSION['Admin']==1){
$page_title = "YoloSwag Products";
include "include.php";
include "header.php";?>

<?php
connectDB();


?>
 
 
   
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<h1> Edit Page </h1>
</div></div>

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<!--<h2 style="color:blue"> Add New Product</h2>-->
<h2> Add New Page</h2>
<!--<table width="50%" class="boxbg all-round">-->
<table width="50%">
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="addProductForm">

<tr class="active">
     <td>Product Name: </td>
     <td><input type="text" name="Name" size="50" required/></td>
</tr>	
<tr class="active">
    <td>Product Type: </td>
    <td>
        <?php

            $sql = 'Select DISTINCT Type from Products order by Type';
            $result2 = mysqli_query($mysqli,$sql);
            ?>
            <select name="Type"> <?php
            if (mysqli_num_rows($result2) > 0) {

                while ($data = mysqli_fetch_array($result2)) {
                            
                            $type = $data['Type']; ?>
                            <option value="<?php echo $type; ?>">  <?php echo $type; ?> </option> <?php
                }					
            }
    ?>		
                </select>
    </td>
 </tr>
<tr class="active">
     <td>Description: </td>
     <td><input type="text" name="Description" size="50" required/></td>
</tr>	
<tr class="active">
     <td>Short Description: </td>
     <td><input type="text" name="ShortDesc" size="50" required/></td>
</tr>	
<tr class="active">
    <td>Product Image: </td>	
    <td><input type="hidden" name="size" value="3500000">
		<input type="file" name="filename"> <br></e><em>Upload a Photo of the Product in gif or jpeg format. If the same file name is uploaded twice it will be overwritten! Maxium size of File is 350kb. </em>
	</td>	
</tr>
<tr class="active">            
    <td>Price: </td>
    <td><input type="number" name="Price"/></td>
</tr>	
<tr class="active">            
    <td>Availability: </td>
    <td><input type="number" name="Availability"/></td>
</tr>	
<tr class="active">
     <td>Product Tags: </td>
     <td><input type="text" name="Tags" size="50" required/></td>
</tr>	
<tr colspan="2"><td>
			<input TYPE="submit" name="insert" title="Add data to the Database" value="Add Product"/> </td>
</tr>
</form>
    </div>
</table> 
</div>
</div>
<!-- CHECH IF ANYTHING WAS PRESSED : insert, delete or update -->
<?php
if (isset($_POST['insert'])) {
//This gets all the other information from the form
$Name = $_POST['Name'];
$Type = $_POST['Type'];
$Description = $_POST['Description'];
$ShortDesc = $_POST['ShortDesc'];
$Price = $_POST['Price'];
$Availability = $_POST['Availability'];
$Tags = $_POST['Tags'];
//This is the directory where images will be saved
$target = "images/";
$target = $target . basename( $_FILES['filename']['name']);
$Image = ($_FILES['filename']['name']);

 if ($Name=='' || $Type =='' || $Description =='' || $ShortDesc ==''|| $Price ==''|| $Availability ==''|| $Image =='')
        $display_html .= "Required information is missing... fill-in all fields first... ";
 else { //Writes the information to the database
      $addProductsSqlQuery = "INSERT INTO Products (Name,Type, Description, ShortDesc, Image, Price, Availability, Rating, Votes, Tags) 
                           VALUES ('$Name', '$Type', '$Description', '$ShortDesc', '$Image', '$Price', '$Availability', '3', '1', '$Tags')";
      $insertResult = mysqli_query($mysqli, $addProductsSqlQuery) or 
                      die ($addProductsSqlQuery . " " .    mysqli_error($mysqli));

    $display_html .= "Product Added ";    
     unset($productID); unset($Name); unset($Type); 
     unset($Description); unset($ShortDesc);  unset($Image); unset($Price); unset($Availability); unset($Rating); unset($Votes); unset($Tags);
   
    //Writes the photo to the server
        if(move_uploaded_file($_FILES['filename']['tmp_name'], $target))
        {
        //Tells you if its all ok
        $display_html .= "The file ". basename( $_FILES['filename']['name']). " has been uploaded, and your information has been added to the directory";
        }
        else {
        //Gives and error if its not
        $display_html .= "Sorry, there was a problem uploading your file.";
        }//end move uploaded files
    } //end of writing info to database 
} //end of if INSERT button was clicked

if (isset($_POST['delete'])) { 
     $productID = $_POST['id'];
     $deleteProductsSqlQuery = "delete from Products where productID = '" . $productID . "'";
     $deleteResult = mysqli_query($mysqli, $deleteProductsSqlQuery) or die ($deleteProductsSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
	$display_html .= $deleteProductsSqlQuery;
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
    $display_html .= "Product " . $Name . " Updated!";
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked
?> 
    
<br>
<hr>
 <div class="jumbotron">
    <!--<h2 style="color:blue">  Manage Existing Products</h2>-->
    <h2>  Manage Existing Products</h2>
<br>
    
<?php //DISPLAY LIST OF ALL Products
$sql = "select * from Products";
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

if(mysqli_num_rows($result)>0){ 
?>
<table class="table"  border="1">
<thead>
<td><b>Product Name</b></td>
<td><b>Type</b></td>
<td><b>Description</b></td>
<td><b>Short Description</b></td>
<td><b>Image FileName</b></td>
<td><b>Price</b></td>
<td><b>Availability</b></td>
<td><b>Rating</b></td>
<td><b>Votes</b></td>
<td><b>Tags</b></td>
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
        ?>
        <tr class="active">
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
        <?php //if update button was pressed change all plain text to textfields to enable inline editing 
            if (isset($_POST['update']) && $_POST['id']==$productID) { ?>
        <td><input type="text" name="Name" value="<?php echo $Name?>"></td>
        <td> 
            <?php
            $TypeList = 'Select DISTINCT Type from Products order by Type';
            $TypeListResult = mysqli_query($mysqli,$TypeList);
            ?>
            <select name="Type"> <?php
            if (mysqli_num_rows($TypeListResult) > 0) {
                while ($data = mysqli_fetch_array($TypeListResult)) {
                    $Type = $data['Type'];
                    ?>
                    <option value="<?php echo $Type;?>"  <?php if ($Type == $Type) echo "selected"; ?> >
                    <?php echo $Type; ?> 
                    </option> 
                <?php
                }					
            }
            ?></select>
        </td>
        <td><input type="text" name="Description" value="<?php echo $Description?>"></td>
        <td><input type="text" name="ShortDesc" value="<?php echo $ShortDesc?>"></td>
        <td><input type="text" name="Image" value="<?php echo $Image?>"> </td>
        <td><input type="number" name="Price" value="<?php echo $Price?>"></td>
        <td><input type="number" name="Availability" value="<?php echo $Availability?>"></td>
        <td><input type="number" name="Rating" value="<?php echo $Rating?>"></td>
        <td><input type="number" name="Votes" value="<?php echo $Votes?>"></td>
        <td><input type="text" name="Tags" value="<?php echo $Tags?>"> </td>
        
        <td><input type="submit" name="doupdate" value="save"/>
        <input type="hidden" name="productID" value="<?php echo $productID?>"> 
        <?php } else {  //These products will be displayed in plain text ?>
        <td><?php echo $Name; ?></td>
        <td><?php echo $Type; ?></td>
        <td><?php echo $Description; ?></td>
        <td><?php echo $ShortDesc; ?></td>
        <td> <img src="images/<?php echo $Image?>" width="150" height="150"></td>
        <td><?php echo $Price; ?></td>
        <td><?php echo $Availability; ?></td>
        <td><?php echo $Rating; ?></td>
        <td><?php echo $Votes; ?></td>
        <td><?php echo $Tags; ?></td>
        
        <td><input type="submit" name="update" value="update"/>
        <?php } // end of if update button was pressed ?>
        <input type="hidden" name="id" value="<?php echo $productID?>">
        <input type="submit" name="delete" value="delete"/></td> 
      </form>  
      </tr>
     <?php } //end of while loop that displays products ?>
        </tbody>
	</table>
<?php } //end of if mysqli_num_rows >0 ?>

</div>
                                              
<h3 style="color:red">
System Messages :
<?php echo $display_html ?>
</h3>
<?php } else {
	header( 'Location: index.php' ) ;
}?>

<?php
include "footer.php";
?>
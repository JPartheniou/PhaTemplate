<?php
session_start();
$page_title = "YoloSwag Products";
include "include.php";
include "header.php";?>
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/star-rating.js" type="text/javascript"></script>
<?php
connectDB();


            
        if (isset($_POST['rate'])) {   
             $productID = $_POST['productID'];
            
           if (isset($_SESSION["ProductRated"][$productID]))  {
              $display_html = "You have already rated this Product!";
            } else {
            $_SESSION['ProductRated'][$productID]=1;
             //setcookie("course", $id , time()+3600*24); //cookie expires in 24 hours
             //echo $HTTP_COOKIE_VARS["course"];
             $userRate = $_POST['userRate'];
             $updateProductsSqlQuery = "update Products set Votes=Votes+1, Rating = Rating + $userRate where productID = $productID";
            $updateResult = mysqli_query($mysqli, $updateProductsSqlQuery) or die ($updateProductsSqlQuery . " " .    mysqli_error($mysqli));
            $display_html .= "Product Rated!";
           }
        } //end of if doupdate button was clicked



$sql = "select * from Products where Type = 'Motherboard' order by '$ratings'";

$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));?>

<table class="table table-striped">
<thead>
<tr class="active"><th>Image</th><th>Product Name</th><th>Category</th><th>Description</th><th>Price</th></tr>
</thead>
    <tbody>
    <?php
if(mysqli_num_rows($result)>0){
	while ($rows = mysqli_fetch_array($result)){
	$productID = $rows['productID'];
	$Image = $rows['Image'];
	$Name = $rows['Name'];
	$Type = $rows['Type'];
	$ShortDesc = $rows['ShortDesc'];
	$Price = $rows['Price'];
	$Votes = $rows['Votes'];
	$Rating = $rows['Rating'];
	$ratings = round($Rating/$Votes);
	?>
    
    <tr class="active"><td><img src="images/<?php echo $Image?>" width="150" height="150"></td><td><?php echo $Name;?></td><td><?php echo $Type;?></td><td><?php echo $ShortDesc;?></td><td><?php echo $Price;?></td><td><form method="POST" action="<?php PHP_SELF ?>">
                <?php  if (isset($_SESSION["ProductRated"][$productID])) { ?>
                 <input name="userRate" type="number" class="rating" data-size="xs" min="1" max="5" step="1" value="<?php echo $ratings ?>" disabled=true>
                <?php } else { ?>
                 <input name="userRate" type="number" class="rating" data-size="xs" min="1" max="5" step="1" value="<?php echo $ratings ?>">
                 <button name="rate" class="btn btn-primary btn-xs">Submit</button> 
                  <?php } ?>
                 <input type="hidden" name="productID" value="<?php echo $productID; ?>">
        </form>
        </td></tr>
    
    
    <?php
	}
}?>
</tbody>
	</table>
<?php
include "footer.php";
?>
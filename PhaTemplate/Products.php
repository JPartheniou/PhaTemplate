<?php
session_start();
$page_title = "Products";

include "header.php";?>


<?php
connectDB();

$_SESSION['a']=1;
$action = isset($_GET['action']) ? $_GET['action'] : "";
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : "1";
$name = isset($_GET['name']) ? $_GET['name'] : "";


$safe_sitep = mysqli_real_escape_string($mysqli, $_GET['SiteName']);

$sqlsite = "select * from Sites where SiteName = '".$safe_sitep."'";
$resultsite = mysqli_query($mysqli, $sqlsite) or die (mysqli_error($mysqli));

if(mysqli_num_rows($resultsite)>0){
	
	while ($rowssite = mysqli_fetch_array($resultsite)){
		$Site_IDp = $rowssite['Site_ID'];
		$User_IDp = $rowssite['User_ID'];
		$SiteNamep = $rowssite['SiteName'];
		$Activep = $rowssite['Active'];
		$Ratingp = $rowssite['Rating'];
		$Votesp = $rowssite['Votes'];
		$CreationDatep = $rowssite['CreationDate'];
		$LastUpdatep = $rowssite['LastUpdate'];

	}
}
$sqlcol = "select * from ProductColumns where Site_ID = '".$Site_IDp."'";
$resultcol = mysqli_query($mysqli, $sqlcol) or die (mysqli_error($mysqli));

if(mysqli_num_rows($resultcol)>0){
	
	while ($rowscol = mysqli_fetch_array($resultcol)){
		$ActiveImage = $rowscol['ActiveImage'];
		$ActiveName = $rowscol['ActiveName'];
		$ActiveType = $rowscol['ActiveType'];
		$ActiveShortDesc = $rowscol['ActiveShortDesc'];
		$ActiveDesc = $rowscol['ActiveDescription'];
		$ActivePrice = $rowscol['ActivePrice'];
		$ActiveAvailability = $rowscol['ActiveAvailability'];
		$ActiveRating = $rowscol['ActiveRating'];

	}
}


if (isset($_POST['search'])) {
$searchcriteria = (trim($_POST['searchcriteria']) == "")?
die (header ("Location: ../../../Products.php?SiteName=".$_SESSION['site']."")):
mysqli_real_escape_string($mysqli, trim($_POST['searchcriteria']));
$safe_search = mysqli_real_escape_string($mysqli, $searchcriteria);

}
            if($action=='added'){
				echo "<div class='container theme-showcase' role='main'>";
    echo "<div class='alert alert-info'  align='center'>";
        echo "<strong>{$name}</strong> was added to your cart! <a href='cart.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-success'><span class='glyphicon glyphicon-shopping-cart'></span>View cart</a>"; ?>                                <a href='cart.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-success'><span class='glyphicon glyphicon-shopping-cart'></span>View cart</a>
<?php
    echo "</div>";
	 echo "</div>";
}
 
if($action=='exists'){
	echo "<div class='container theme-showcase' role='main'>";
    echo "<div class='alert alert-info' align='center'>";
        echo "<strong>{$name}</strong> already exists in your cart! <a href='cart.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-success'><span class='glyphicon glyphicon-shopping-cart'></span>View cart</a>"; ?>                                <a href='cart.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-success'><span class='glyphicon glyphicon-shopping-cart'></span>View cart</a> 
    <?php echo "</div>";
	 echo "</div>";
}
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
		
		if (isset($_POST['AddtoCart'])) {
//This gets all the other information from the form
		$pproductID = $_POST['pproductID'];
		$ppAvailability = $_POST['pAvailability'];
		$pUserID = $_SESSION['id'];
		
		$CorderselectQuery1 = "select * from Corders where Site_ID = '".$Site_IDp."' and User_ID = '".$pUserID."' and Status = '0'";
		$CselectResult1 = mysqli_query($mysqli, $CorderselectQuery1) or die ($CorderselectQuery1 . " " .    mysqli_error($mysqli));
				if(mysqli_num_rows($CselectResult1)>0){
					while($Crows1 = mysqli_fetch_array($CselectResult1)){
						$Order_ID = $Crows1['Order_ID'];
					}}else{
		$CorderinsertQuery = "INSERT INTO Corders (Site_ID, User_ID, Total, Status, Date) VALUES ('$Site_IDp', '$pUserID', '0', '0', NOW())";
		$CinsertResult = mysqli_query($mysqli, $CorderinsertQuery) or die ($CorderinsertQuery . " " .    mysqli_error($mysqli));
				$CorderselectQuery2 = "select * from Corders where Site_ID = '".$Site_IDp."' and User_ID = '".$pUserID."' and Status = '0'";
		$CselectResult2 = mysqli_query($mysqli, $CorderselectQuery2) or die ($CorderselectQuery2 . " " .    mysqli_error($mysqli));
				if(mysqli_num_rows($CselectResult2)>0){
					while($Crows2 = mysqli_fetch_array($CselectResult2)){
						$Order_ID = $Crows2['Order_ID'];
					}}
					}
		
		$orderselectQuery = "select * from Orders where ProductID = '".$pproductID."' and Site_ID = '".$Site_IDp."' and User_ID = '".$pUserID."' and Order_ID = '$Order_ID'";
		$selectResult = mysqli_query($mysqli, $orderselectQuery) or 
                      die ($orderselectQuery . " " .    mysqli_error($mysqli));
				if(mysqli_num_rows($selectResult)>0){
					while($rows48 = mysqli_fetch_array($selectResult)){
						$pQuantity = $rows48['Quantity'];
					}
					if($pQuantity<$ppAvailability){
					$orderinsertQuery = "update Orders set Quantity=Quantity+1 where ProductID = '".$pproductID."' and Site_ID = '".$Site_IDp."' and User_ID = '".$pUserID."' and Order_ID = '$Order_ID'";
		$insertResult = mysqli_query($mysqli, $orderinsertQuery) or 
                      die ($orderinsertQuery . " " .    mysqli_error($mysqli));
					  ?>
                      <div class='container theme-showcase' role='main'>
		<div class='alert alert-info'  align='center'>
		<strong><?php echo $_POST['pName'];?></strong> was added to your cart!                                                                       <a href='cart.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-success'><span class='glyphicon glyphicon-shopping-cart'></span> View cart</a>
		</div>
		</div>
        <?php }else{?>
						  <div class='container theme-showcase' role='main'>
		<div class='alert alert-danger'  align='center'>
		<strong>We only have <?php echo $ppAvailability;?> units of <?php echo $_POST['pName'];?> in stock.</strong>
		</div>
		</div>
        <?php }
				}else{
		$orderinsertQuery = "INSERT INTO Orders (ProductID, Site_ID, Quantity, User_ID, Order_ID) VALUES ('$pproductID', '$Site_IDp', '1', '$pUserID', '$Order_ID')";
		$insertResult = mysqli_query($mysqli, $orderinsertQuery) or 
                      die ($orderinsertQuery . " " .    mysqli_error($mysqli));
					  
					  ?> <div class='container theme-showcase' role='main'>
		<div class='alert alert-info'  align='center'>
		<strong><?php echo $_POST['pName'];?></strong> was added to your cart!                                                                       <a href='cart.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-success'><span class='glyphicon glyphicon-shopping-cart'></span> View cart</a>
		</div>
		</div> <?php }?>
		
		<?php }?>
        <div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      
		<div align="center">
<form method="POST" action="<?php PHP_SELF ?>">
	<input class="input-xlarge" name="searchcriteria" type="text" placeholder="Search Criteria.." style="height:35px;">
   <button type="submit" class="btn btn-info" name="search"><span class="glyphicon glyphicon-search"></span> Search Product</button>
   
</form>
</div>
     <br />     <br />
		<?php 
		
$uid = $_SESSION['id'];

$cat = $_GET["Type"];
if($cat==''){
	if($safe_search!=''){
		$sql = "select * from Products  where Site_ID = '".$Site_IDp."' and (upper(Name) like '%". strtoupper($safe_search) . "%' or upper(ShortDesc) like '%" . strtoupper($safe_search) . "%' or upper(Type) like '%" . strtoupper($safe_search) . "%' or upper(Tags) like '%" . strtoupper($safe_search) . "%' ) order by Rating/Votes";
		}else{
$sql = "select * from Products  where Site_ID = '".$Site_IDp."' order by Type";
}}
else{
$sql = "select * from Products where Site_ID = '".$Site_IDp."' and Type = '" . $cat ."' order by '$ratings'";
}
$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));?>

<?php 

if(mysqli_num_rows($result)>0){?>      
<table class="table">
<thead>
<tr class="active"><?php if($ActiveImage==1){?><th>Image</th><?php }if($ActiveName==1){?><th>Product Name</th><?php }if($ActiveType==1){?><th>Category</th><?php }if($ActiveShortDesc==1){?><th>Description</th><?php }if($ActivePrice==1){?><th>Price (USD)</th><?php }if($ActiveAvailability==1){?><th>Availability</th><?php }if($ActiveRating==1){?><th>Rating</th><?php }if ((isset($_SESSION['user']) && $_SESSION['Site_ID'] == $Site_ID && $Ecommerce==1) || $_SESSION['id']==$User_ID && $Ecommerce==1) { ?><th>Action</th><?php }?></tr>
</thead>
    <tbody>
    <?php

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
	
	$ratings = round($Rating/$Votes);
	?>
    
    <tr class="active"><?php if($ActiveImage==1){?><td><a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox"><img src="images/<?php echo $Image?>" width="100" height="100"></a></td><?php }if($ActiveName==1){?>
    <td><?php echo $Name;?></td>
    <?php }if($ActiveType==1){?>
    <td><?php echo $Type;?></td>
    <?php }if($ActiveShortDesc==1){?>
    <td><?php echo $ShortDesc;?>
    <?php }if($ActiveDesc==1){?>
    <br /><li class="information">
    <button id="button1">More Information</button>
    <ul id="Info" class="Info"><?php echo $Desc?></ul></li></td>
    <?php }if($ActivePrice==1){?>
    <td>$<?php echo $Price;?></td>
    <?php }if($ActiveAvailability==1){?>
    <td><?php echo $Availability;?>
    <?php }if($ActiveRating==1){?>
    <td><form method="POST" action="<?php PHP_SELF ?>">
                <?php  if ((isset($_SESSION["ProductRated"][$productID])) || $uid == null) { ?>
                 <input name="userRate" type="number" class="rating" data-size="xs" min="1" max="5" step="1" value="<?php echo $ratings ?>" disabled=true>
                <?php } else { ?>
                 <input name="userRate" type="number" class="rating" data-size="xs" min="1" max="5" step="1" value="<?php echo $ratings ?>">
                 <?php /*?><?php if ($uid == 'null') ?><?php */?>
                 <button name="rate" class="btn btn-primary btn-xs"><span style="display: none;" class="glyphicon glyphicon-star-empty"></span> Rate</button> 
                 
                  <?php } ?>
                  
                 <input type="hidden" name="productID" value="<?php echo $productID; ?>">
        </form>
        </td><?php }
		if ((isset($_SESSION['user']) && $_SESSION['Site_ID'] == $Site_ID && $Ecommerce==1) || $_SESSION['id']==$User_ID && $Ecommerce==1) { ?>
        <td><?php if($Availability>0){?><form method="POST" action="<?php PHP_SELF ?>">
                 <button name="AddtoCart" class="btn btn-success"><span class='glyphicon glyphicon-shopping-cart'></span> Add to cart</button>
                 <input type="hidden" name="pproductID" value="<?php echo $productID; ?>">
                 <input type="hidden" name="pName" value="<?php echo $Name; ?>">
                 <input type="hidden" name="pAvailability" value="<?php echo $Availability; ?>">
                 <input type="hidden" name="pPrice" value="<?php echo $Price; ?>">
        </form>
       <?php } else { ?>
       <div style="color:#F00;">Out of Stock!</div>
       <?php }?>
        </td></tr>
    
    
    <?php }
	}
?>
</tbody>
	</table>
    <?php }else{?>
    <h3>The page you are looking for does not exist.</h3>
    
    <?php }?>
</div>
  <div class="container theme-showcase" role="main">  
<?php
include "footer.php";
?>
</div>
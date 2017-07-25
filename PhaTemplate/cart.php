<?php
session_start();
$sas = $_GET['SiteName'];
$page_title= $sas." Cart";
$current_page = "Cart";

include 'header.php';
connectDB();
$_SESSION['a']=1;
if ($Ecommerce==1){
$safe_sitep = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$action = isset($_GET['action']) ? $_GET['action'] : "";
$name = isset($_GET['name']) ? $_GET['name'] : "";

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

$sqlsite123 = "select MAX(Order_ID) AS LatestOrderID FROM Orders where Site_ID = '".$Site_IDp."' and User_ID = '".$_SESSION['id']."'";
$resultsite123 = mysqli_query($mysqli, $sqlsite123) or die (mysqli_error($mysqli));
if(mysqli_num_rows($resultsite123)>0){
	while ($rowssite123 = mysqli_fetch_array($resultsite123)){
		$LatestOrderID = $rowssite123['LatestOrderID'];
		$_SESSION['LatestOrderID'] = $LatestOrderID;
		}
}

 /*$query1 = "SELECT * FROM Orders WHERE User_ID = '".$_SESSION['id']."' and Status = '0' and Site_ID = '".$Site_IDp."'";
 
$res1 = mysqli_query($mysqli, $query1) or die (mysqli_error($mysqli));
*/
$query2 = "SELECT o.productID as product_id, Image, Name, Price , Quantity FROM Products p join Orders o on o.productID = p.productID join Corders co on co.Order_ID = o.Order_ID WHERE o.User_ID = '".$_SESSION['id']."' and o.Site_ID = '".$Site_IDp."' and co.Status = '0' and co.Order_ID = '".$LatestOrderID."'";
$res2 = mysqli_query($mysqli, $query2) or die (mysqli_error($mysqli));
		
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
		$_SESSION['success']=0;} ?></strong>
        
		</div>
		</div>

</h3>

<div class='container theme-showcase' role='main'>
<?php if(mysqli_num_rows($res2)>0){
	?>
   <table class='table table-hover table-responsive table-bordered'>
        <tr class="active">
			<th>Image</th>
          	<th class='textAlignLeft'>Product Name</th>
            <th>Price/Unit (USD)</th>
			<th>Quantity</th>
			<th>Price (USD)</th>
            <th>Action</th>
        </tr><?php
		
		$nproducts= 0;
		$total_price=0;
		
		
		while ($row2 = mysqli_fetch_array($res2)){
			$pName = $row2['Name'];
			$Price = $row2['Price'];
			$Image = $row2['Image'];
			$Quantity = $row2['Quantity'];
			$pproductID = $row2['product_id'];
        
         ?>
 
            <tr class="active">
            <td><a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox"><img src="images/<?php echo $Image?>" width="100" height="100"></a></td>
                <td><?php echo $pName;?></td>
                <td><?php echo "$".$Price."";?></td>
                <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>">
                <td><input size="5" type="text" name="pQuantity" value="<?php echo $Quantity?>"> <input type="submit" name="doupdate" value="Update Cart"/>
        <input type="hidden" name="pproductID" value="<?php echo $pproductID?>"></td>
                <td><?php $finalPrice = $Price*$Quantity; echo "$".$finalPrice."";?></td>
                <td><button type="submit" name="delete" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Remove from cart</button><input type="hidden" name="pproductID" value="<?php echo $pproductID?>"><input type="hidden" name="ppName" value="<?php echo $pName?>">
                </td>
                </form>
            <tr class="active">
  <?php 
            $total_price= $total_price + $finalPrice;
			$nproducts=$nproducts+$Quantity;
			
			$CorderupdateQuery = "update Corders set Total = '$total_price', Date = NOW() where Order_ID = '$LatestOrderID'";
		$CordersupdateResult = mysqli_query($mysqli, $CorderupdateQuery) or die ($CorderupdateQuery . " " .    mysqli_error($mysqli));
        //}
		
		}?>
        <tr class="active">
             <td><a href='../../../Products.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-info'><span class='glyphicon glyphicon-arrow-left'></span> Continue Shopping</a></td>
             <td></td>
             <td></td>
                <td><b>Total</b></td>
                <td><?php echo "$".$total_price."";?></td>
                <td>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="puzzle_john@hotmail.com">
    <input type="hidden" name="item_name" value="<?php echo $SiteNamep;?> Products">
    <input type="hidden" name="item_number" value="<?php echo $nproducts;?>">
    <input type="hidden" name="amount" value="<?php echo $total_price;?>">
    <input type="hidden" name="no_shipping" value="0">
    <input type="hidden" name="no_note" value="<?php echo $nproducts;?>">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="lc" value="AU">
    <input type="hidden" name="bn" value="PP-BuyNowBF">
    <input type="hidden" name="return" value="partheniou.students.acg.edu/successpay.php?SiteName=<?php echo $_SESSION['site'];?>&Order=<?php echo $_SESSION['LatestOrderID'];?>">
    <input type="submit" class='btn btn-success' span class='glyphicon glyphicon-shopping-cart' name="submit" value="Checkout" alt="PayPal - The safer, easier way to pay online.">
    </form>
    
    <br/>
                <?php if($_SESSION['id']==$User_ID){?>
                <a href='../../../successpay.php?SiteName=<?php echo $_SESSION['site'];?>&Order=<?php echo $_SESSION['LatestOrderID'];?>' class='btn btn-info'><span class='glyphicon glyphicon-search'></span> View Page after Payment</a>
				<?php }?>
                 <!--<a href='#' class='btn btn-success'>
                 <span class='glyphicon glyphicon-shopping-cart'></span> Checkout</a>-->
                </td>
            </tr>
 
    </table>
	<?php
	}
 
else{?>
	<div class='container theme-showcase' role='main'>
    <div class='alert alert-danger'>
        <strong>No products found</strong> in your cart!
    </div>
	</div>
    <?php
}

if (isset($_POST['delete'])) { 
     $pproductID = $_POST['pproductID'];
     $deleteOrderSqlQuery = "delete from Orders where productID = '$pproductID' and User_ID = '".$_SESSION['id']."' and Order_ID = '".$LatestOrderID."'";
     $deleteResult = mysqli_query($mysqli, $deleteOrderSqlQuery) or die ($deleteOrderSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
	header ( "refresh:0;" );
} //end of if DELETE button was clicked

if (isset($_POST['doupdate'])) { 
     $pproductID = $_POST['pproductID'];
     $pQuantity = $_POST['pQuantity'];
	 $ppName = $_POST['ppName'];
	 $sqlavail = "select * from Products where productID = '".$pproductID."'";
$resultavail = mysqli_query($mysqli, $sqlavail) or die (mysqli_error($mysqli));
if(mysqli_num_rows($resultavail)>0){
	while ($rowsavail = mysqli_fetch_array($resultavail)){
		$Availability = $rowsavail['Availability'];
	}}
	if($Availability < $pQuantity){
		$_SESSION['display_html'] = 'We only have '.$Availability.' units of '.$ppName.' in stock.';
		 header ( "refresh:0;" );
		}else if($pQuantity < 1){
			$deleteOrderSqlQuery = "delete from Orders where productID = '$pproductID' and User_ID = '".$_SESSION['id']."' and Order_ID = '".$LatestOrderID."'";
     $deleteResult = mysqli_query($mysqli, $deleteOrderSqlQuery) or die ($deleteOrderSqlQuery . " " .    mysqli_error($mysqli));
	 $_SESSION['success']=1;
	 $_SESSION['display_html'] = 'Your cart has been updated';
    //$display_html .= "Product " . $productID . " Deleted!";
	header ( "refresh:0;" );
			}else{
	$updateOrdersSqlQuery = "update Orders set Quantity = '$pQuantity' where productID = '$pproductID' and User_ID = '".$_SESSION['id']."' and Order_ID = '".$LatestOrderID."'";
    $updateResult = mysqli_query($mysqli, $updateOrdersSqlQuery) or die ($updateOrdersSqlQuery . " " .    mysqli_error($mysqli));
	$_SESSION['success']=1;
	 $_SESSION['display_html'] = 'Your cart has been updated';
	header ( "refresh:0;" );}
    
	 //$display_html .= $updateProductsSqlQuery;
}?>
</div>
    <div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
    </div>
</div>

<?php }else{?>
<h2 align="center">This Page is not available.</h2>

<?php }?>
<div class='container theme-showcase' role='main'>
<?php 
include "footer.php";
?>
</div>
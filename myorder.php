<?php
session_start();
 $current_page = "order";
$page_title="My Order";
include 'header.php';
connectDB();
if ($Ecommerce==1){

$safe_sitep = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_order = mysqli_real_escape_string($mysqli, $_GET['Order']);
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


 /*$query1 = "SELECT * FROM Orders WHERE User_ID = '".$_SESSION['id']."' and Status = '0' and Site_ID = '".$Site_IDp."'";
 
$res1 = mysqli_query($mysqli, $query1) or die (mysqli_error($mysqli));
*/


$query2 = "SELECT o.productID as product_id, Image, Name, Price , Quantity FROM Products p join Orders o on o.productID = p.productID join Corders co on co.Order_ID = o.Order_ID WHERE o.User_ID = '".$_SESSION['id']."' and o.Site_ID = '".$Site_IDp."' and co.Status = '1' and co.Order_ID = '".$safe_order."'";
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
                <td><?php echo $Quantity?></td>
                <td><?php $finalPrice = $Price*$Quantity; echo "$".$finalPrice."";?></td>
            <tr class="active">
  <?php 
            $total_price= $total_price + $finalPrice;
			$nproducts=$nproducts+$Quantity;
       
		
		}?>
        <tr class="active">
             <td align="center"><a href='../../../Products.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-warning'><span class='glyphicon glyphicon-arrow-left'></span> Go to Products</a></td>
             <td></td>
             <td></td>
                <td><b>Total</b></td>
                <td><?php echo "$".$total_price."";?></td>
                
            </tr>
 
    </table>
	<?php
	}
 
else{?>
	<div class='container theme-showcase' role='main'>
    <div class='alert alert-danger'>
        <strong>No orders have been found!</strong>
    </div>
	</div>
    <?php
}

if (isset($_POST['delete'])) { 
     $pproductID = $_POST['pproductID'];
     $deleteOrderSqlQuery = "delete from Orders where productID = '$pproductID' and User_ID = '".$_SESSION['id']."' and Status = '0'";
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
			$deleteOrderSqlQuery = "delete from Orders where productID = '$pproductID' and User_ID = '".$_SESSION['id']."' and Status = '0'";
     $deleteResult = mysqli_query($mysqli, $deleteOrderSqlQuery) or die ($deleteOrderSqlQuery . " " .    mysqli_error($mysqli));
	 $_SESSION['success']=1;
	 $_SESSION['display_html'] = 'Your cart has been updated';
    //$display_html .= "Product " . $productID . " Deleted!";
	header ( "refresh:0;" );
			}else{
	$updateOrdersSqlQuery = "update Orders set Quantity = '$pQuantity' where productID = '$pproductID' and User_ID = '".$_SESSION['id']."' and Status = '0'";
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
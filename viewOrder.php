<?php
session_start();
 $current_page = "vieworder";
$page_title="View Order";
include 'header.php';
connectDB();


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

if($User_ID == $_SESSION['id']){
$query2 = "SELECT o.productID as product_id, Image, Name, Price , Quantity FROM Products p join Orders o on o.productID = p.productID join Corders co on co.Order_ID = o.Order_ID WHERE o.User_ID = '".$_SESSION['id']."' and o.Site_ID = '".$Site_IDp."' and co.Order_ID = '".$safe_order."'";
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
             <td align="center"><a href='../../../orders.php?SiteName=<?php echo $safe_sitep;?>' class='btn btn-warning'><span class='glyphicon glyphicon-arrow-left'></span> Go to Orders</a></td>
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
<div class='container theme-showcase' role='main'>
<?php 
include "footer.php";
?>
</div>
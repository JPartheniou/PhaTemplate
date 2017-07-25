<?php
session_start();
 $current_page = "order";
$page_title="My Order";
include 'header.php';
connectDB();


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
 /*$query1 = "SELECT * FROM Orders WHERE User_ID = '".$_SESSION['id']."' and Status = '0' and Site_ID = '".$Site_IDp."'";
 
$res1 = mysqli_query($mysqli, $query1) or die (mysqli_error($mysqli));
*/
if($User_ID == $_SESSION['id'] && $Ecommerce==1){
	$query2 = "SELECT * FROM Corders where Site_ID = '".$Site_IDp."' and Status != '0' order by Status";
$res2 = mysqli_query($mysqli, $query2) or die (mysqli_error($mysqli));
/*$query2 = "SELECT o.productID as product_id, Image, Name, Price , Quantity, Status, User_ID FROM Products p join Orders o on o.productID = p.productID WHERE o.Site_ID = '".$Site_IDp."' and o.Status != '0'";
$res2 = mysqli_query($mysqli, $query2) or die (mysqli_error($mysqli));*/
		
?>
<h3 style="color:red">
<?php if($_SESSION['display_html'] != ''){?>
<div class='container theme-showcase' role='main' id='msg'>
<?php if ($_SESSION['success'] == 1){?>
		<div class='alert alert-success'  align='center'>
        <?php }else{?>
        <div class='alert alert-danger'  align='center'>
        <?php }?>
		<strong><?php echo $_SESSION['display_html'];?>
		<a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a>
		<?php
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
			
          	<th>User</th>
			<th>Total Price (USD)</th>
            <th>Status</th>
            <th>Action</th>
            <th>Extra Action</th>
        </tr><?php
		
		$nproducts= 0;
		$total_price=0;
		
		
		while ($row2 = mysqli_fetch_array($res2)){
			$Order_ID = $row2['Order_ID'];
			$pUser_ID = $row2['User_ID'];
			$Total = $row2['Total'];
			$pStatus = $row2['Status'];
        
         ?>
 
 <tr class="active">
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
                
                <td>
                <?php $query22 = "SELECT UserName FROM Users where ID = '".$pUser_ID."'";
$res22 = mysqli_query($mysqli, $query22) or die (mysqli_error($mysqli));
if(mysqli_num_rows($res22)>0){
	while ($rows22 = mysqli_fetch_array($res22)){
		$nick = $rows22['UserName'];
	}}
?>
				<?php echo $nick;?>
                </td>
                <td><?php echo "$".$Total."";?></td>
                <?php if($pStatus == 2){?>
                    <td><strong style="color:#0C0">Shipped</strong></td>
                    <td></td>
					<?php }else if($pStatus == 1){ ?>
                	<td><strong style="color:#F00">Not Shipped</strong></td>
                    <td><button type="submit" name="ship" class='btn btn-success'><span class='glyphicon glyphicon-plane'></span> Ship Product</button>
                    <input type="hidden" name="Order_ID" value="<?php echo $Order_ID;?>">
                    </td>
                    
                <?php }
				
				?>
                <td align="center"><a href='../../../viewOrder.php?SiteName=<?php echo $safe_sitep;?>&Order=<?php echo $Order_ID;?>' class='btn btn-info'><span class='glyphicon glyphicon-arrow-right'></span> View Order</a></td>
                </form>
                </tr>
  <?php 
            		
		}?>
 
    </table>
    
  <h3 align="center">  <a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a></h3>
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

if (isset($_POST['ship'])) { 
     $ppOrder_ID = $_POST['Order_ID'];
     
	// update Orders set Status = '2' where Site_ID = '1' and User_ID = '1' and productID = '6' and Status = '1'
     	 
    $deactivateSqlQuery = "update Corders set Status = '2' where Order_ID = '$ppOrder_ID' and Status = '1'";
    $deactivateResult = mysqli_query($mysqli, $deactivateSqlQuery) or die ($deactivateSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= "Order has been Shipped!";
    $_SESSION['success'] = 1;
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked

}else{?>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
<h2 align="center">The page you are trying to access is not available!</h2>
</div>
</div>
<?php }?>
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
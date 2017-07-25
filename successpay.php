<?php
session_start();
 $current_page = "order";
$page_title="My Order";
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
if($safe_order == 'PhaTemplate'){
$query2 = "update Sites set Ecommerce = '1' where SiteName = '".$safe_sitep."'";
$res2 = mysqli_query($mysqli, $query2) or die (mysqli_error($mysqli));
$_SESSION['display_html']='Congratulations! '.$safe_sitep.' is now a Premium Site.';
		$_SESSION['success']=1;
header ('Location:  ../../../siteManagement.php?SiteName='.$safe_sitep.'');
}else{

$query2 = "SELECT o.productID as product_id, Quantity FROM Products p join Orders o on o.productID = p.productID join Corders co on co.Order_ID = o.Order_ID WHERE o.User_ID = '".$_SESSION['id']."' and o.Site_ID = '".$Site_IDp."' and co.Status = '0' and co.Order_ID = '".$safe_order."'";
$res2 = mysqli_query($mysqli, $query2) or die (mysqli_error($mysqli));
if(mysqli_num_rows($res2)>0){
	while ($rows2 = mysqli_fetch_array($res2)){
		$pid = $rows2['product_id'];
		$Quantity = $rows2['Quantity'];
		$query3 = "update Products set Availability = Availability-".$Quantity." where productID = '".$pid."'";
$res3 = mysqli_query($mysqli, $query3) or die (mysqli_error($mysqli));
		
	}}

$updateorderquery = "update Corders set Status = '1' where Order_ID = '".$safe_order."' and User_ID = '".$_SESSION['id']."' and Site_ID = '".$Site_IDp."' and Status = '0'";
$updateorderResult = mysqli_query($mysqli, $updateorderquery) or die ($updateorderquery . " " .    mysqli_error($mysqli));

header ('Location:  ../../../myorder.php?SiteName='.$safe_sitep.'&Order='.$safe_order.'');
}?>
<div class='container theme-showcase' role='main'>
<?php 
include "footer.php";
?>
</div>
<?php 
$page_title = "YoloSwag Search";
$current_page = "Search";

?>


<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<h2>Product Search</h2>


<form action="<?=$_SESSION['PHP_SELF']?>" 
  method="post">
   <input type="text" name="searchcriteria"/>
   <input type="submit" name="submit" value="search"/>
   
</form>
<?php 
if ($_POST['submit']) {
$searchcriteria = (trim($_POST['searchcriteria']) == "")?
die ("You did not enter any search criteria. Try Again!"):
mysqli_real_escape_string($mysqli, trim($_POST['searchcriteria']));


echo "You searched for : " . $searchcriteria . " <br /> ";


$safe_search = mysqli_real_escape_string($mysqli, $searchcriteria);


$query = "select * from Products where upper(Name) like '%". strtoupper($safe_search) . "%' or upper(ShortDesc) like '%" . strtoupper($safe_search) . "%' or upper(Type) like '%" . strtoupper($safe_search) . "%'";

if (! ($result = mysqli_query($mysqli,$query)))
die ("There is something wrong in the query :" . $query . "</body></html>");





if(mysqli_num_rows($result)>0){
echo '<table class="table">
<thead>
<tr class="active"><th>Image</th><th>Product Name</th><th>Category</th><th>Description</th><th>Price</th></tr>
</thead>
    <tbody>';
while ($rows = mysqli_fetch_array($result)) {
	$Tags = $rows['Tags'];
	$productID = $rows['productID'];
echo "<tr class='active'>";
echo '<td><a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox"><img src="images/' . 
$rows['Image'] . '" width="150" height="150"></a></td><td>' .$rows['Name']. '</td><td> ' .$rows['Type']. '</td><td> ' .$rows['ShortDesc']. '</td><td>€' .$rows['Price'].'</td></tr>';
}}else{
	echo 'Could not find any products while searching for: ' . $searchcriteria ;}
echo '</tbody>
	</table>
	<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
    </div>
</div>';


?>

</div>
</div>
<?php if(mysqli_num_rows($result)>0){ ?>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      <h2 align="center">Recommended Products</h2>
<?php

$query2 = "select * from Products where upper(Tags) like '%". strtoupper($Tags) . "%'";
	
	if (! ($result2 = mysqli_query($mysqli,$query2)))
die ("There is something wrong in the query :" . $query2 . "</body></html>");

echo '<table class="table">
<thead>
<tr class="active"><th>Image</th><th>Product Name</th><th>Category</th><th>Description</th><th>Price</th></tr>
</thead>
    <tbody>';
	while ($rows2 = mysqli_fetch_array($result2)) {
		if ($rows2['productID'] != $productID){
		echo "<tr class='active'>";
echo '<td><a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox"><img src="images/' . 
$rows2['Image'] . '" width="150" height="150"></a></td><td>' .$rows2['Name']. '</td><td> ' .$rows2['Type']. '</td><td> ' .$rows2['ShortDesc']. '</td><td>€' .$rows2['Price'].'</td></tr>';
	}
	}
echo '</tbody>
	</table>
	<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
    </div>
</div>';
}
} //Form is submitted
else //form isnot submitted
echo 'Please Provide search criteria...';

?>
</div>


<?php include 'footer.php';
?>
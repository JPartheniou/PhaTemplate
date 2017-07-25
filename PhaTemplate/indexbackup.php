<?php
session_start();
$page_title = "YoloSwag Products";
$current_page = "index";
include "include.php";
include "header.php";
connectDB();
$_SESSION['MSG'] = NULL;
?>

<?php
$sql1 = "select SiteIcon from parthe_PhaTemplate.Sites";
$result1 = mysqli_query($mysqli, $sql1) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result1)>0){
	
	while ($rows1 = mysqli_fetch_array($result1)){
		$SiteIcon = $rows1['SiteIcon'];
	}
}
?>
<?php
$sql2 = "select PageName, PageTitle, PageHeaderTitle, PageHeaderText1 from parthe_PhaTemplate.Pages";
$result2 = mysqli_query($mysqli, $sql2) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result2)>0){
	
	while ($rows2 = mysqli_fetch_array($result2)){
		$PageName = $rows2['PageName'];
		$PageTitle = $rows2['PageTitle'];
		$PageHeaderTitle = $rows2['PageHeaderTitle'];
		$PageHeaderText = $rows2['PageHeaderText1'];
	}
}
?>

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2 align="center"><?php echo $PageHeaderTitle ?> </h2>
        <p align="center"><?php echo $PageHeaderText?></p>
      </div>


</div>




<?php
$sql = "select productID , Image , Name, Type, ShortDesc, Description, Price, max(Rating) from Products order by productID";

$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));?>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      <h2 align="center">Top Rated Product!</h2>
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
	$Desc = $rows['Description'];
	$Price = $rows['Price'];
	
	?>
    
    <tr class="active"><td><a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox"><img src="images/<?php echo $Image?>" width="150" height="150"></a></td>
    <td><?php echo $Name;?></td>
    <td><?php echo $Type;?></td>
    <td><?php echo $ShortDesc;?>
    <br /><li class="information">
    <button id="button1">More Information</button>
    <ul id="Info" class="Info"><?php echo $Desc?></ul></li></td>
    <td>€<?php echo $Price;?></td>
	</tr>
</tbody>
	</table>
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
<?php
include_once ('footer.php');
?>
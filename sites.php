<?php
session_start();
include "header.php";
connectDB();
$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
$page_title = 'Site Management';
$current_page = 'sitemanagement';



$sql9 = "select * from Users where ID = '".$_SESSION['id']."'";

$result9 = mysqli_query($mysqli, $sql9) or die (mysqli_error($mysqli));
			if(mysqli_num_rows($result9)>0){
	
	while ($rows9 = mysqli_fetch_array($result9)){
		$Site_ID9 = $rows9['Site_ID'];

	}
			}
if($Site_ID9==1){

if($_SESSION['walkthrough']==1){
	$_SESSION['display_html']='Press the "Create a New Site" Button to create your first Site.';
		$_SESSION['success']=1;
		
}


if($_SESSION['walkthrough']==2){
	$_SESSION['display_html']='Now you can see your first site! From here you can make it either Active or Inactive by pressing the "Activate" or "Deactivate" Buttons, you can View the site in a new window if you press the "View" Button (will be shown when you create your Home Page for the Site) and finally you can Edit your site if you press the "Edit Site" Button. Press the "Edit Site" Button to Continue.';
		$_SESSION['success']=1;
		
}
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
		$_SESSION['success']=0;
		if($_SESSION['walkthrough']==1){
			$_SESSION['walkthrough']=100;
			$_SESSION['success']=1;
		}
		if($_SESSION['walkthrough']==2){
			$_SESSION['walkthrough']=3;
			$_SESSION['success']=1;
		}
		} 
		
		


?></strong>
        
		</div>
		</div>


</h3>

<?php 


?>


<h3 align="center"><a class="btn btn-large btn-success" href="../../../createSite.php"><span class='glyphicon glyphicon-plus'></span> Create a New Site</a></h3>

<?php $sql7 = "select * from Sites where User_ID = '".$_SESSION['id']."'";

$result7 = mysqli_query($mysqli, $sql7) or die (mysqli_error($mysqli));
			if(mysqli_num_rows($result7)>0){?>
  
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">

  
  <table class="table table-striped" >
  <thead><td><b>Site Name</b></td><td><b>Activity</b></td><td><b>Action</b></td><td><b>Extra Actions</b></td><td></td></thead>
   <?php




	
	while ($rows7 = mysqli_fetch_array($result7)){
		$Site_ID7 = $rows7['Site_ID'];
		$User_ID7 = $rows7['User_ID'];
		$SiteName7 = $rows7['SiteName'];
		$Active7 = $rows7['Active'];
		$Rating7 = $rows7['Rating'];
		$Votes7 = $rows7['Votes'];
		$CreationDate7 = $rows7['CreationDate'];
		$LastUpdate7 = $rows7['LastUpdate'];
		$Ecommerce7 = $rows7['Ecommerce'];
		
		$sql80 = "Select Page_ID, Site_ID, HomePage, PageName from Pages where Site_ID = '".$Site_ID7."' and HomePage = '1'";
$result80 = mysqli_query($mysqli, $sql80) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result80)>0){
	while ($rows80 = mysqli_fetch_array($result80)){
	$PageName80 = $rows80['PageName'];}}else{$PageName80='';}
		?>
        <tbody>
        <tr class="active">
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
                <td><?php echo $SiteName7?></td>
                <?php if ($Active7 == 1){?>
                    <td><strong style="color:#0C0">Active</strong></td>
                    <td><button type="submit" name="deactivate" class='btn btn-danger'><span class='glyphicon glyphicon-off'></span> Deactivate</button>
                    <input type="hidden" name="Site_ID7" value="<?php echo $Site_ID7;?>">
                    <input type="hidden" name="SiteName7" value="<?php echo $SiteName7;?>"></td><?php }else{ ?>
                	<td><strong style="color:#F00">Inactive</strong></td>
                    <td><button type="submit" name="activate" class='btn btn-success'><span class='glyphicon glyphicon-off'></span> Activate</button>
                    <input type="hidden" name="Site_ID7" value="<?php echo $Site_ID7;?>">
                    <input type="hidden" name="SiteName7" value="<?php echo $SiteName7;?>"></td>
                <?php }
				?><td><?php if($PageName80!=''){?><button type="button" class="btn btn-large btn-info" onclick="window.open('../../../<?php echo $SiteName7?>/<?php echo $PageName80?>')"><span class='glyphicon glyphicon-share-alt'></span> View</button><?php }?>    <a class="btn btn-large btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $SiteName7?>"><span class='glyphicon glyphicon-cog'></span> Edit Site</a></td>
		
                <td>
                <?php
				$minav = 3;
                $prsql7 = "SELECT Availability FROM Products where Site_ID = '".$Site_ID7."' and Availability = '0'";
		$prresult7 = mysqli_query($mysqli, $prsql7) or die (mysqli_error($mysqli));
		
		if(mysqli_num_rows($prresult7)>0){
			?>
				<a class="btn btn-large btn-danger" href="../../../productMaintenance.php?SiteName=<?php echo $SiteName7;?>"><span class='glyphicon glyphicon-share-alt'></span> View Product Availability</a>
				<?php }else{
					$prsql71 = "SELECT Availability FROM Products where Site_ID = '".$Site_ID7."' and Availability < '3'";
		$prresult71 = mysqli_query($mysqli, $prsql71) or die (mysqli_error($mysqli));
		
		if(mysqli_num_rows($prresult71)>0){?>
				<a class="btn btn-large btn-warning" href="../../../productMaintenance.php?SiteName=<?php echo $SiteName7;?>"><span class='glyphicon glyphicon-share-alt'></span> View Product Availability</a>
				
				<?php }}?>
			
                </td></form>
                </tr>
       </tbody>
      

      




<?php }
}
?>

</table>

<?php

if (isset($_POST['deactivate'])) { 
     $Site_ID77 = $_POST['Site_ID7'];
     $SiteName77 = $_POST['SiteName7'];
     	 
    $deactivateSqlQuery = "update Sites set Active = '0' where Site_ID = '$Site_ID77'";
    $deactivateResult = mysqli_query($mysqli, $deactivateSqlQuery) or die ($deactivateSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= $SiteName77. " has been deactivated!";
    $_SESSION['success'] = 1;
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked

if (isset($_POST['activate'])) { 
     $Site_ID77 = $_POST['Site_ID7'];
     $SiteName77 = $_POST['SiteName7'];
     	 
    $activateSqlQuery = "update Sites set Active = '1' where Site_ID = '$Site_ID77'";
    $activateResult = mysqli_query($mysqli, $activateSqlQuery) or die ($activateSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= $SiteName77. " has been activated!";
    $_SESSION['success'] = 1;
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked
?>

<?php 

}?>

</div>
</div>
</div>
</div>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->

<?php
include 'footer.php';
?>
</div>

			
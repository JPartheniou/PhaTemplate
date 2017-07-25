<?php
session_start();
$page_title = $_GET['PageName'];
include "header.php";
connectDB();
$_SESSION['a']=1;
$_SESSION['MSG'] = NULL;
?>

<?php
$sql1 = "select * from Sites where SiteName = '".$safe_site."'";
$result1 = mysqli_query($mysqli, $sql1) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result1)>0){
	
	while ($rows1 = mysqli_fetch_array($result1)){
		$Site_ID = $rows1['Site_ID'];
		$User_ID = $rows1['User_ID'];
		$Active = $rows1['Active'];
		$SiteName = $rows1['SiteName'];
	}
}
?>

<?php
$sql2 = "select Page_ID, Site_ID, PageName, PageTitle, ActiveHeader, HeaderAreaColor, PageHeaderTitle, HeaderTitleColor, PageHeaderText1, HeaderImage1, PageHeaderText2, HeaderImage2, PageHeaderText3, HeaderTextColor, ActiveBody, BodyAreaColor, PageBodyTitle, BodyTitleColor, PageBodyText1, BodyImage1, PageBodyText2, BodyImage2, PageBodyText3, BodyTextColor, CreationDate, LastUpdate from Pages where Site_ID = '".$Site_ID."' and PageName = '" .$safe_page. "'";
$result2 = mysqli_query($mysqli, $sql2) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result2)>0){
	
	while ($rows2 = mysqli_fetch_array($result2)){
		$Page_ID = $rows2['Page_ID'];
		$Site_ID = $rows2['Site_ID'];
		$PageName = $rows2['PageName'];
		$PageTitle = $rows2['PageTitle'];
		$ActiveHeader = $rows2['ActiveHeader'];
		$HeaderAreaColor = $rows2['HeaderAreaColor'];
		$PageHeaderTitle = $rows2['PageHeaderTitle'];
		$HeaderTitleColor = $rows2['HeaderTitleColor'];
		$PageHeaderText1 = $rows2['PageHeaderText1'];
		$HeaderImage1 = $rows2['HeaderImage1'];
		$PageHeaderText2 = $rows2['PageHeaderText2'];
		$HeaderImage2 = $rows2['HeaderImage2'];
		$PageHeaderText3 = $rows2['PageHeaderText3'];
		$HeaderTextColor = $rows2['HeaderTextColor'];
		$ActiveBody = $rows2['ActiveBody'];
		$BodyAreaColor = $rows2['BodyAreaColor'];
		$PageBodyTitle = $rows2['PageBodyTitle'];
		$BodyTitleColor = $rows2['BodyTitleColor'];
		$PageBodyText1 = $rows2['PageBodyText1'];
		$BodyImage1 = $rows2['BodyImage1'];
		$PageBodyText2 = $rows2['PageBodyText2'];
		$BodyImage2 = $rows2['BodyImage2'];
		$PageBodyText3 = $rows2['PageBodyText3'];
		$BodyTextColor = $rows2['BodyTextColor'];
		$CreationDate = $rows2['CreationDate'];
		$LastUpdate = $rows2['LastUpdate'];
		
	}
}

?>
<?php 
if($Active == 1){
	if ((!isset($_SESSION['user']) || $_SESSION['Site_ID'] != $Site_ID) && ($_SESSION['Admin']!=1) && ($_SESSION['id'] != $User_ID)) {
		?>
		<h3 align="center"><a class="btn btn-large btn-info" href="../../../login.php?SiteName=<?php echo $safe_site?>&PageName=<?php echo $safe_page?>">Log In</a>                               <a class="btn btn-large btn-info" href="../../../register.php?SiteName=<?php echo $safe_site?>&PageName=<?php echo $safe_page?>">Register</a></h3>
	<?php }
	if($ActiveHeader == 1){
?>

<div class="container theme-showcase" role="main" >
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron" style="background:<?php echo $HeaderAreaColor?>;" >
        <h2 align="center" style="color:<?php echo $HeaderTitleColor?>"><?php echo $PageHeaderTitle ?></h2>
        <p align="center" style="color:<?php echo $HeaderTextColor?>">
		<?php if ($PageHeaderText1 != NULL){
			echo $PageHeaderText1;
		}?>
        <?php if ($HeaderImage1 != NULL && $HeaderImage1 != ''){?>
        	<a class="thumbnail"   style="display:inline-block; max-width: 100%;"><img src="../../../images/<?php echo $HeaderImage1?>"></a>
        <?php }?>
        <?php if ($PageHeaderText2 != NULL){
        	echo $PageHeaderText2;
		}?>
        <?php if ($HeaderImage2 != NULL && $HeaderImage2 != ''){?>
        	<a class="thumbnail"  style="display:inline-block; max-width: 100%;"><img src="../../../images/<?php echo $HeaderImage2?>"></a>
        <?php }?>
        <?php if ($PageHeaderText3 != NULL){
        	echo $PageHeaderText3;
		}?>
        </p>
        </div>
</div>
<?php 
	}
?>

<?php 
	if($ActiveBody == 1){
?>
<div class="container theme-showcase" role="main">
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron" style="background:<?php echo $BodyAreaColor?>;">
        <h2 align="center" style="color:<?php echo $BodyTitleColor?>"><?php echo $PageBodyTitle?> </h2>
        <p align="center" style="color:<?php echo $BodyTextColor?>">
		<?php if ($PageBodyText1 != NULL){
			echo $PageBodyText1;
		}?>
        <?php if ($BodyImage1 != NULL && $BodyImage1 != ''){?>
     		<a class="thumbnail"  style="display:inline-block; max-width: 100%;"><img src="../../../images/<?php echo $BodyImage1?>"></a>
		<?php }?>
        <?php if ($PageBodyText2 != NULL){
        	echo $PageBodyText2;
		}?>
        <?php if ($BodyImage2 != NULL && $BodyImage2 != ''){?>
        	<a class="thumbnail"  style="display:inline-block; max-width: 100%;"><img src="../../../images/<?php echo $BodyImage2?>"></a>
        <?php }?>
        <?php if ($PageBodyText3 != NULL){
        	echo $PageBodyText3;
		}?>
        </p>
      </div>
</div>
<?php }
	}else{?>
		<div class="container theme-showcase" role="main" >
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      <h2 align="center">The Site you are trying to access is not available right now!</h2>
      </div>
      </div>
<?php	}
?>




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
<div class="container theme-showcase" role="main">
<?php
include "footer.php";
?>
</div>

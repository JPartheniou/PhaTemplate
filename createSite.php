<?php
session_start();
include "header.php";
connectDB();
if($_SESSION['id']!= NULL && $_SESSION['Site_ID'] == 1){
$page_title = "Create New Site";


if($_SESSION['walkthrough']==100){
	$_SESSION['display_html']='Add information in the fields and press the "Create Site" Button and then Go back to Sites.';
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
		<strong><?php echo $_SESSION['display_html']; if($_SESSION['walkthrough']!=100){?>
		<a class="btn btn-warning" href="../../../sites.php"><span class='glyphicon glyphicon-arrow-left'></span> Go to Sites</a> <?php }if($_SESSION['walkthrough']==0){?>or <a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $_SESSION['sitename1'];?>"><span class='glyphicon glyphicon-arrow-right'></span> Go to Site Management</a>
		<?php }
		$_SESSION['display_html']='';
		$_SESSION['success']=0;} 
		if($_SESSION['walkthrough']==100){
		$_SESSION['walkthrough']=2;
		$_SESSION['success']=1;}
		?> </strong>
        
		</div>
		</div>
</h3>





<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      
<h2 align="center"> Create New Site </h2>

<!--<table width="50%" class="boxbg all-round">-->
<table class="table" >
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="createSiteForm">
<tr class="active">
     <td>Site Name: </td>
     <td><input type="text" name="SiteName1" size="50" required/></td><td><em>This is the name of the Site. You can access a page if you place the Page Name after the Site Name on the URL.<br /> e.g. partheniou.students.acg.edu/SiteName/PageName. Please Note that you should only put alphanumeric characters in the name of your Site. If you have special characters then your Site will not be accessible. You can not change the name of your Site later so think pick the name of your Site carefully!</em></td>
</tr>
<tr class="active">
     <td>Site Category: </td>
     <td><input type="text" name="SiteCategory1" size="50" required/></td><td></td>
</tr>
</tr>
<tr class="active">
     <td>Description: </td>
     <td><textarea name="Description1" cols="51" rows="10" required></textarea></td><td></td>
</tr>
<tr class="active">
     <td>Active </td>
     <td><input type="checkbox" name="Active1" value="1"/></td><td><em>If Active is selected that means that your Site can be accessed. If it is not then you can still edit it in the Site Management Interface but it can not be accessed! You can activate or deactivate it from the Sites Interface.</em></td>
</tr>
<tr colspan="2"><td>
<input type="hidden" name="UID" value="<?php echo $_SESSION['id'];?>" />
			<button TYPE="submit" name="insert" title="Add data to the Database" class="btn btn-success"><span class='glyphicon glyphicon-ok'></span> Create Site</td>
			<td align="right"><a class="btn btn-warning" href="../../../sites.php"><span class='glyphicon glyphicon-arrow-left'></span> Go to Sites</a></td>
</tr>
</form>
    
</table> 
</div>
</div>
<!-- CHECH IF ANYTHING WAS PRESSED : insert, delete or update -->
<?php
if (isset($_POST['insert'])) {
//This gets all the other information from the form



		$SiteName1 = $_POST['SiteName1'];
		$SiteCategory1 = $_POST['SiteCategory1'];
		$Description1 = $_POST['Description1'];
		$Active1 = $_POST['Active1'];
		$UID = $_POST['UID'];
				
				
				$sqlsite = "select * from Sites where SiteName = '".$SiteName1."'";
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


				
		if($Active1 == ''){
			$Active1 = 0;
		}
		
 if ($SiteName1=='' || $SiteCategory1 =='' ||  $Description1 =='' ||  $UID ==''){
        $_SESSION['display_html'] .= "Required information is missing... fill-in all fields first... ";
        header ( "refresh:0;" );
 }else if(mysqli_num_rows($resultsite)>0){
  $_SESSION['display_html'] .= "A Site with this Name already exist! Please choose another one!";
  header ( "refresh:0;" );
 }else { //Writes the information to the database
      $addSiteSqlQuery = "INSERT INTO Sites (User_ID, SiteName, SiteCategory, Description, Active, Rating, Votes, CreationDate, LastUpdate, Ecommerce) VALUES ('$UID', '$SiteName1', '$SiteCategory1', '$Description1', '$Active1', '3', '1', NOW(), NOW(), '0')";
      $insertResult = mysqli_query($mysqli, $addSiteSqlQuery) or die ($addSiteSqlQuery . " " .    mysqli_error($mysqli));
        
      $sqlco = "select * from Sites where SiteName = '".$SiteName1."'";

$resultco = mysqli_query($mysqli, $sqlco) or die (mysqli_error($mysqli));    
if(mysqli_num_rows($resultco)>0){
		  while ($rowsco = mysqli_fetch_array($resultco)){
		  	$coSite_ID = $rowsco['Site_ID'];
		  }
		  } 



                      $addcoSqlQuery = "INSERT INTO ProductColumns (Site_ID, ActiveName, ActiveType, ActiveDescription, ActiveShortDesc, ActiveImage, ActivePrice, ActiveAvailability, ActiveRating) VALUES ('$coSite_ID', '1', '1', '1', '1', '1', '1', '1', '1')";
      $insertResultco = mysqli_query($mysqli, $addcoSqlQuery ) or 
                      die ($addcoSqlQuery . " " .    mysqli_error($mysqli));
                      
                       $_SESSION['sitename1']=$SiteName1;
                      $_SESSION['success']=1;
    $_SESSION['display_html'] .= "Site was Created Successfully!";  
	if($_SESSION['Admin'] == 0){  
	$changeUserSqlQuery	= "update Users set is_Admin = '2' where ID = '$UID'";			  
		$userChange = mysqli_query($mysqli, $changeUserSqlQuery) or 
                      die ($changeUserSqlQuery . " " .    mysqli_error($mysqli));	  
	}
	  
     
	 	unset($SiteName1);
		unset($SiteCategory1);
		unset($Description1);
		unset($Active1);
		unset($UID);
		
   
   
   
   
    } //end of writing info to database 
    header ( "refresh:0;" );
} //end of if INSERT button was clicked


?>
</div>
                                              

<?php } else {
	header("Location: ../../../PhaTemplate/Home") ;
}?>

<div class="container theme-showcase" role="main">
<?php include "footer.php";?>
</div>



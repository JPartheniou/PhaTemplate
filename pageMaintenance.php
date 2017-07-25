<?php
session_start();
$page_title = "Edit Page";
include "header.php";
connectDB();
$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
if($_SESSION['Admin']==1 || $User_ID == $_SESSION['id']){

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

<h3 style="color:red">
<?php if($_SESSION['display_html'] != ''){?>
<div class='container theme-showcase' role='main' id='msg'>
<?php if ($_SESSION['success'] == 1){?>
		<div class='alert alert-success'  align='center'>
        <?php }else{?>
        <div class='alert alert-danger'  align='center'>
        <?php }?>
		<strong><?php echo $_SESSION['display_html'];?>                  <a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a>
		<?php $_SESSION['display_html']='';
		$_SESSION['success']=0;} ?></strong>
        
		</div>
		</div>

</h3>

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<!--<h2 style="color:blue"> Add New Product</h2>-->
<h2 align="center">Edit <?php echo $safe_page;?></h2>
<!--<table width="50%" class="boxbg all-round">-->
<table class="table" >
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="updatePageForm">
<h2> Header</h2>
<tr class="active">
     <td>Page Title: </td>
     <td><input type="text" name="PageTitle" size="50" value="<?php echo $PageTitle?>" required/></td><td><em> Page Title is the Text that will be shown on the Tab of the browser. For example in this page the title is "Edit Page"</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Active Header </td>
     <td><input type="checkbox" name="ActiveHeader" <?php if ($ActiveHeader == 1){?> checked="checked" <?php } ?> value="1"></td><td><em>If Active Header is selected then the first area of content will be visible.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Header Area Color: </td>
     <td><input type="color" name="HeaderAreaColor" id="HeaderAreaColor" value="<?php echo $HeaderAreaColor?>"/></td><td><em>Here you can choose the background color of the first area of content.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Header Title: </td>
     <td><input type="text" name="PageHeaderTitle" size="50" value="<?php echo $PageHeaderTitle?>" /></td><td><em>This is the Title of the first area of content.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Header Title Color: </td>
     <td><input type="color" name="HeaderTitleColor" id="HeaderTitleColor" value="<?php echo $HeaderTitleColor?>" /></td><td><em>Here you can choose the Font color of the Title of the first area of content.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Header Text 1: </td>
     <td><textarea name="PageHeaderText1" cols="51" rows="10" ><?php echo $PageHeaderText1?></textarea></td><td><em>Here you can type the first text of the first area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta onclick="window.open('http://www.google.com')"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a onclick="window.open('http://www.google.com')">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td><td width="50px"></td>
</tr>
<tr class="active">
    <td>Header Image 1: </td>	
    <td><?php if ($HeaderImage1 != NULL){?>
        	<a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox" style="display:inline-block;"><img src="../../../images/<?php echo $HeaderImage1?>"></a>
        <?php }?></td>
    <td><input type="hidden" name="size1" value="3500000">
		<input type="file" name="HeaderImage1" value="<?php echo $HeaderImage1?>"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	<td width="50px">Delete Image  
    <input type="checkbox" name="DeleteHeaderImage1" value="1"></td>	
</tr>
<tr class="active">
     <td>Header Text 2: </td>
     <td><textarea name="PageHeaderText2" cols="51" rows="10" ><?php echo $PageHeaderText2?></textarea></td><td><em>Here you can type the second text of the first area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta onclick="window.open('http://www.google.com')"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a onclick="window.open('http://www.google.com')">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td><td width="50px"></td>
</tr>
<tr class="active">
    <td>Header Image 2: </td>	
    <td><?php if ($HeaderImage2 != NULL){?>
        	<a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox" style="display:inline-block;"><img src="../../../images/<?php echo $HeaderImage2?>"></a>
        <?php }?></td>
    <td><input type="hidden" name="size2" value="3500000">
		<input type="file" name="HeaderImage2" value="<?php echo $HeaderImage2?>"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	</td>
    <td width="50px">Delete Image  
    <input type="checkbox" name="DeleteHeaderImage2" value="1"></td>	
</tr>
<tr class="active">
     <td>Header Text 3: </td>
     <td><textarea name="PageHeaderText3" cols="51" rows="10" ><?php echo $PageHeaderText3?></textarea></td><td><em>Here you can type the third text of the first area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta onclick="window.open('http://www.google.com')"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a onclick="window.open('http://www.google.com')">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Header Text Color: </td>
     <td><input type="color" name="HeaderTextColor" id="HeaderTextColor" value="<?php echo $HeaderTextColor?>"/></td><td><em>Here you can choose the Font color of the Text of the first area of content.</em></td><td width="50px"></td>
</tr>
<!--------------------------------------------->
<tr><td>
<h2> Body</h2>
</td></tr>
<tr class="active">
     <td>Active Body </td>
     <td><input type="checkbox" name="ActiveBody" <?php if ($ActiveBody == 1){?> checked="checked" <?php } ?> value="1"></td><td><em>If Active Body is selected then the second area of content will be visible.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Body Area Color: </td>
     <td><input type="color" name="BodyAreaColor" id="BodyAreaColor" value="<?php echo $BodyAreaColor?>"/></td><td><em>Here you can choose the background color of the second area of content.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Body Title: </td>
     <td><input type="text" name="PageBodyTitle" size="50" value="<?php echo $PageBodyTitle?>"/></td><td><em>This is the Title of the second area of content.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Body Title Color: </td>
     <td><input type="color" name="BodyTitleColor" id="BodyTitleColor" value="<?php echo $BodyTitleColor?>"/></td><td><em>Here you can choose the Font color of the Title of the second area of content.</em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Body Text 1: </td>
     <td><textarea name="PageBodyText1" cols="51" rows="10" ><?php echo $PageBodyText1?></textarea></td><td><em>Here you can type the first text of the second area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta onclick="window.open('http://www.google.com')"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a onclick="window.open('http://www.google.com')">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td><td width="50px"></td>
</tr>
<tr class="active">
    <td>Body Image 1: </td>	
    <td><?php if ($BodyImage1 != NULL){?>
        	<a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox" style="display:inline-block;"><img src="../../../images/<?php echo $BodyImage1?>"></a>
        <?php }?></td>
    <td><input type="hidden" name="size3" value="3500000">
		<input type="file" name="BodyImage1" value="<?php echo $BodyImage1?>"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	</td>	
    <td width="50px">Delete Image  
    <input type="checkbox" name="DeleteBodyImage1" value="1"></td>
</tr>
<tr class="active">
     <td>Body Text 2: </td>
     <td><textarea name="PageBodyText2" cols="51" rows="10" ><?php echo $PageBodyText2?></textarea></td><td><em>Here you can type the second text of the second area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta onclick="window.open('http://www.google.com')"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a onclick="window.open('http://www.google.com')">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td><td width="50px"></td>
</tr>
<tr class="active">
    <td>Body Image 2: </td>	
    <td><?php if ($BodyImage2 != NULL){?>
        	<a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox" style="display:inline-block;"><img src="../../../images/<?php echo $BodyImage2?>"></a>
        <?php }?></td>
    <td><input type="hidden" name="size4" value="3500000">
		<input type="file" name="BodyImage2" value="<?php echo $BodyImage2?>"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	</td>	
    <td width="50px">Delete Image  
    <input type="checkbox" name="DeleteBodyImage2" value="1"></td>
</tr>
<tr class="active">
     <td>Body Text 3: </td>
     <td><textarea name="PageBodyText3" cols="51" rows="10" ><?php echo $PageBodyText3?></textarea></td><td><em>Here you can type the third text of the second area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta onclick="window.open('http://www.google.com')"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a onclick="window.open('http://www.google.com')">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td><td width="50px"></td>
</tr>
<tr class="active">
     <td>Body Text Color: </td>
     <td><input type="color" name="BodyTextColor" id="BodyTextColor" value="<?php echo $BodyTextColor?>"/></td><td><em>Here you can choose the Font color of the Text of the second area of content.</em></td><td width="50px"></td>
</tr>
<tr colspan="2"><td>
<input type="hidden" name="Site_ID" value="<?php echo $Site_ID?>"> 
<input type="hidden" name="PageName" value="<?php echo $PageName?>"> 
<input type="hidden" name="Page_ID" value="<?php echo $Page_ID?>"> 
			<button TYPE="submit" name="update" title="Add data to the Database" class="btn btn-success"><span class='glyphicon glyphicon-ok'></span> Save</button></td>
           <td></td><td></td> 
	        <td><a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a> </td>
</tr>
</form>
    
</table> 
</div>
</div>
<!-- CHECH IF ANYTHING WAS PRESSED : insert, delete or update -->
<?php
if (isset($_POST['update'])) {
//This gets all the other information from the form

		$target = "images/";
		
		
		$Page_ID = $_POST['Page_ID'];
		$Site_ID = $_POST['Site_ID'];
		$PageName = $_POST['PageName'];
		$PageTitle = $_POST['PageTitle'];
		$ActiveHeader = $_POST['ActiveHeader'];
		$HeaderAreaColor = $_POST['HeaderAreaColor'];
		$PageHeaderTitle = $_POST['PageHeaderTitle'];
		$HeaderTitleColor = $_POST['HeaderTitleColor'];
		$PageHeaderText1 = $_POST['PageHeaderText1'];
		$info1 = basename($_FILES['HeaderImage1']['name']);
		if($info1 != ''){
			$HeaderImage1 = $safe_site."_".$info1; 
		}
		$target1 = $target . $HeaderImage1;
		$DeleteHeaderImage1 = $_POST['DeleteHeaderImage1'];
		$PageHeaderText2 = $_POST['PageHeaderText2'];
		$info2 = basename($_FILES['HeaderImage2']['name']);
		if($info2 != ''){
			$HeaderImage2 = $safe_site."_".$info2; 
		}
		$target2 = $target . $HeaderImage2;
		$DeleteHeaderImage2 = $_POST['DeleteHeaderImage2'];
		$PageHeaderText3 = $_POST['PageHeaderText3'];
		$HeaderTextColor = $_POST['HeaderTextColor'];
		$ActiveBody = $_POST['ActiveBody'];
		$BodyAreaColor = $_POST['BodyAreaColor'];
		$PageBodyTitle = $_POST['PageBodyTitle'];
		$BodyTitleColor = $_POST['BodyTitleColor'];
		$PageBodyText1 = $_POST['PageBodyText1'];
		$info3 = basename($_FILES['BodyImage1']['name']);
		if($info3 != ''){
		$BodyImage1 = $safe_site."_".$info3; 
		}
		$target3 = $target . $BodyImage1;
		$DeleteBodyImage1 = $_POST['DeleteBodyImage1'];
		$PageBodyText2 = $_POST['PageBodyText2'];
		$info4 = basename($_FILES['BodyImage2']['name']);
		if($info4 != ''){
		$BodyImage2 = $safe_site."_".$info4;
		}
		$target4 = $target . $BodyImage2;
		$DeleteBodyImage2 = $_POST['DeleteBodyImage2'];
		$PageBodyText3 = $_POST['PageBodyText3'];
		$BodyTextColor = $_POST['BodyTextColor'];
				
		if($ActiveHeader == ''){
			$ActiveHeader = 0;
		}
		if($ActiveBody == ''){
			$ActiveBody = 0;
		}
		if ($PageHeaderTitle=='' && $PageHeaderText1 =='' && $HeaderImage1 =='' && $PageHeaderText2 =='' && $HeaderImage2 =='' && $PageHeaderText3 ==''){
			$ActiveHeader = 0;
		}
		if ($PageBodyTitle=='' && $PageBodyText1 =='' && $BodyImage1 =='' && $PageBodyText2 =='' && $BodyImage2 =='' && $PageBodyText3 ==''){
			$ActiveBody = 0;
		}
		if($DeleteHeaderImage1 == 1){
			$HeaderImage1 = '';
		}
		if($DeleteHeaderImage2 == 1){
			$HeaderImage2 = '';
		}
		if($DeleteBodyImage1 == 1){
			$BodyImage1 = '';
		}
		if($DeleteBodyImage2 == 1){
			$BodyImage2 = '';
		}
		
 if ($PageTitle ==''){
        $display_html .= "System Messages : Required information is missing... fill-in all fields first... ";
 }else { //Writes the information to the database
      $updatePageSqlQuery = "update Pages set PageTitle = '$PageTitle', ActiveHeader = '$ActiveHeader', HeaderAreaColor = '$HeaderAreaColor', PageHeaderTitle = '$PageHeaderTitle', HeaderTitleColor = '$HeaderTitleColor', PageHeaderText1 = '$PageHeaderText1', HeaderImage1 = '$HeaderImage1', PageHeaderText2 = '$PageHeaderText2', HeaderImage2 = '$HeaderImage2', PageHeaderText3 = '$PageHeaderText3', HeaderTextColor = '$HeaderTextColor', ActiveBody = '$ActiveBody',  BodyAreaColor = '$BodyAreaColor', PageBodyTitle = '$PageBodyTitle', BodyTitleColor = '$BodyTitleColor', PageBodyText1 = '$PageBodyText1', BodyImage1 = '$BodyImage1', PageBodyText2 = '$PageBodyText2', BodyImage2 = '$BodyImage2', PageBodyText3 = '$PageBodyText3', BodyTextColor = '$BodyTextColor', LastUpdate = NOW() where Page_ID = '$Page_ID'";
    $updateResult = mysqli_query($mysqli, $updatePageSqlQuery) or die ($updatePageSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= "Page '" . $PageName . "' Updated! ";

   
   ///Writes the photo to the server
   if($info1 != ''){
	   if($HeaderImage1 != ''){
			if(move_uploaded_file($_FILES['HeaderImage1']['tmp_name'], $target1) )
			{
				//Tells you if its all ok
				$_SESSION['display_html'] .= " The file ". basename($_FILES['HeaderImage1']['name']). " has been uploaded.";
			}
			else {
				//Gives and error if its not
				$_SESSION['display_html'] .= " Sorry, there was a problem uploading". basename($_FILES['HeaderImage1']['name']). ".";
			}
		}else{
			$_SESSION['display_html'] .= " You deleted your Header Image 1.";
		}			
   }
	if($info2 != ''){
		if($HeaderImage2 != ''){
			if(move_uploaded_file($_FILES['HeaderImage2']['tmp_name'], $target2) )
			{
				//Tells you if its all ok
				$_SESSION['display_html'] .= " The file ". basename($_FILES['HeaderImage2']['name']). " has been uploaded.";
			}
			else {
				//Gives and error if its not
				$_SESSION['display_html'] .= " Sorry, there was a problem uploading". basename($_FILES['HeaderImage2']['name']). ".";
			}
		}else{
			$_SESSION['display_html'] .= " You deleted your Header Image 2.";
		}
	}
		   if($info3 != ''){
if($BodyImage1 != ''){
		if(move_uploaded_file($_FILES['BodyImage1']['tmp_name'], $target3) )
        {
        //Tells you if its all ok
        $_SESSION['display_html'] .= " The file ". basename($_FILES['BodyImage1']['name']). " has been uploaded.";
        }
        else {
        //Gives and error if its not
        $_SESSION['display_html'] .= " Sorry, there was a problem uploading". basename($_FILES['BodyImage1']['name']). ".";
        }
		   }else{
			   $_SESSION['display_html'] .= " You deleted your Body Image 1.";
		   }
		   }
	if($info4 != ''){
		if($BodyImage1 != ''){
			if(move_uploaded_file($_FILES['BodyImage2']['tmp_name'], $target4) )
			{
				//Tells you if its all ok
				$_SESSION['display_html'] .= " The file ". basename($_FILES['BodyImage2']['name']). " has been uploaded.";
			}
			else {
				//Gives and error if its not
				$_SESSION['display_html'] .= " Sorry, there was a problem uploading". basename($_FILES['BodyImage2']['name']). ".";
			}
		}else{
			$_SESSION['display_html'] .= " You deleted your Body Image 2.";
		}
	}//end move uploaded files*/
    } //end of writing info to database 
	$_SESSION['success']=1;
	$_SESSION['display_html'] .= " Your information has been added to the directory";
	header ( "refresh:0;" );
} //end of if update button was clicked

if (isset($_POST['delete'])) { 
     $deletePageSqlQuery = "delete from Pages where Page_ID = '" . $Page_ID . "'";
     $deleteResult = mysqli_query($mysqli, $deletePageSqlQuery) or die ($deletePageSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
	$display_html .= $deletePageSqlQuery;
	header ( "refresh:0;" );
} //end of if DELETE button was clicked
?>
</div>
                                              
<h3 style="color:red">
<?php echo $display_html ?><br />
</h3>
<?php } else {
	header("Location: ../../../PhaTemplate/Home") ;
}?>
<div class="container theme-showcase" role="main">
<?php
include "footer.php";
?>
</div>
<?php
session_start();
$page_title = "Create Page";
include "header.php";
connectDB();
if($_SESSION['Admin']==1 || $User_ID == $_SESSION['id']){

if($_SESSION['walkthrough']==4){
	$_SESSION['display_html']='Fill in the Fields, then Press "Create Page" Button and then Go to Site Management Page.';
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
		<strong><?php echo $_SESSION['display_html']; if($_SESSION['walkthrough']!=4){?>                  <a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a><?php }if($_SESSION['walkthrough']!=5){?> or <button type="button" class="btn btn-large btn-info" onclick="window.open('../../../<?php echo $safe_site;?>/<?php echo $_SESSION['newpage'];?>')"><span class='glyphicon glyphicon-share-alt'></span> View Page</button>
		<?php }
		$_SESSION['display_html']='';
		$_SESSION['success']=0;
		
		if($_SESSION['walkthrough']==4){
	$_SESSION['walkthrough']=5;
	$_SESSION['success']=1;
}
		} ?></strong>
        
		</div>
		</div>


</h3>
 

<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<!--<h2 style="color:blue"> Add New Product</h2>-->
<h2 align="center"> Create New Page </h2>
<!--<table width="50%" class="boxbg all-round">-->
<table class="table" >
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="addPageForm">
<h2> Header</h2>
<tr class="active">
     <td>Page Name: </td>
     <td><input type="text" name="PageName" size="50" required/></td><td><em>This is the name of the Page. You can access this page if you place the Page Name after the Site Name on the URL.<br /> e.g. partheniou.students.acg.edu/<?php echo $safe_site;?>/PageName. Please Note that you should only put <strong> alphanumeric characters</strong> in the name of your Page <strong>(Without Space)</strong>. If you have special characters then your page will not be accessible. You can not change the name of your Page later so pick the name of your Page carefully!</em></td>
</tr>
<tr class="active">
     <td>Page Title: </td>
     <td><input type="text" name="PageTitle" size="50" required/></td><td><em> Page Title is the Text that will be shown on the Tab of the browser. For example in this page the title is "Create Page"</em></td>
</tr>
<tr class="active">
     <td>Active Header </td>
     <td><input type="checkbox" name="ActiveHeader" value="1"></td><td><em>If Active Header is selected then the first area of content will be visible.</em></td>
</tr>
<tr class="active">
     <td>Header Area Color: </td>
     <td><input type="color" name="HeaderAreaColor" id="HeaderAreaColor" value="#eeeeee"/></td><td><em>Here you can choose the background color of the first area of content.</em></td>
</tr>
<tr class="active">
     <td>Header Title: </td>
     <td><input type="text" name="PageHeaderTitle" size="50" /></td><td><em>This is the Title of the first area of content.</em></td>
</tr>
<tr class="active">
     <td>Header Title Color: </td>
     <td><input type="color" name="HeaderTitleColor" id="HeaderTitleColor"/></td><td><em>Here you can choose the Font color of the Title of the first area of content.</em></td>
</tr>
<tr class="active">
     <td>Header Text 1: </td>
     <td><textarea name="PageHeaderText1" cols="51" rows="10" ></textarea></td><td><em>Here you can type the first text of the first area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta href="http://www.google.com/" target="_blank"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a href="http://www.google.com/" target="_blank">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td>
</tr>
<tr class="active">
    <td>Header Image 1: </td>	
    <td><input type="hidden" name="size1" value="3500000">
		<input type="file" name="HeaderImage1"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	</td>	<td></td>
</tr>
<tr class="active">
     <td>Header Text 2: </td>
     <td><textarea name="PageHeaderText2" cols="51" rows="10" ></textarea></td><td><em>Here you can type the second text of the first area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta href="http://www.google.com/" target="_blank"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a href="http://www.google.com/" target="_blank">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td>
</tr>
<tr class="active">
    <td>Header Image 2: </td>	
    <td><input type="hidden" name="size2" value="3500000">
		<input type="file" name="HeaderImage2"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	</td>	<td></td>
</tr>
<tr class="active">
     <td>Header Text 3: </td>
     <td><textarea name="PageHeaderText3" cols="51" rows="10" ></textarea></td><td><em>Here you can type the third text of the first area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta href="http://www.google.com/" target="_blank"gtClick Here&lt/a&gt <br />will have this output: <br /> <a href="http://www.google.com/" target="_blank">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td>
</tr>
<tr class="active">
     <td>Header Text Color: </td>
     <td><input type="color" name="HeaderTextColor" id="HeaderTextColor"/></td><td><em>Here you can choose the Font color of the Text of the first area of content.</em></td>
</tr>
<!--------------------------------------------->
<tr hidden=""><td hidden=""></td></tr>
<tr><td>
<h2> Body</h2>
</td></tr>
<tr class="active">
     <td>Active Body </td>
     <td><input type="checkbox" name="ActiveBody" value="1"></td><td><em>If Active Body is selected then the second area of content will be visible.</em></td>
</tr>
<tr class="active">
     <td>Body Area Color: </td>
     <td><input type="color" name="BodyAreaColor" id="HeaderAreaColor" value="#eeeeee"/></td><td><em>Here you can choose the background color of the second area of content.</em></td>
</tr>
<tr class="active">
     <td>Body Title: </td>
     <td><input type="text" name="PageBodyTitle" size="50" /></td><td><em>This is the Title of the second area of content.</em></td>
</tr>
<tr class="active">
     <td>Body Title Color: </td>
     <td><input type="color" name="BodyTitleColor" id="BodyTitleColor"/></td><td><em>Here you can choose the Font color of the Title of the second area of content.</em></td>
</tr>
<tr class="active">
     <td>Body Text 1: </td>
     <td><textarea name="PageBodyText1" cols="51" rows="10" ></textarea></td><td><em>Here you can type the first text of the second area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta href="http://www.google.com/" target="_blank"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a href="http://www.google.com/" target="_blank">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td>
</tr>
<tr class="active">
    <td>Body Image 1: </td>	
    <td><input type="hidden" name="size3" value="3500000">
		<input type="file" name="BodyImage1"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	</td>	<td></td>
</tr>
<tr class="active">
     <td>Body Text 2: </td>
     <td><textarea name="PageBodyText2" cols="51" rows="10" ></textarea></td><td><em>Here you can type the second text of the second area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta href="http://www.google.com/" target="_blank"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a href="http://www.google.com/" target="_blank">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td>
</tr>
<tr class="active">
    <td>Body Image 2: </td>	
    <td><input type="hidden" name="size4" value="3500000">
		<input type="file" name="BodyImage2"> <br></e><em>Upload a Photo in gif or jpeg format. If the same file name is uploaded twice it will be overwritten!<br/> You should resize the image with the width and the height you want it to appear in the Area.<br/> The Maxium size of File is 350kb. </em>
	</td>	<td></td>
</tr>
<tr class="active">
     <td>Body Text 3: </td>
     <td><textarea name="PageBodyText3" cols="51" rows="10" ></textarea></td><td><em>Here you can type the third text of the second area of content. In order to put hyperlinks in your text you can put them like this:<br /> &lta href="http://www.google.com/" target="_blank"&gtClick Here&lt/a&gt <br />will have this output: <br /> <a href="http://www.google.com/" target="_blank">Click Here</a><br />
     In order to go to a new line type &ltbr /&gt <br /><br /> You can see an example page if you click <a onclick="window.open('../../../PhaTemplate/Example')"> Here</a></em></td>
</tr>
<tr class="active">
     <td>Body Text Color: </td>
     <td><input type="color" name="BodyTextColor" id="BodyTextColor"/></td><td><em>Here you can choose the Font color of the Text of the second area of content.</em></td>
</tr>
<tr hidden=""><td hidden=""></td></tr>
<tr colspan="2"><td>
			<button TYPE="submit" name="insert" title="Add data to the Database" class="btn btn-success"><span class='glyphicon glyphicon-ok'></span> Create Page</button></td><td></td><td align="right"><a class="btn btn-warning" href="../../../siteManagement.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-arrow-left'></span> Go to Site Management</a></td>
</tr>
</form>
    
</table> 
</div>
</div>
<!-- CHECH IF ANYTHING WAS PRESSED : insert, delete or update -->
<?php
if (isset($_POST['insert'])) {
//This gets all the other information from the form

		$target = "images/";


		$PageName = $_POST['PageName'];
		$PageTitle = $_POST['PageTitle'];
		$ActiveHeader = $_POST['ActiveHeader'];
		$HeaderAreaColor = $_POST['HeaderAreaColor'];
		$PageHeaderTitle = $_POST['PageHeaderTitle'];
		$HeaderTitleColor = $_POST['HeaderTitleColor'];
		$PageHeaderText1 = $_POST['PageHeaderText1'];
		$info1 = basename($_FILES['HeaderImage1']['name']);
		if($info1 != NULL){
			$HeaderImage1 = $safe_site."_".$info1; 
		}
		$target1 = $target . $HeaderImage1;
		$PageHeaderText2 = $_POST['PageHeaderText2'];
		$info2 = basename($_FILES['HeaderImage2']['name']);
		if($info2 != NULL){
			$HeaderImage2 = $safe_site."_".$info2; 
		}
		$target2 = $target . $HeaderImage2;
		$PageHeaderText3 = $_POST['PageHeaderText3'];
		$HeaderTextColor = $_POST['HeaderTextColor'];
		$ActiveBody = $_POST['ActiveBody'];
		$BodyAreaColor = $_POST['BodyAreaColor'];
		$PageBodyTitle = $_POST['PageBodyTitle'];
		$BodyTitleColor = $_POST['BodyTitleColor'];
		$PageBodyText1 = $_POST['PageBodyText1'];
		$info3 = basename($_FILES['BodyImage1']['name']);
		if($info3 != NULL){
		$BodyImage1 = $safe_site."_".$info3; 
		} 
		$target3 = $target . $BodyImage1;
		$PageBodyText2 = $_POST['PageBodyText2'];
		$info4 = basename($_FILES['BodyImage2']['name']);
		if($info4 != NULL){
		$BodyImage2 = $safe_site."_".$info4;
		}
		$target4 = $target . $BodyImage2;
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
		
 if ($PageName=='' || $PageTitle ==''){
        $display_html .= "Required information is missing... fill-in all fields first... ";
 }else { //Writes the information to the database
      $addProductsSqlQuery = "INSERT INTO Pages (Site_ID, PageName, PageTitle, ActiveHeader, HeaderAreaColor, PageHeaderTitle, HeaderTitleColor, PageHeaderText1, HeaderImage1, PageHeaderText2, HeaderImage2, PageHeaderText3, HeaderTextColor, ActiveBody,  BodyAreaColor, PageBodyTitle, BodyTitleColor, PageBodyText1, BodyImage1, PageBodyText2, BodyImage2, PageBodyText3, BodyTextColor, CreationDate, LastUpdate) 
                           VALUES ('$Site_ID', '$PageName', '$PageTitle', '$ActiveHeader', '$HeaderAreaColor', '$PageHeaderTitle', '$HeaderTitleColor', '$PageHeaderText1', '$HeaderImage1', '$PageHeaderText2', '$HeaderImage2', '$PageHeaderText3', '$HeaderTextColor', '$ActiveBody', '$BodyAreaColor', '$PageBodyTitle', '$BodyTitleColor', '$PageBodyText1', '$BodyImage1', '$PageBodyText2', '$BodyImage2', '$PageBodyText3', '$BodyTextColor', NOW(), NOW())";
      $insertResult = mysqli_query($mysqli, $addProductsSqlQuery) or 
                      die ($addProductsSqlQuery . " " .    mysqli_error($mysqli));
					  $_SESSION['newpage']=$PageName;
		$_SESSION['success']=1;
    $_SESSION['display_html'] .= "Page ".$PageName." Added! ";    
     
	 	unset($PageName);
		unset($PageTitle);
		unset($ActiveHeader);
		unset($HeaderAreaColor);
		unset($PageHeaderTitle);
		unset($HeaderTitleColor);
		unset($PageHeaderText1);
		unset($HeaderImage1);
		unset($PageHeaderText2);
		unset($HeaderImage2);
		unset($PageHeaderText3);
		unset($HeaderTextColor);
		unset($ActiveBody);
		unset($BodyAreaColor);
		unset($PageBodyTitle);
		unset($BodyTitleColor);
		unset($PageBodyText1);
		unset($BodyImage1);
		unset($PageBodyText2);
		unset($BodyImage2);
		unset($PageBodyText3);
		unset($BodyTextColor);
		
   
   ///Writes the photo to the server
        if(move_uploaded_file($_FILES['HeaderImage1']['tmp_name'], $target1) )
        {
        //Tells you if its all ok
		if($_SESSION['success']!=0){
		$_SESSION['success']=1;
		}
        $_SESSION['display_html'] .= "The file ". basename($_FILES['HeaderImage1']['name']). " has been uploaded, and your information has been added to the directory. ";
        }
        else if($HeaderImage1 != ''){
        //Gives and error if its not
		$_SESSION['success']=0;
        $_SESSION['display_html'] .= "Sorry, there was a problem uploading". basename($_FILES['HeaderImage1']['name']). ". ";
        }
		
		
		if(move_uploaded_file($_FILES['HeaderImage2']['tmp_name'], $target2) )
        {
        //Tells you if its all ok
		if($_SESSION['success']!=0){
		$_SESSION['success']=1;
		}
        $_SESSION['display_html'] .= "The file ". basename($_FILES['HeaderImage2']['name']). " has been uploaded, and your information has been added to the directory. ";
        }
        else if($HeaderImage2 != ''){
        //Gives and error if its not
		$_SESSION['success']=0;
        $_SESSION['display_html'] .= "Sorry, there was a problem uploading". basename($_FILES['HeaderImage2']['name']). ". ";
        }
		
		
		if(move_uploaded_file($_FILES['BodyImage1']['tmp_name'], $target3) )
        {
        //Tells you if its all ok
		if($_SESSION['success']!=0){
		$_SESSION['success']=1;
		}
        $_SESSION['display_html'] .= "The file ". basename($_FILES['BodyImage1']['name']). " has been uploaded, and your information has been added to the directory. ";
        }
        else if($BodyImage1 != ''){
        //Gives and error if its not
		$_SESSION['success']=0;
        $_SESSION['display_html'] .= "Sorry, there was a problem uploading". basename($_FILES['BodyImage1']['name']). ". ";
        }
		
		
		if(move_uploaded_file($_FILES['BodyImage2']['tmp_name'], $target4) )
        {
        //Tells you if its all ok
		if($_SESSION['success']!=0){
		$_SESSION['success']=1;
		}
        $_SESSION['display_html'] .= "The file ". basename($_FILES['BodyImage2']['name']). " has been uploaded, and your information has been added to the directory. ";
        }
        else if($BodyImage2 != ''){
        //Gives and error if its not
		$_SESSION['success']=0;
        $_SESSION['display_html'] .= "Sorry, there was a problem uploading". basename($_FILES['BodyImage2']['name']). ". ";
        }//end move uploaded files*/
    } //end of writing info to database 
	header ( "refresh:0;" );
} //end of if INSERT button was clicked


?>
</div>
                                              

<?php } else {
	header("Location: ../../../PhaTemplate/Home") ;
}?>

<?php
include "footer.php";
?>
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
if($Site_ID9==1 ){
	if($_SESSION['walkthrough']==3){
	$_SESSION['display_html']='This is the Manage Page of this Site. You can Add, View, Edit and Delete Pages, Buttons, Products and Users. You can also assign your Home Page and your Item Button (button that redirects you to a list of your products). Finally you can promote your site to a Premium site and then view the Orders made from your Site. Press the "Add New Page" Button to create your first page.';
		$_SESSION['success']=1;
}

if($_SESSION['walkthrough']==5){
	$_SESSION['display_html']='Now you can see your first page! You can View, Edit, Delete and Assign it as Home Page. Press the "Make this your Home Page" to Assign this Page as your Home Page.';
		$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==8){
	$_SESSION['display_html']='You have Successfully created your first Button! Now press the "Go to Product Maintenance" to create your first Product.';
		$_SESSION['success']=1;
		
}
if($_SESSION['walkthrough']==12){
	$_SESSION['display_html']='Press "Add New Button" to create your Item Button.';
		$_SESSION['success']=1;
		
}
if($_SESSION['walkthrough']==14){
	$_SESSION['display_html']='Press "Make it Item Button".';
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
		
		if($_SESSION['walkthrough']==3){
	$_SESSION['walkthrough']=4;
	$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==5){
	$_SESSION['walkthrough']=6;
	$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==8){
	$_SESSION['walkthrough']=9;
	$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==12){
	$_SESSION['walkthrough']=13;
	$_SESSION['success']=1;
}
if($_SESSION['walkthrough']==14){
	$_SESSION['walkthrough']=15;
	$_SESSION['success']=1;
}
		} ?></strong>
        
		</div>
		</div>


</h3>


  
   <?php



$sql7 = "select * from Sites where SiteName = '".$safe_site."'";

$result7 = mysqli_query($mysqli, $sql7) or die (mysqli_error($mysqli));
			if(mysqli_num_rows($result7)>0){
	
	while ($rows7 = mysqli_fetch_array($result7)){
		
		
			
			
		$Site_ID7 = $rows7['Site_ID'];
		$User_ID7 = $rows7['User_ID'];
		$SiteName7 = $rows7['SiteName'];
		$Active7 = $rows7['Active'];
		$Rating7 = $rows7['Rating'];
		$Votes7 = $rows7['Votes'];
		$CreationDate7 = $rows7['CreationDate'];
		$LastUpdate7 = $rows7['LastUpdate'];
		




if($User_ID7 = $_SESSION['id'] || $_SESSION['Admin']==1){




		?>
        
		<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      
      
<!--<h2 style="color:blue"> Add New Product</h2>-->

<!--<table width="50%" class="boxbg all-round">-->

<h2><?php /* = basename($_SERVER['PHP_SELF']);
$_SESSION['where'] = $where;*/

echo $SiteName7;
if($Ecommerce != 1){?>   
<?php if($_SESSION['Admin']!=1){?>
                    
<table>
<tr class="active"><td>             
<form style="vertical-align: middle;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="puzzle_john@hotmail.com">
    <input type="hidden" name="item_name" value="PhaTemplate Premium Site">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="amount" value="150">
    <input type="hidden" name="no_shipping" value="0">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="lc" value="AU">
    <input type="hidden" name="bn" value="PP-BuyNowBF">
    <input type="hidden" name="return" value="partheniou.students.acg.edu/successpay.php?SiteName=<?php echo $_SESSION['site'];?>&Order=PhaTemplate">
    <input type="submit" align="middle" class='btn btn-success' style="align: middle;" span class='glyphicon glyphicon-shopping-cart' name="submit" value="Go Premium Now" alt="PayPal - The safer, easier way to pay online.">
    </form>   </td><td  width="10px"></td><td>                  <button type="button" class="btn btn-large btn-warning" onclick="window.open('promotesite.php?SiteName=<?php echo $SiteName7;?>')"><span class='glyphicon glyphicon-question-sign'></span> What is a Premium Site</button></td></tr>
    </table>
    <?php }}?>
<h3>Pages<br/>                                                                                                                              <a class="btn btn-large btn-success" href="../../../addPage.php?SiteName=<?php echo $SiteName7?>"><span class='glyphicon glyphicon-plus'></span> Add New Page</a></h3> 
<br/>

<table>
<?php 
 $sql80 = "Select Page_ID, Site_ID, HomePage, PageName from Pages where Site_ID = '".$Site_ID7."' and HomePage = '1'";
$result80 = mysqli_query($mysqli, $sql80) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result80)>0){
	?>
	<tr class="active">
<td><b>Home Page:                               </b></td>
<td  width="10px"></td>

<?php	while ($rows80 = mysqli_fetch_array($result80)){
		
		$Page_ID80 = $rows80['Page_ID'];
		$Site_ID80 = $rows80['Site_ID'];
		$HomePage80 = $rows80['HomePage'];
		$PageName80 = $rows80['PageName'];
	?>	
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
        <?php //if update button was pressed change all plain text to textfields to enable inline editing 
            if (isset($_POST['homeupdate']) && $_POST['Site_ID']==$Site_ID7) { ?>
        
        <td> 
            <?php
            $PageList = "Select DISTINCT PageName from Pages where Site_ID = '".$Site_ID7."'";
            $PageListResult = mysqli_query($mysqli,$PageList);
            ?>
            <select name="PageName"> <?php
            if (mysqli_num_rows($PageListResult) > 0) {
                while ($data = mysqli_fetch_array($PageListResult)) {
                    $PageName8 = $data['PageName'];
                    ?>
                    <option value="<?php echo $PageName8;?>"  <?php if ($PageName80 == $PageName8) echo "selected"; ?> >
                    <?php echo $PageName8; ?> 
                    </option> 
                <?php
                }					
            }
            ?></select>
        </td>
        <td  width="10px"></td>
        <td><button type="submit" name="dohomeupdate" class='btn btn-success'><span class='glyphicon glyphicon-home'></span> Make this your Home Page</button>
            <input type="hidden" name="Site_ID" value="<?php echo $Site_ID7?>"> 
        <?php } else {  //These products will be displayed in plain text ?>
        <td><?php echo $PageName80; ?></td>
           <td  width="10px"></td>         
        <td><button type="submit" name="homeupdate" class='btn btn-warning'><span class='glyphicon glyphicon-home'></span> Change Home Page</button>
        
        <?php } // end of if update button was pressed ?>
        <input type="hidden" name="Site_ID" value="<?php echo $Site_ID7?>">
        </td>
      </form>
      
       
      </tr>
<?php		
	}?>
	
<?php }
	
?>

	</table>

<br />             
<table class="table" >
<?php $sql2 = "select Page_ID, Site_ID, PageName, PageTitle, ActiveHeader, HeaderAreaColor, PageHeaderTitle, HeaderTitleColor, PageHeaderText1, HeaderImage1, PageHeaderText2, HeaderImage2, PageHeaderText3, HeaderTextColor, ActiveBody, BodyAreaColor, PageBodyTitle, BodyTitleColor, PageBodyText1, BodyImage1, PageBodyText2, BodyImage2, PageBodyText3, BodyTextColor, CreationDate, LastUpdate from Pages where Site_ID = '".$Site_ID7."'";

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
?>

<tr class="active">
<td><?php echo $PageName;?></td><td><button type="button" class="btn btn-large btn-info" onclick="window.open('../../../<?php echo $SiteName7?>/<?php echo $PageName?>')"><span class='glyphicon glyphicon-share-alt'></span> View Page</button>     <a class="btn btn-large btn-warning" href="../../../pageMaintenance.php?SiteName=<?php echo $SiteName7?>&PageName=<?php echo $PageName?>" title="Edit Page"><span class='glyphicon glyphicon-cog'></span></a>            <a data-toggle="modal" href="#myModal3" data-id="<?php echo $PageName;?>" class='open-myModal3 btn btn-danger' title="Delete Page"><span class='glyphicon glyphicon-trash'></span></a></td><td><?php if(mysqli_num_rows($result80)==0){?>
<form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
		  <button type="submit" name="dohomeupdate" class='btn btn-warning'><span class='glyphicon glyphicon-home'></span> Make this your Home Page</button>
          <input type="hidden" name="PageName" value="<?php echo $PageName?>">
          <input type="hidden" name="Site_ID" value="<?php echo $Site_ID?>">
          </form>
	<?php  }?> </td>
</tr>
		
		
		
	<?php }?>
	
<?php }
?>
	</table>
<br />




<h3>Buttons         <br />                                                                                                                     <a class="btn btn-large btn-success" href="../../../addButton.php?SiteName=<?php echo $SiteName7?>"><span class='glyphicon glyphicon-plus'></span> Add New Button</a></h3>


<table class="table" >
<?php $sql81 = "Select Button_ID, Site_ID, ButtonName, ItemButton from Buttons where Site_ID = '".$Site_ID7."' and ItemButton = '1'";
$result81 = mysqli_query($mysqli, $sql81) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result81)>0){
	?>
	<tr class="active">
<td><b>Item Button: </b></td>
<td  width="10px"></td>

<?php	while ($rows81 = mysqli_fetch_array($result81)){
		
		$Button_ID81 = $rows81['Button_ID'];
		$Site_ID81 = $rows81['Site_ID'];
		$ButtonName81 = $rows81['ButtonName'];
		$ItemButton81 = $rows81['ItemButton'];
	?>	
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
        <?php //if update button was pressed change all plain text to textfields to enable inline editing 
            if (isset($_POST['itembuttonupdate']) && $_POST['Site_ID']==$Site_ID7) { ?>
        
        <td> 
            <?php
            $ItemButtonList = "Select DISTINCT ButtonName from Buttons where Site_ID = '".$Site_ID7."'";
            $ItemButtonListResult = mysqli_query($mysqli,$ItemButtonList);
            ?>
            <select name="ButtonName"> <?php
            if (mysqli_num_rows($ItemButtonListResult) > 0) {
                while ($data = mysqli_fetch_array($ItemButtonListResult)) {
                    $ButtonName8 = $data['ButtonName'];
                    ?>
                    <option value="<?php echo $ButtonName8;?>"  <?php if ($ButtonName81 == $ButtonName8) echo "selected"; ?> >
                    <?php echo $ButtonName8; ?> 
                    </option> 
                <?php
                }					
            }
            ?></select>
        </td>
        <td  width="10px"></td>
        <td><button type="submit" name="doitembuttonupdate" class='btn btn-success'><span class='glyphicon glyphicon-cog'></span> Make this your Product Button</button>
        <input type="hidden" name="Site_ID" value="<?php echo $Site_ID7?>"> 
        <?php } else {  //These products will be displayed in plain text ?>
        <td><?php echo $ButtonName81; ?></td>
           <td  width="10px"></td>         
        <td><button type="submit" name="itembuttonupdate" class='btn btn-warning'><span class='glyphicon glyphicon-cog'></span> Change Product Button</button>
        <?php } // end of if update button was pressed ?>
        <input type="hidden" name="Site_ID" value="<?php echo $Site_ID7?>">
        </td>
      </form>
      
       
      </tr>
<?php		
	}?>
	
<?php }
	
?>

	</table>

<br />             

                <p style="font-size:11px">If your Item Button is a Drop Down Button then in the Drop Down Menu you will have one link for each Product Type. If it is not a Drop Down Button, it will just redirect you to a list of all the Products.</p>
      <br/>      

<table class="table" border="1">
<?php $sql8 = "select * from Buttons where Site_ID = '".$Site_ID7."' order by ButtonPosition";
$result8 = mysqli_query($mysqli, $sql8) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result8)>0){
	?>
	<thead>
<td><b>Active</b></td>
<td><b>Button Name</b></td>
<td><b>Drop Down</b></td>
<td><b>Page Link</b></td>
<td><b>Position</b></td>
<td><b>Actions</b></td>
<td><b>Extra Actions</b></td>
</thead>
<tbody>
<?php	while ($rows8 = mysqli_fetch_array($result8)){
		
		$Button_ID = $rows8['Button_ID'];
		$Site_ID = $rows8['Site_ID'];
		$ButtonActive = $rows8['ButtonActive'];
		$ButtonName = $rows8['ButtonName'];
		$DDButton = $rows8['DDButton'];
		$ButtonLink = $rows8['ButtonLink'];
		$ButtonPosition = $rows8['ButtonPosition'];
		$ItemButton = $rows8['ItemButton'];
	?>	
		<tr class="active">
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
        <?php //if update button was pressed change all plain text to textfields to enable inline editing 
            if (isset($_POST['update']) && $_POST['Button_ID']==$Button_ID) { ?>
        <td><input type="checkbox" name="ButtonActive" <?php if ($ButtonActive == 1){?> checked="checked" <?php } ?> value="1"></td>
        <td><input type="text" name="ButtonName" value="<?php echo $ButtonName?>" required="required"></td>
        <td><input type="checkbox" name="DDButton" <?php if ($DDButton == 1){?> checked="checked" <?php } ?> value="1"></td>
        <td> 
            <?php
            $PageList = "Select DISTINCT PageName from Pages where Site_ID = '".$Site_ID."'";
            $PageListResult = mysqli_query($mysqli,$PageList);
            ?>
            <select name="ButtonLink"> <?php
            if (mysqli_num_rows($PageListResult) > 0) {
                while ($data = mysqli_fetch_array($PageListResult)) {
                    $PageName8 = $data['PageName'];
                    ?>
                    <option value="<?php echo $PageName8;?>"  <?php if ($ButtonLink == $PageName8) echo "selected"; ?> >
                    <?php echo $PageName8; ?> 
                    </option> 
                <?php
                }					
            }
            ?></select>
        </td>
        <td><input type="number" name="ButtonPosition" value="<?php echo $ButtonPosition?>" required="required"></td>
                
        <td><button type="submit" name="doupdate" title="Save Button" class='btn btn-success'><span class='glyphicon glyphicon-cog'></span></button>
            <input type="hidden" name="Button_ID" value="<?php echo $Button_ID?>"> 
        <input type="hidden" name="Site_ID" value="<?php echo $Site_ID?>"> 
        <?php } else {  //These products will be displayed in plain text ?>
        <td><?php echo $ButtonActive; ?></td>
        <td><?php echo $ButtonName; ?></td>
        <td><?php echo $DDButton; ?></td>
        <td><?php echo $ButtonLink; ?></td>
        <td><?php echo $ButtonPosition; ?></td>
             
        <td><button type="submit" name="update" title="Edit Button" class='btn btn-warning'><span class='glyphicon glyphicon-cog'></span></button>
        <?php } // end of if update button was pressed ?>
        <input type="hidden" name="Button_ID" value="<?php echo $Button_ID?>">
        <input type="hidden" name="Site_ID" value="<?php echo $Site_ID?>">
        <a data-toggle="modal" href="#myModal2" data-id="<?php echo $ButtonName;?>" class='open-myModal2 btn btn-danger' title="Delete Button"><span class='glyphicon glyphicon-trash'></span></a>
        <?php if(mysqli_num_rows($result81)==0){?>
		  <button type="submit" name="doitembuttonupdate" class='btn btn-warning'><span class='glyphicon glyphicon-cog'></span> Make it Item Button</button>
          <input type="hidden" name="ButtonName" value="<?php echo $ButtonName?>">
	<?php  }?> 
      </form>
      <td>
	  <?php if($DDButton == 1 && $ItemButton == 0){?>
      <a class="btn btn-large btn-warning" href="../../../editDDMenu.php?SiteName=<?php echo $safe_site;?>&Button=<?php echo $Button_ID?>"><span class='glyphicon glyphicon-cog'></span> Edit Drop Down Menu</a>  
      <?php }?>
      </td> 
      
      </tr>
<?php		
	}?>
	
<?php }
	
?>
</tbody>
	</table>

<br/>
<h3>Users          <br />                                                                                                                                                                    <a class="btn btn-warning" href="../../../userMaintenance.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-share-alt'></span> Go to User Maintenance</a></h3>

<br/>

<h3>Products          <br />                                                                                                                                                                   <?php
				$minav = 3;
                $prsql7 = "SELECT Availability FROM Products where Site_ID = '".$Site_ID7."' and Availability = '0'";
		$prresult7 = mysqli_query($mysqli, $prsql7) or die (mysqli_error($mysqli));
		
		if(mysqli_num_rows($prresult7)>0){
			?>
				<a class="btn btn-large btn-danger" href="../../../productMaintenance.php?SiteName=<?php echo $SiteName7;?>"><span class='glyphicon glyphicon-share-alt'></span> Go to Product Maintenance</a>
				<?php }else{
					$prsql71 = "SELECT Availability FROM Products where Site_ID = '".$Site_ID7."' and Availability < '3'";
		$prresult71 = mysqli_query($mysqli, $prsql71) or die (mysqli_error($mysqli));
		
		if(mysqli_num_rows($prresult71)>0){?>
				<a class="btn btn-large btn-warning" href="../../../productMaintenance.php?SiteName=<?php echo $SiteName7;?>"><span class='glyphicon glyphicon-share-alt'></span> Go to Product Maintenance</a>
				
				<?php }else{?>
                <a class="btn btn-large btn-info" href="../../../productMaintenance.php?SiteName=<?php echo $SiteName7;?>"><span class='glyphicon glyphicon-share-alt'></span> Go to Product Maintenance</a>
					<?php }}?>
                
                <br/>
                <p style="font-size:11px">If you have at least one Product that its availability is 0 units (out of stock) then the button will be Red, else if there is at least one Product that its availability is &lt; 3 units then the Button will be Yellow, else it will be blue.</p>


<br/>
<br/>
<?php if($_SESSION['Admin']!=1){?>
<h3>Orders          <br />            <?php if($Ecommerce==1 ){
$prsql73 = "SELECT Status FROM Corders where Site_ID = '".$Site_ID7."' and Status = '1'";
		$prresult73 = mysqli_query($mysqli, $prsql73) or die (mysqli_error($mysqli));
		
		if(mysqli_num_rows($prresult73)>0){ ?>                                                                                                                                                        <a class="btn btn-warning" href="../../../orders.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-share-alt'></span> Go to Orders</a>
        <?php }else{?>
         <a class="btn btn-info" href="../../../orders.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-share-alt'></span> Go to Orders</a>
        <?php }?>
        </h3>
<p style="font-size:11px">If there are new Orders that are not Shipped yet, the button will be Yellow, else it will be Blue.</p>
<?php }else{?>
<form style="vertical-align: middle;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="puzzle_john@hotmail.com">
    <input type="hidden" name="item_name" value="PhaTemplate Premium Site">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="amount" value="150">
    <input type="hidden" name="no_shipping" value="0">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="lc" value="AU">
    <input type="hidden" name="bn" value="PP-BuyNowBF">
    <input type="hidden" name="return" value="partheniou.students.acg.edu/successpay.php?SiteName=<?php echo $_SESSION['site'];?>&Order=PhaTemplate">
    <input type="submit" align="middle" class='btn btn-success' style="align: middle;" span class='glyphicon glyphicon-shopping-cart' name="submit" value="Go Premium Now" alt="PayPal - The safer, easier way to pay online.">
    </form> 
    <p style="font-size:11px">You need a Premium Site to Have Orders.</p>
    <?php }}?>
<br/>
<br/>
<h3 align="center"><a class="btn btn-warning" href="../../../sites.php"><span class='glyphicon glyphicon-arrow-left'></span> Go to Sites</a></h3>
</div>
</div>
<?php


} }}else{
header ('Location:  ../../../sites.php');
}
?>
<?php 
if (isset($_POST['delete'])) { 
	$pName = $_POST['pName'];
	$prName = $_POST['prName'];
	if($pName == $prName){
     $Button_ID = $_POST['Button_ID'];
     $deleteButtonSqlQuery = "delete from Buttons where ButtonName= '" . $prName . "' and Site_ID = '".$Site_ID7."'";
     $deleteResult = mysqli_query($mysqli, $deleteButtonSqlQuery) or die ($deleteButtonSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
    $_SESSION['success'] = 1;
	$_SESSION['display_html'] .="   The Button ".$prName."was deleted Successfully.";}else{
	$_SESSION['display_html'] .="The Button Name you typed did not match the Name of the Button you tried to delete.";}
	header ( "refresh:0;" );
} //end of if DELETE button was clicked

if (isset($_POST['deletepage'])) { 
	$pName = $_POST['pName'];
	$prName = $_POST['prName'];
	if($pName == $prName){
     
     $deletePageSqlQuery = "delete from Pages where PageName= '" . $prName . "' and Site_ID = '".$Site_ID7."'";
     $deleteResult = mysqli_query($mysqli, $deletePageSqlQuery) or die ($deletePageSqlQuery . " " .    mysqli_error($mysqli));
    //$display_html .= "Product " . $productID . " Deleted!";
    $_SESSION['success'] = 1;
	$_SESSION['display_html'] .="   The Page ".$prName." was deleted Successfully.";}else{
	$_SESSION['display_html'] .="The Page Name you typed did not match the Name of the Page you tried to delete.";}
	header ( "refresh:0;" );
} //end of if DELETE button was clicked

if (isset($_POST['doupdate'])) { 
     $Button_ID = $_POST['Button_ID'];
     $Site_ID = $_POST['Site_ID'];
     $ButtonActive = $_POST['ButtonActive'];
     $ButtonName = $_POST['ButtonName'];
     $DDButton = $_POST['DDButton'];
     $ButtonLink = $_POST['ButtonLink'];
	 $ButtonPosition = $_POST['ButtonPosition'];
	 
     $updateButtonSqlQuery = "update Buttons set ButtonActive = '$ButtonActive', ButtonName= '$ButtonName',DDButton ='$DDButton', ButtonLink= '$ButtonLink', ButtonPosition= '$ButtonPosition' where Button_ID = '$Button_ID'";
    $updateResult = mysqli_query($mysqli, $updateButtonSqlQuery) or die ($updateButtonSqlQuery . " " .    mysqli_error($mysqli));
    $_SESSION['display_html'] .= "Button " . $ButtonName . " Updated!";
	$_SESSION['success'] = 1;
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked


if (isset($_POST['dohomeupdate'])) { 
     $PageName = $_POST['PageName'];
     $Site_ID = $_POST['Site_ID'];
	 
	 

	 
     $resetHomePageSqlQuery = "update Pages set HomePage = '0' where Site_ID = '$Site_ID'";
    $resetResult = mysqli_query($mysqli, $resetHomePageSqlQuery) or die ($resetHomePageSqlQuery . " " .    mysqli_error($mysqli));
	 
     $updateHomePageSqlQuery = "update Pages set HomePage = '1'where Site_ID = '$Site_ID' and PageName = '$PageName'";
    $updateResult = mysqli_query($mysqli, $updateHomePageSqlQuery) or die ($updateHomePageSqlQuery . " " .    mysqli_error($mysqli));
	if($_SESSION['walkthrough']==6){
	$_SESSION['display_html']= $PageName . ' is set as your Home Page! Now Press the "Add New Button" Button to create your first Button.';
		$_SESSION['success']=1;
		$_SESSION['walkthrough']=7;
}else{
	$_SESSION['success'] = 1;
    $_SESSION['display_html'] .=  $PageName . " is set as your Home Page!";
}
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked

if (isset($_POST['doitembuttonupdate'])) { 
     $ButtonName = $_POST['ButtonName'];
     $Site_ID = $_POST['Site_ID'];
	 
     $resetHomePageSqlQuery = "update Buttons set ItemButton = '0' where Site_ID = '$Site_ID'";
    $resetResult = mysqli_query($mysqli, $resetHomePageSqlQuery) or die ($resetHomePageSqlQuery . " " .    mysqli_error($mysqli));
	 
     $updateHomePageSqlQuery = "update Buttons set ItemButton = '1'where Site_ID = '$Site_ID' and ButtonName = '$ButtonName'";
    $updateResult = mysqli_query($mysqli, $updateHomePageSqlQuery) or die ($updateHomePageSqlQuery . " " .    mysqli_error($mysqli));
	$_SESSION['success'] = 1;
	if($_SESSION['walkthrough']==15){
	$_SESSION['display_html']=  $ButtonName . ' is set as your Item Button! <br/>  Congratulations!! You have ended the Walkthrough Successfully!';
		$_SESSION['success']=1;
		$_SESSION['walkthrough']=0;
}else{
    $_SESSION['display_html'] .=  $ButtonName . " is set as your Item Button!";
}
	header ( "refresh:0;" );
	 //$display_html .= $updateProductsSqlQuery;
} //end of if doupdate button was clicked
?>


<?php } else {?>
	<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
      
      <p>Please                                                                                             <a class="btn btn-large btn-info" href="../../../login.php">Log In</a>               or                          <a class="btn btn-large btn-info" href="../../../register.php?SiteName=<?php echo $safe_site?>&PageName=<?php echo $safe_page?>">Register</a>                       to Create a New Site!</p>
      
      </div>
      </div>
<?php }?>
<div class="container theme-showcase" role="main">
<?php
include "footer.php";
?>
</div>
<?php
	ob_start();
	session_start();
	include "include2.php";
	connectDB();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="../../../images/favicon.ico">
<title><?php echo $page_title;?></title>

<!-- Bootstrap core CSS -->
<link href="../../../dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap theme -->
<link href="../../../dist/css/bootstrap-theme.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="../../../dist/css/theme.css" rel="stylesheet">
<script charset='utf-8' src="../../../js/jquery-1.11.0.min.js"></script>
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
<link href="../../../css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../../../js/star-rating.js" type="text/javascript"></script>
<link href="../../../Modalcss.css" rel="stylesheet" type="text/css" />
<script src="../../../js/jquery-1.11.0.min.js"></script>
<style>
.Info {
	display: none;
	list-style-type: none;
}
ul {
	list-style-type: none;
}
li {
	list-style-type: none;
}

/*--------------------------------*/

#lightbox .modal-content {
	display: inline-block;
	text-align: center;
}
#lightbox .close {
	opacity: 1;
	color: rgb(255, 255, 255);
	background-color: rgb(25, 25, 25);
	padding: 5px 8px;
	border-radius: 30px;
	border: 2px solid rgb(255, 255, 255);
	position: absolute;
	top: -15px;
	right: -55px;
	z-index: 1032;
}
.searchbar {
	padding-top: 5px;
}
</style>
<style>
h1 {
	font-size: 3em;
	line-height: 1.2em;
	margin-top: 0.4em;
	margin-bottom: 0.4em;
	color: #FFF;
	padding: 0px 24px;
}
#vacations ul li {
	-moz-box-sizing: border-box;
	background: none repeat scroll 0% 0% rgba(37, 43, 48, 0.5);
	float: left;
	min-height: 7em;
	margin: 1em;
	padding: 0.5em;
	position: relative;
	vertical-align: top;
	width: 24.25%;
	list-style: none outside none;
}
.price {
	font-size: 1em;
	font-weight: bold;
	line-height: 1.2em;
	color: #191D20;
	background: none repeat scroll 0% 0% #82ADD8;
	border: 1px solid #191D20;
	display: block;
	padding: 10px;
	text-align: center;
	cursor: pointer;
}
.up {
	bottom: 30px;
}
</style>
<style type="text/css">
.boxbg {
	background-color: #a2c7d0
}
.all-round {
	border-radius: 1em;
	-moz-border-radius: 1em;
	-webkit-border-radius: 1em;
}
.dropdownhover:hover .dropdown-menu {
    display: block;
 }
 .dropdown-menu{
 float:right;
 }
 
 
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
</style>

<!-- Just for debugging purposes. Dont actually copy this line! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body role="document">
<div align="left"> 
  <script>
	function showInfo(){
		$(this).closest('.information').find('.Info').slideToggle("slow");
	}
	$(document).ready(function(){
		$('.information').on('click', 'button', showInfo);});
		
		//-------------------------------
		$(document).ready(function() {
    var $lightbox = $('#lightbox');
    
    $('[data-target="#lightbox"]').on('click', function(event) {
        var $img = $(this).find('img'), 
            src = $img.attr('src'),
            alt = $img.attr('alt'),
            css = {
                'maxWidth': $(window).width() - 100,
                'maxHeight': $(window).height() - 100
            };
    
        $lightbox.find('.close').addClass('hidden');
        $lightbox.find('img').attr('src', src);
        $lightbox.find('img').attr('alt', alt);
        $lightbox.find('img').css(css);
    });
    
    $lightbox.on('shown.bs.modal', function (e) {
        var $img = $lightbox.find('img');
            
        $lightbox.find('.modal-dialog').css({'width': $img.width()});
        $lightbox.find('.close').removeClass('hidden');
    });
});

//----------------------------------

$( document ).ready(function() {
         $( ".tti" ).click(function() { //id area
            var $img = $(this).find('.image');
             $img.toggle("slow");
             });        
    });
	//----------------------------------------
	
	$( document ).ready(function() {
$('#loginform input').keypress(function (e) {
 if (e.which === 13) {
  e.preventDefault();
  $('#loginform').submit();
 }
})});


$(document).on("click", ".open-myModal2", function () {
     var varName = $(this).data('id');
     $(".modal-body #pName").val( varName );
});

$(document).on("click", ".open-myModal3", function () {
     var varName = $(this).data('id');
     $(".modal-body #pName").val( varName );
});
//-------------------------------
		</script>
  <?php  
$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
$_SESSION['site'] = $safe_site;
$_SESSION['page'] = $safe_page;
$where = basename($_SERVER['PHP_SELF']);
$_SESSION['where'] = $where;
$_SESSION['a']=0;


?>
<?php
$sql1 = "select * from Sites where SiteName = '".$safe_site."'";
$result1 = mysqli_query($mysqli, $sql1) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result1)>0){
	
	while ($rows1 = mysqli_fetch_array($result1)){
		$Site_ID = $rows1['Site_ID'];
		$User_ID = $rows1['User_ID'];
		$SiteName = $rows1['SiteName'];
		$Active = $rows1['Active'];
		$Rating = $rows1['Rating'];
		$Votes = $rows1['Votes'];
		$Ecommerce = $rows1['Ecommerce'];
		$CreationDate = $rows1['CreationDate'];
		$LastUpdate = $rows1['LastUpdate'];

	}
}

$sqlh = "select * from Pages where Site_ID = '".$Site_ID."' and HomePage = '1'";
$resulth = mysqli_query($mysqli, $sqlh) or die (mysqli_error($mysqli));

if(mysqli_num_rows($resulth)>0){
	
	while ($rowsh = mysqli_fetch_array($resulth)){
		$_SESSION['home']=$rowsh['PageName'];
	}
}


$sqlsite1234 = "select MAX(Order_ID) AS LatestOrderID FROM Orders where Site_ID = '".$Site_ID."' and User_ID = '".$_SESSION['id']."'";
$resultsite1234 = mysqli_query($mysqli, $sqlsite1234) or die (mysqli_error($mysqli));
if(mysqli_num_rows($resultsite1234)>0){
	while ($rowssite1234 = mysqli_fetch_array($resultsite1234)){
		$LatestOrderID1234 = $rowssite1234['LatestOrderID'];
		$_SESSION['LatestOrderID1234'] = $LatestOrderID1234;
		}
}

 /*$query1 = "SELECT * FROM Orders WHERE User_ID = '".$_SESSION['id']."' and Status = '0' and Site_ID = '".$Site_IDp."'";
 
$res1 = mysqli_query($mysqli, $query1) or die (mysqli_error($mysqli));
*/
$query2345 = "SELECT o.productID as product_id, Image, Name, Price , Quantity FROM Products p join Orders o on o.productID = p.productID join Corders co on co.Order_ID = o.Order_ID WHERE o.User_ID = '".$_SESSION['id']."' and o.Site_ID = '".$Site_ID."' and co.Status = '0' and co.Order_ID = '".$LatestOrderID1234."'";
$res2345 = mysqli_query($mysqli, $query2345) or die (mysqli_error($mysqli));
if(mysqli_num_rows($res2345)>0){
	$nproducts2345= 0;		
		while ($row2345 = mysqli_fetch_array($res2345)){
			$Quantity2345 = $row2345['Quantity'];
			$nproducts2345=$nproducts2345+$Quantity2345;
		}
}
?>
<?php if($_SESSION['Admin'] != 0){
	
$sql5 = "select * from Sites where User_ID = '".$_SESSION['id']."'";
	
}
?>
  
  <!-- Fixed navbar --> 
</div>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand"><?php echo $SiteName?></a> </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <?php
		  $sql3 = "select * from Buttons where Site_ID = '".$Site_ID."' order by ButtonPosition";
$result3 = mysqli_query($mysqli, $sql3) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result3)>0){
	
	while ($rows3 = mysqli_fetch_array($result3)){
		$Button_ID = $rows3['Button_ID'];
		$Site_ID = $rows3['Site_ID'];
		$ButtonActive = $rows3['ButtonActive'];
		$ButtonName = $rows3['ButtonName'];
		$DDButton = $rows3['DDButton'];
		$ButtonLink = $rows3['ButtonLink'];
		$ButtonPosition = $rows3['ButtonPosition'];
		$ItemButton = $rows3['ItemButton'];
				
	if($ButtonActive == 1){	
		$sqldd = "select * from DDButtons where Button_ID = '".$Button_ID."' order by DDButtonPosition";
			$resultdd = mysqli_query($mysqli, $sqldd) or die (mysqli_error($mysqli));
			
			
		if($DDButton == 1 && $ItemButton == 1){?>
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $ButtonName;?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
          <?php
			 $sqlitemb = "select DISTINCT Type from Products where Site_ID = '".$Site_ID."' order by Type";
			$resultitemb = mysqli_query($mysqli, $sqlitemb) or die (mysqli_error($mysqli));
			if(mysqli_num_rows($resultitemb)>0){
				?><li><a href="../../../Products.php?SiteName=<?php echo $safe_site;?>">All</a></li><?php
				while ($rowsitemb = mysqli_fetch_array($resultitemb)){
					$Typeb = $rowsitemb['Type'];
					?>
       			<li><a href="../../../Products.php?SiteName=<?php echo $safe_site;?>&Type=<?php echo $Typeb?>"><?php echo $Typeb;?></a></li>
        <?php 
					
				}?>
               
        <?php
			}?>
			 </ul>
        </li>
        </li>
		<?php }else if($DDButton == 1 && mysqli_num_rows($resultdd)>0 && $ItemButton != 1){
				?>
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $ButtonName;?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <?php
			$sql4 = "select * from DDButtons where Button_ID = '".$Button_ID."' order by DDButtonPosition";
			$result4 = mysqli_query($mysqli, $sql4) or die (mysqli_error($mysqli));
			if(mysqli_num_rows($result4)>0){
	
				while ($rows4 = mysqli_fetch_array($result4)){
					$DDButton_ID = $rows4['DDButton_ID'];
					$Button_ID2 = $rows4['Button_ID'];
					$DDButtonActive = $rows4['DDButtonActive'];
					$DDButtonName = $rows4['DDButtonName'];
					$DDButtonLink = $rows4['DDButtonLink'];
					$DDButtonPosition = $rows4['DDButtonPosition'];
					
					if($DDButtonActive == 1){
					?>
            <li><a href="../../<?php echo $safe_site;?>/<?php echo $DDButtonLink;?>"><?php echo $DDButtonName;?></a></li>
            <?php }
			}?>
          </ul>
        </li>
        </li>
        <?php }
		}else{
			
			if($ItemButton==1){
				if(basename($_SERVER['PHP_SELF'])=='Products.php'){?>
				<li class="active"><a href="../../../Products.php?SiteName=<?php echo $safe_site;?>"><?php echo $ButtonName;?></a></li>
        <?php }else{?>
       			<li class="inactive"><a href="../../../Products.php?SiteName=<?php echo $safe_site;?>"><?php echo $ButtonName;?></a></li>
        <?php }
			}else{
				if($safe_page == $ButtonLink){?>
                    <li class="active"><a href="../../<?php echo $safe_site;?>/<?php echo $ButtonLink;?>"><?php echo $ButtonName;?></a></li>
                    <?php }else{?>
                    <li class="inactive"><a href="../../<?php echo $safe_site;?>/<?php echo $ButtonLink;?>"><?php echo $ButtonName;?></a></li>
                    <?php }
		}}
	}
	}
}?>
       
        </li>
        
      </ul>
      <ul class="nav navbar-nav navbar-right" >
      <?php
	  if((isset($_SESSION['user']) && $_SESSION['Site_ID'] == $Site_ID && $Ecommerce==1) || ($_SESSION['id']==$User_ID && $Ecommerce==1)){
		  if ($current_page == "Cart"){?>
          <?php if($_SESSION['id']==$User_ID){?>
        <li class="active"><a href="../../../cart.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-shopping-cart'></span> Sample Cart  <?php echo $nproducts2345?></a></li>
        <?php }else{?>
			<li class="active"><a href="../../../cart.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-shopping-cart'></span> My Cart <?php echo $nproducts2345?></a></li>
			<?php }}
		  else{?>
          <?php if($_SESSION['id']==$User_ID){?>
        <li class="inactive"><a href="../../../cart.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-shopping-cart'></span> Sample Cart  <?php echo $nproducts2345?></a></li>
        <?php }else{?>
			<li class="inactive"><a href="../../../cart.php?SiteName=<?php echo $safe_site;?>"><span class='glyphicon glyphicon-shopping-cart'></span> My Cart  <?php echo $nproducts2345?></a></li>
		<?php }}}
		  ?>
        </li>
        <?php
		  if ($current_page == "login"){?>
        <li class="active">
          <?php }  else { ?>
        <li class="inactive">
          <?php } ?>
          <?php if ((isset($_SESSION['user']) && $_SESSION['Site_ID'] == $Site_ID) || $_SESSION['Admin']==1 || $_SESSION['id']==$User_ID || (basename($_SERVER['PHP_SELF'])=='siteManagement.php' && $_SESSION['Admin']==2) || (basename($_SERVER['PHP_SELF'])=='sites.php' && $_SESSION['Admin']==2) || (basename($_SERVER['PHP_SELF'])=='createSite.php' && $_SESSION['Admin']==2) || (basename($_SERVER['PHP_SELF'])=='editProductColumns.php' && $_SESSION['Admin']==2)) { ?>
        <?php
		   if($_SESSION['Admin']!=0){?>
           
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['user']?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            
            
            <li class="dropdown-header">Admin Stuff</li>
            <li class="divider"></li>
            <li><a href="../../../createSite.php">Create New Site</a></li>
        <li><a href="../../../sites.php">Go to Sites</a></li>
            <?php 
$result5 = mysqli_query($mysqli, $sql5) or die (mysqli_error($mysqli));
			if(mysqli_num_rows($result5)>0){
	
	while ($rows5 = mysqli_fetch_array($result5)){
		$Site_ID5 = $rows5['Site_ID'];
		$User_ID5 = $rows5['User_ID'];
		$SiteName5 = $rows5['SiteName'];
		$Active5 = $rows5['Active'];
		$Rating5 = $rows5['Rating'];
		$Votes5 = $rows5['Votes'];
		$CreationDate5 = $rows5['CreationDate'];
		$LastUpdate5 = $rows5['LastUpdate'];
		?>
        
		<li class="dropdown-header"><?php echo $SiteName5?></li>
		<li><a href="../../../siteManagement.php?SiteName=<?php echo $SiteName5?>">Go to Site Management</a></li>
        <!--<li class="dropdown-submenu"> <a href="#" tabindex="-1" ><i class="icon-chevron-left"></i> Go to Site Management Category</a>
                  <ul class="dropdown-menu" style="left: -100%; right:100%">
                      <li><a href="../../../addPage.php?SiteName=<?php //echo $SiteName5?>">Add New Page</a></li>
                      <li><a href="../../../pageMaintenance.php?SiteName=<?php //echo $SiteName5?>">Go to Page Maintenance</a></li>
                      <li><a href="../../../addButton.php?SiteName=<?php //echo $SiteName5?>">Add New Button</a></li>
                      <li><a href="../../../userMaintenance.php.php?SiteName=<?php //echo $SiteName5?>">Go to User Maintenance</a></li>
                      <li><a href="../../../productMaintenance.php?SiteName=<?php //echo $SiteName5?>">Go to Product Maintenance</a></li>
                      <li><a href="../../../orders.php?SiteName=<?php //echo $SiteName5?>">Go to Orders</a></li>
              	</ul>
        </li>-->
        <li class="divider"></li>
	<?php }
}?>

            <li><a href="../../../manageacc.php?SiteName=<?php echo $safe_site;?>">Manage Account</a></li>
            <li><a href="../../../logout.php">Logout</a></li>
          </ul>
        </li>
        <?php } else { ?>
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['user']?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
          <?php if($Ecommerce==1){?>
          <li><a href="../../../myorder.php?SiteName=<?php echo $safe_site;?>">Orders</a></li>
          <?php }?>
            <li><a href="../../../manageacc.php?SiteName=<?php echo $safe_site;?>">Manage Account</a></li>
           
            <li><a href="../../../logout.php">Logout</a></li>
          </ul>
        </li>
        <?php }?>
        <?php } else { ?>
        <a data-toggle="modal" href="#myModal">Login</a>
        <?php } ?>
        
      </ul>
    </div>
    <!--/.nav-collapse --> 
    
  </div>
</div>
<div id="myModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="../../../dologin2.php?SiteName=<?php echo $safe_site?>&PageName=<?php echo $safe_page?>" id="loginform">
        <div class="modal-header"> <a class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></a>
          <h4 class="modal-title" id="myModalLabel">Login</h4>
        </div>
        <div class="modal-body">
       
          <h4>Enter Login Details</h4>
          Email:
          <input type="text" name="Email" id="Email"/>
          Password:
          <input type="password" name="pass" id="pass" />
        </div>
        <div class="modal-footer">
          <div class="btn-group">
            <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary">Login</button>
          </div>
          <h3>Not a member yet? Register <a href="../../../register.php?SiteName=<?php echo $safe_site?>&PageName=<?php echo $safe_page?>">Here!</a></h3>
        </div>
      </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dalog --> 
</div>
<!-- /.modal -->
<div id="myModal2" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="post" action="<?php $_SERVER[PHP_SELF]?>" id="deleteProduct">
        <div class="modal-header"> <a class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></a>
          <h4 class="modal-title" id="myModal2Label"><?php if(basename($_SERVER['PHP_SELF'])=='manageacc.php'){?>Change Password<?php }else{?>Delete Confirmation<?php }?></h4>
        </div>
        <div class="modal-body">
        <?php if(basename($_SERVER['PHP_SELF'])=='manageacc.php'){?>
        <table>
        <tr><td>
        Old Password </td><td width="10px"></td><td>
        	<input type="hidden" name="pName" id="pName" value=""/>
          	<input type="password" name="prName" id="prName" required/>
            </td></tr>
            <tr><td>
            New Password </td><td width="10px"></td><td>
          	<input type="password" name="pass" id="prName" required/>
            </td></tr>
            <tr><td>
            Confirm New Password </td><td width="10px"></td><td>
          	<input type="password" name="passcon" id="prName" required/>
            </td></tr>
        </table>
        <?php }else{?> 
          <?php if(basename($_SERVER['PHP_SELF'])=='userMaintenance.php'){?>  
          Type the Email of the User you want to delete:   
          <?php }else{?>    
          Type the Name of the Record you want to delete:
          <?php }?>
          
           <input type="hidden" name="pName" id="pName" value=""/>
          <input type="text" name="prName" id="prName" required/>
          
          <?php }?>
        </div>
        <div class="modal-footer">
          <div class="btn-group">
          
          <?php if(basename($_SERVER['PHP_SELF'])=='manageacc.php'){?>
          <button type="submit" name="changepass"  class="btn btn-warning"><span class="glyphicon glyphicon-cog"></span> Change Password</button>                                                                         <button class="btn" data-dismiss="modal">Cancel</button>          
          <?php }else{?>
          	<button type="submit" name="delete"  class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>                                                                         <button class="btn" data-dismiss="modal">Cancel</button>
            <?php }?>
          </div>
          
        </div>
      </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dalog --> 
</div>
<!-- /.modal -->
<div id="myModal3" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="post" action="<?php $_SERVER[PHP_SELF]?>" id="deletePage">
        <div class="modal-header"> <a class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></a>
          <h4 class="modal-title" id="myModal3Label"><?php if(basename($_SERVER['PHP_SELF'])=='userMaintenance.php' || basename($_SERVER['PHP_SELF'])=='manageacc.php' || basename($_SERVER['PHP_SELF'])=='login.php'){?>Reset Password Confirmation<?php }else{?>Delete Confirmation<?php }?></h4>
        </div>
        <div class="modal-body">
           <?php if(basename($_SERVER['PHP_SELF'])=='userMaintenance.php' || basename($_SERVER['PHP_SELF'])=='manageacc.php' || basename($_SERVER['PHP_SELF'])=='login.php'){
			if(basename($_SERVER['PHP_SELF'])=='userMaintenance.php'){?>           
           Type the Email of the User that needs his/her Password reseted:
           <input type="hidden" name="pName" id="pName" value=""/>
          <input type="text" name="prName" id="prName" required/>
          <?php }else if(basename($_SERVER['PHP_SELF'])=='login.php'){?>
          <table>
          <tr><td>
          Type your Email: </td><td>
          <input type="hidden" name="pName" id="pName" value=""/>
          <input type="text" name="prName" id="prName" required/></td></tr>
          <tr><td>
          Type your Last Name: </td><td><input type="text" name="llllName" id="llllName" required/>
          </td></tr>
          </table>
          <?php }else{?>
          Type your Last Name in order to reset your Password
          <input type="hidden" name="pName" id="pName" value=""/>
          <input type="text" name="prName" id="prName" required/>
          <?php }?>
           
          
        </div>
        <div class="modal-footer">
          <div class="btn-group">
          	<button type="submit" name="resetpass"  class="btn btn-warning"><span class="glyphicon glyphicon-flash"> Reset Password</span></button>
           
           <?php }else{?>
          Type the Name of the Page you want to delete:
          
           <input type="hidden" name="pName" id="pName" value=""/>
          <input type="text" name="prName" id="prName" required/>
          
        </div>
        <div class="modal-footer">
          <div class="btn-group">
          	<button type="submit" name="deletepage"  class="btn btn-danger"><span class="glyphicon glyphicon-trash"> Delete</span></button>                       <?php }?>                                                  <button class="btn" data-dismiss="modal">Cancel</button>
            
          </div>
          
        </div>
      </form>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dalog --> 
</div>
<!-- /.modal -->


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
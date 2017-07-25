<?php
session_start();
include "header.php";
connectDB();
$safe_site = mysqli_real_escape_string($mysqli, $_GET['SiteName']);
$safe_page = mysqli_real_escape_string($mysqli, $_GET['PageName']);
$page_title = 'Go Premium';
$current_page = 'promotesite';

$sql9 = "select * from Users where ID = '".$_SESSION['id']."'";

$result9 = mysqli_query($mysqli, $sql9) or die (mysqli_error($mysqli));
if(mysqli_num_rows($result9)>0){
	while ($rows9 = mysqli_fetch_array($result9)){
		$Site_ID9 = $rows9['Site_ID'];
	}
}
if($Site_ID9==1){
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
	}?>
	<div class="container theme-showcase" role="main">
    	<div class="jumbotron">
        	<h2 style="color:#800000;" align="center">Premium Site Perks.</h2>
            <p align="center">Global Clientele.</p>
            <p align="center">Fully dynamic cart.</p>
            <p align="center">Check out with <span style="color:#00F;">Paypal</span>.</p>
            <p align="center">Your customers can see their purchases in a personal page.</p>
            
            <div   text-align:"center" align="center" style="vertical-align: middle;">                                                                                <form style="vertical-align: middle;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
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
    <input type="hidden" name="return" value="partheniou.students.acg.edu/successpay.php">
    <input type="submit" align="middle" class='btn btn-success' style="align: middle;" span class='glyphicon glyphicon-shopping-cart' name="submit" value="Go Premium Now" alt="PayPal - The safer, easier way to pay online.">
    </form></div><br /><br />
    <p align="center" style="font-size:11px">You will need to create a <span style="color:#00F;">Paypal</span> account. If you do not have one press <a href="https://www.paypal.com">Here</a>!</p>
        </div>
    </div>
    
	
	
<?php	
}
?>
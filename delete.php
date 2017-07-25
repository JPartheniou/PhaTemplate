<?php
session_start();
include "header.php";
include "include.php";

connectDB();

$safe_cid = mysqli_real_escape_string($mysqli, $_POST ['id']);


$mysqlstatemnt = "DELETE FROM cCourses WHERE ID = " .$safe_cid;
echo $mysqlstatemnt;
$result = mysqli_query ($mysqli, $mysqlstatemnt) or die (mysqli_error($mysqli));

echo "<h1> Class $safe_id has been successfully deleted! </h1>"; ?>

You will be redirected to Courses in <span id="seconds">3</span>.

<script>
	var seconds = 3;
	setInterval(
		function(){
			document.getElementById('seconds').innerHTML = --seconds;
		}, 1000
		);
		</script>
        
<?
echo '<META http-equiv="refresh" Content="3;
URL=admincourses.php">';
exit;
include "footer.php";
?>
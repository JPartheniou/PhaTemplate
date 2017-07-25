<?php include 'header.php';
include 'include.php';
?>
<div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
<h2 style align="center">You are not authorized to access this page!!</h2>

You will be redirected to Courses in <span id="seconds">3</span>.
</div>
</div>
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
URL=../index.php">';
exit;
include "footer.php";
?>
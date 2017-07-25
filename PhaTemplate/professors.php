<?php 

include "header.php";
include "include.php";

connectDB();

$sql = "select ID, concat(lastName, \" \", firstName) as FullName from cProfessors";
$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));
?>
<form action="insertCourse.php">
Course Code: <input type="text" name"code" id"code"/> <br>
Course Name: <input type="text" name="name" id="name"/> <br />
	Credits: <input type="text" name="credits" id="credits"/> <br />
Professor: 
<?php
if(mysqli_num_rows($result)>0){
	echo "<select name=\"professor\">";
	while ($rows = mysqli_fetch_array($result)){
	$ID = $rows['ID'];
	$FullName = $rows['FullName'];
	
	echo "<option value=\"" .$id. "\">" .$FullName;
	echo "</option>";
	}
	echo "</select>";
}?>
<br>
<input type="submit", value="Submit">
<input type="reset", value="Reset">
</form><?php
include "footer.php";
	?>
	
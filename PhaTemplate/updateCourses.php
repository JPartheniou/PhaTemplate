<?php
session_start;
include "include.php";
include "header.php";
$page_title="Update Courses";
$current_page="updateCourses";

connectDB();

$course_id = $_GET['course_id'];


$sqlProf = "select ID, concat(lastName, \" \", firstName) as FullName from cProfessors order by lastName";
$resultProf = mysqli_query ($mysqli, $sqlProf) or die(mysqli_error($mysqli));

$sqlcourses = "select * from cCourses where ID = " .$course_id;
$resultcourses = mysqli_query($mysqli, $sqlcourses) or die(mysqli_error($mysqli));

if(mysqli_num_rows($resultcourses)==1){
	while($rowscourses=mysqli_fetch_array($resultcourses)){
	$course_id = $rowscourses['ID'];
	$course_code = $rowscourses['course_code'];
	$course_title = $rowscourses['courseTitle'];
	$course_credits = $rowscourses['credits'];
	$prof_id = $rowscourses['prof_ID'];	
	}
}
?>
<form method="post" action="doinsertCourse.php">
<input type="hidden" name="id" value="<?php echo $course_id; ?>" > </br>
Course Code: <input type="text" name="code" id="code" value="<?php echo $course_code; ?>" />  <br>
Course Title: <input type="text" name="name" id="name" value="<?php echo $course_title; ?>" size="100" /> <br />
	Credits: <input type="text" name="credits" id="credits" value="<?php echo $course_credits; ?>" /> <br />
Professor: 
<?php
if(mysqli_num_rows($resultProf)>0){
	
	echo "<select name=\"professor\">";
	while ($rows = mysqli_fetch_array($resultProf)){
	$ID = $rows['ID'];
	$FullName = $rows['FullName'];
	if($ID == $prof_id) {
	echo "<option value=\"" .$ID. "\" selected>".$FullName;
	echo "</option>";
	}
	echo "<option value=\"" .$ID. "\">" .$FullName;
	
    
	echo "</option>"; 
	}
	echo "</select>";
}?>
<br>
<input type="submit", value="Submit">
<input type="reset", value="Reset">
</form>
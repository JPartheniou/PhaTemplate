<?php
session_start();
include "header.php";
include "include.php";



connectDB();

$sql = "select ID, course_code, courseTitle, Professor, image from CourseInfo order by course_code";

if($_SESSION['Admin']==1){



$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));?>
<table class="table table-striped">
<thead>
<tr class="active"><th>Course Code</th><th>Course Title</th><th>Professor</t></tr>
</thead>
    <tbody>
    <?php
if(mysqli_num_rows($result)>0){
	while ($rows = mysqli_fetch_array($result)){
	$course_id = $rows['ID'];
	$course_code = $rows['course_code'];
	$courseTitle = $rows['courseTitle'];
	$Professor = $rows['Professor'];
	$image = $rows['image'];
	?>
    
    <tr class="active"><form method="post" action="delete.php"><td><input type="hidden" name="id" id="id" value="<?php echo $course_id;?>"/> <a href="updateCourses.php?course_id=<?php echo $course_id;?>"> <?php echo $course_code; ?></a></td><td><?php echo $courseTitle;?></td><td><?php echo $Professor;?></td><td><img src="images/<?php echo $image?>" width="40" height="60"></td><td><input type="submit", value="Delete"></td></form></tr>
    
    
    <?php
	}
}
?>
</tbody>
	</table>
<?php
include "footer.php";
}
else{
	echo "Shoo! Dis iz admin stuffz";
}
?>
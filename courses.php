<?php
session_start();
include "header.php";




$sql = "select course_code, courseTitle, Professor from CourseInfo order by course_code";

$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));?>
<table class="table table-striped">
<thead>
<tr class="active"><th>Course Code</th><th>Course Title</th><th>Professor</t></tr>
</thead>
    <tbody>
    <?php
if(mysqli_num_rows($result)>0){
	while ($rows = mysqli_fetch_array($result)){
	$course_code = $rows['course_code'];
	$courseTitle = $rows['courseTitle'];
	$Professor = $rows['Professor'];
	?>
    
    <tr class="active"><td><?php echo $course_code;?></td><td><?php echo $courseTitle;?></td><td><?php echo $Professor;?></td></tr>
    
    
    <?php
	}
}?>
</tbody>
	</table>
<?php
include "footer.php";
?>
<?php
session_start();
include "include.php";

//check that the posted field have values
if ((empty ( $_POST ['code'])) || (empty($_POST['name']))|| (empty($_POST['credits']))){
	
	echo "empty fields";
}

connectDB();

$safe_code = mysqli_real_escape_string($mysqli, $_POST ['code']);
$safe_name = mysqli_real_escape_string($mysqli, $_POST ['name']);
$safe_credits = mysqli_real_escape_string($mysqli, $_POST ['credits']);
$safe_profid = mysqli_real_escape_string($mysqli, $_POST ['professor']);

$mysqlstatemnt2 = "insert into cCourses (course_code, courseTitle, credits, prof_ID) values ('" .$safe_code. "','" .$safe_name. "','" .$safe_credits. "', '".$safe_profid."')";

$result=mysqli_query ($mysqli, $mysqlstatemnt2) or die (mysqli_error($mysqli));

header ("Location: index.php");

?>
<html>
    <head> <title>Welcome to Courses Administration</title></head>
    <style type="text/css">
    .boxbg { background-color:#a2c7d0 }
    .all-round {
        border-radius:1em;
        -moz-border-radius:1em;
        -webkit-border-radius:1em;
        }
    </style>
<body>
    <!-- JQUERY import -->
    <script src="js/jquery-1.11.0.min.js"></script>
    <script>
    $( document ).ready(function() {
         $( ".tti" ).click(function() { //id area
            var $img = $(this).find('.image');
             $img.toggle("slow");
             });        
    });
    </script>
<h1> Course Maintenance </h1>
<?php //Connect to DB information
$mysqli = mysqli_connect("localhost", "parthe_root", "!root123#", "parthe_class");
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	}
?>

<h2 style="color:blue"> Add New Course</h2>
<table width="50%" class="boxbg all-round">
<form method="post" action="<?php $_SERVER[PHP_SELF]?>" enctype="multipart/form-data" name="addCourseForm">
<tr class="active"> 
     <td>Course Code </td>
     <td><input type="text" name="course_code" size="10" required/> </td>
</tr>	
<tr class="active">
     <td>Course Title</td>
     <td><input type="text" name="course_title" size="50" required/></td>
</tr>	
<tr class="active">            
    <td>Credits</td>
    <td><input type="number" name="credits" min="1" max="4"/></td>
</tr>	
<tr class="active">
    <td>Professor</td>
    <td>
        <?php

            $sql = 'Select professor_id, professor_name from professors order by professor_name';
            $result2 = mysqli_query($mysqli,$sql);
            ?>
            <select name="professor_id"> <?php
            if (mysqli_num_rows($result2) > 0) {

                while ($data = mysqli_fetch_array($result2)) {
                            $cid = $data['professor_id'];
                            $cname = $data['professor_name']; ?>
                            <option value="<?php echo $cid; ?>">  <?php echo $cname; ?> </option> <?php
                }					
            }
    ?>		
                </select>
    </td>
 </tr>
<tr class="active">
    <td>Text Book Image</td>	
    <td><input type="hidden" name="size" value="350000">
		<input type="file" name="filename"> <br></e><em>Upload a Photo of the textbook in gif or jpeg format. If the same file name is uploaded twice it will be overwritten! Maxium size of File is 35kb. </em>
	</td>	</tr>		
<tr colspan="2"><td>
			<input TYPE="submit" name="insert" title="Add data to the Database" value="Insert Course"/> </td>
</tr>
</form>
    </div>
</table> 
<!-- CHECH IF ANYTHING WAS PRESSED : insert, delete or update -->
<?php
if (isset($_POST['insert'])) {
//This gets all the other information from the form
$course_code = $_POST['course_code'];
$course_title = $_POST['course_title'];
$credits = $_POST['credits'];
$professor_id = $_POST['professor_id'];
//This is the directory where images will be saved
$target = "images/";
$target = $target . basename( $_FILES['filename']['name']);
$textbook_image = ($_FILES['filename']['name']);

 if ($course_code=='' || $course_title =='' || $credits =='' )
        $display_html .= "Required information is missing... fill-in all fields first... ";
 else { //Writes the information to the database
      $addCoursesSqlQuery = "INSERT INTO courses (course_code,course_title, credits, professor_id, textbook_image) 
                           VALUES ('$course_code','$course_title',$credits,$professor_id, '$textbook_image')";
      $insertResult = mysqli_query($mysqli, $addCoursesSqlQuery) or 
                      die ($addCoursesSqlQuery . " " .    mysqli_error($mysqli));

    $display_html .= "Course Added ";    
     unset($id); unset($course_code); unset($course_title); 
     unset($credits); unset($professor_id);  unset($textbook_image);
   
    //Writes the photo to the server
        if(move_uploaded_file($_FILES['filename']['tmp_name'], $target))
        {
        //Tells you if its all ok
        $display_html .= "The file ". basename( $_FILES['filename']['name']). " has been uploaded, and your information has been added to the directory";
        }
        else {
        //Gives and error if its not
        $display_html .= "Sorry, there was a problem uploading your file.";
        }//end move uploaded files
    } //end of writing info to database 
} //end of if INSERT button was clicked

if (isset($_POST['delete'])) { 
     $id = $_POST['id'];
     $deleteCoursesSqlQuery = "delete from courses where id = $id";
     $deleteResult = mysqli_query($mysqli, $deleteCoursesSqlQuery) or die ($deleteCoursesSqlQuery . " " .    mysqli_error($mysqli));
    $display_html .= "Course " . $id . " Deleted!";
} //end of if DELETE button was clicked

if (isset($_POST['doupdate'])) { 
     $id = $_POST['id'];
     $course_code = $_POST['course_code'];
     $course_title = $_POST['course_title'];
     $credits = $_POST['credits'];
     $professor_id = $_POST['professor_id'];
     $textbook_image = $_POST['textbook_image'];
     $updateCoursesSqlQuery = "update courses set course_code = '$course_code', course_title= '$course_title',credits =$credits, professor_id= $professor_id, textbook_image='$textbook_image' where id = $id";
    $updateResult = mysqli_query($mysqli, $updateCoursesSqlQuery) or die ($updateCoursesSqlQuery . " " .    mysqli_error($mysqli));
    $display_html .= "Course " . $course_code . " Updated!";
} //end of if doupdate button was clicked
?> 
    
<br>
<hr>
    <h2 style="color:blue">  Manage Existing Courses</h2>
<br>
    
<?php //DISPLAY LIST OF ALL COURSES
$sql = "select * from courses";
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

if(mysqli_num_rows($result)>0){ 
?>
<table border="1">
<thead>
<td><b>Course Code</b></td>
<td><b>Title</b></td>
<td><b>Credits</b></td>
<td><b>Professor</b></td>
<td><b>Image FileName</b></td>
<td><b>Actions</b></td>
</thead>
<tbody>
<?php while($rows=mysqli_fetch_array($result)){
    	$course_id = $rows['id'];
		$course_code = $rows['course_code'];
		$course_title = $rows['course_title'];
		$credits = $rows['credits'];
		$professor_id = $rows['professor_id'];
        $textbook_image = $rows['textbook_image'];
        ?>
        <tr class="active">
        <form method="post" id="action" action="<?php $_SERVER[PHP_SELF]?>"> 
        <?php //if update button was pressed change all plain text to textfields to enable inline editing 
            if (isset($_POST['update']) && $_POST['id']==$course_id) { ?>
        <td><input type="text" name="course_code" value="<?php echo $course_code?>"></td>
        <td><input type="text" name="course_title" value="<?php echo $course_title?>"></td>
        <td><input type="text" name="credits" value="<?php echo $credits?>"></td>
        <td> 
            <?php
            $profList = 'Select professor_id, professor_name from professors order by professor_name';
            $profListResult = mysqli_query($mysqli,$profList);
            ?>
            <select name="professor_id"> <?php
            if (mysqli_num_rows($profListResult) > 0) {
                while ($data = mysqli_fetch_array($profListResult)) {
                    $cid = $data['professor_id'];
                    $cname = $data['professor_name']; ?>
                    <option value="<?php echo $cid;?>"  <?php if ($cid == $professor_id) echo "selected"; ?> >
                    <?php echo $cname; ?> 
                    </option> 
                <?php
                }					
            }
            ?></select>
        </td>
        <td><input type="text" name="textbook_image" value="<?php echo $textbook_image?>"> </td>
        <td><input type="submit" name="doupdate" value="save"/>
        <?php } else {  //These courses will be displayed in plain text ?>
        <td><?php echo $course_code; ?> </a></td>
        <td><?php echo $course_title; ?></td>
        <td><?php echo $credits; ?></td>
        <td>  <?php //display Professor_name instead of id
		$profQuery = "SELECT * from professors where professor_id = $professor_id";   
		$profResult = mysqli_query($mysqli,$profQuery);
		$profData = mysqli_fetch_array($profResult);
        $profName = $profData['professor_name']; 
		echo  $profName; ?> </td> 
        <td> <div id="tti<?php echo $course_id ?>" class="tti"><?php echo $textbook_image; ?> <img id="img<?php echo $course_id ?>" class="image" style="display: none; position: relative;" src="<?php echo "images/".$textbook_image; ?>" /> </div> </td>
        <td><input type="submit" name="update" value="update"/>
        <?php } // end of if update button was pressed ?>
        <input type="hidden" name="id" value="<?php echo $course_id?>">
        <input type="submit" name="delete" value="delete"/></td> 
      </form>  
      </tr>
     <?php } //end of while loop that displays courses ?>
        </tbody>
	</table>
<?php } //end of if mysqli_num_rows >0 ?>

                                                             
<h3 style="color:red">
System Messages :
<?php echo $display_html ?>
</h3>
</body>
</html>
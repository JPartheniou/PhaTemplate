<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>Krajee JQuery Plugins - &copy; Kartik</title>
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/star-rating.js" type="text/javascript"></script>
    </head>
    <body>
        <h1>Bootstrap Star Rating Example with courses</h1>
        
        
        <?php
            $mysqli = mysqli_connect("localhost", "parthe_root", "!root123#", "parthe_class");
            if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
            }
			
			if (isset ($_POST['rate'])){
				$id = $_POST['id'];
				$userRate = $_POST['userRate'];
				echo 'Course id : ' .$id. 'User Rate : ' .$userRate;?> <br> <?php
				
				$updateCoursesSqlQuery = "update courses set votes=votes+1, ratings = ratings + $userRate where id = $id";
            $updateResult = mysqli_query($mysqli, $updateCoursesSqlQuery) or die ($updateCoursesSqlQuery . " " .    mysqli_error($mysqli));
            $display_html .= "Course Rated!";
			
			
			}

            //Select all data
            $sql = "select * from courses";

            $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
             while($rows=mysqli_fetch_array($result)){
                    $course_code = $rows['course_code'];
                    $course_title = $rows['course_title'];
                    $votes = $rows['votes'];
                    $ratings = $rows['ratings'];
                    $rating = $ratings/$votes;
					$roundrating = round($rating);
					$id = $rows['id'];

                echo $course_title . " " . $rating . "<BR/>";?>
				<form method="post" action="<?php PHP_SELF ?>">
        <input name="userRate" id="userRate" type="number" class="rating" min="1" max="5" step="1" value="<?php echo $roundrating ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button name="rate" class="btn btn-primary" >Submit</button>
        <button class="btn btn-default" type="reset">Reset</button>
        </form>
           <?php }
		   
		   echo $display_html;
            ?>
       
        <input id="input-22" type="number" class="rating" disabled=true>
    </body>
</html>



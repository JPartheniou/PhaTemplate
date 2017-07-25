<?php session_start(); ?>
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
        <hr>
        <?php
            $mysqli = mysqli_connect("localhost", "parthe_root", "!root123#", "parthe_class");
            if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
            }

        if (isset($_POST['rate'])) {   
             $id = $_POST['id'];
            
           if (isset($_SESSION["CoursesRated"][$id]))  {
              $display_html = "Course $id has already been Rated!";
            } else {
            $_SESSION['CoursesRated'][$id]=1;
             //setcookie("course", $id , time()+3600*24); //cookie expires in 24 hours
             //echo $HTTP_COOKIE_VARS["course"];
             $userRate = $_POST['userRate'];
             $updateCoursesSqlQuery = "update courses set votes=votes+1, ratings = ratings + $userRate where id = $id";
            $updateResult = mysqli_query($mysqli, $updateCoursesSqlQuery) or die ($updateCoursesSqlQuery . " " .    mysqli_error($mysqli));
            $display_html .= "Course Rated!";
           }
        } //end of if doupdate button was clicked


            //Select all data
            $sql = "select * from courses";
            $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
             while($rows=mysqli_fetch_array($result)){
                    $id = $rows['id']; 
                    $course_code = $rows['course_code'];
                    $course_title = $rows['course_title'];
                    $votes = $rows['votes'];
                    $ratings = $rows['ratings'];
                    $rating = round($ratings/$votes);

                echo "<h3>" . $course_title . " Average rating :" . $rating . "</h3>";
                ?>
                <form method="POST" action="<?php PHP_SELF ?>">
                <?php  if (isset($_SESSION["CoursesRated"][$id])) { ?>
                 <input name="userRate" type="number" class="rating" data-size="xs" min="1" max="5" step="1" value="<?php echo $rating ?>" disabled=true>
                <?php } else { ?>
                 <input name="userRate" type="number" class="rating" data-size="xs" min="1" max="5" step="1" value="<?php echo $rating ?>">
                 <button name="rate" class="btn btn-primary btn-xs">Submit</button> 
                  <?php } ?>
                 <input type="hidden" name="id" value="<?php echo $id; ?>">
        </form>
        <?php
            }
            ?>
  <hr>  
    <h3> <?php echo $display_html;  ?></h3>
    <hr>      
        <a href="logout.php"> Click here to logout</a>
    </body>
</html>







 

  
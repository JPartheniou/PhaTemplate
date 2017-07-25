<?php
if ($_GET['randomId'] != "pFGww8gPItVWyIH0Mwdc6VYTtUlvsGJ2R7bKmkRuh7Mf6gnArcXO1xAA1vGMgjIr") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  

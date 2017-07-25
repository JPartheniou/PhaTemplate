<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<h1>My first php </h1>
<?php
$x = 5;
$y = 6.7;
$d = "hello";
echo "My <b>x</b> variable is : " . $x;
echo "<br/>";
echo "My <b>y</b> variable is : " . $y;
echo "<br/>";

$myarray[0] = "Maira";
$myarray[1] = "Kostas";
$myarray[2] = "Hellen";

for ($i=0; $i<count ($myarray); $i++){
print ($myarray[$i] . "<br/>");
}
foreach ($myarray as $row => $value){
print ("$row has the value $value" . "<br/>");
}
?>
</body>
</html>
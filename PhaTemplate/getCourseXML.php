<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>XML to FOMRATTED  TABLE </title>
</head>

<style>
table
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
border-collapse:collapse;
}

td
{
width:200px;
font-size:1em;
border:1px solid black;
padding:3px 7px 3px 7px;  /*top right bottom left*/
}

.title 
{
width:100px;
font-size:1.1em;
text-align:right;
background-color:#66F;
color:#ffffff;
}
</style>
<body>
<?php
$q=$_GET["course"];

$xmlDoc = new DOMDocument();
$xmlDoc->load("course_catalog.xml");

$x=$xmlDoc->getElementsByTagName('TITLE');

for ($i=0; $i<$x->length; $i++)
{
//Process only element nodes
if ($x->item($i)->nodeType==1)
  {
//  if ($x->item($i)->childNodes->item(0)->nodeValue == $q)
    {
    $y=($x->item($i)->parentNode);    
	echo("<table>");
	$course=($y->childNodes);

				for ($n=0;$n<$course->length;$n++)
				{ 
				if ($course->item($n)->nodeType==1)
				  {
				  echo("<tr>");
				  echo("<td class='title'>" . $course->item($n)->nodeName . "</td> ");
				  echo("<td>" . $course->item($n)->childNodes->item(0)->nodeValue . "</td> ");
				  echo("</tr>");
				  }
				  
				}   
		echo("</tr> </table> </br>");	
			
    }
  }
}


echo("</table>");
?>

</body>
</html>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Professors</title>
</head>

<style>
table
{
font-family:Tahoma, Geneva, sans-serif;
border-collapse:collapse;
}

td
{
width:100px;
font-size:1em;
border:1px solid black;
padding:4px 20px 4px 20px;  /*top right bottom left*/
}

.title 
{
width:50px;
font-size:1.1em;
text-align:right;
background-color:#09F;
color:#ffffff;
}
</style>
<body>
<h2>Professors</h2>
<?php
$q=$_GET["professor"];

$xmlDoc = new DOMDocument();
$xmlDoc->load("professors.xml");

$x=$xmlDoc->getElementsByTagName('ID');

for ($i=0; $i<$x->length; $i++)
{
//Process only element nodes
if ($x->item($i)->nodeType==1)
  {
//  if ($x->item($i)->childNodes->item(0)->nodeValue == $q)
    {
    $y=($x->item($i)->parentNode);    
	echo("<table>");
	$professor=($y->childNodes);

				for ($n=0;$n<$professor->length;$n++)
				{ 
				if ($professor->item($n)->nodeType==1)
				  {
				  echo("<tr>");
				  echo("<td class='title'>" . $professor->item($n)->nodeName . "</td> ");
				  echo("<td class='content'>" . $professor->item($n)->childNodes->item(0)->nodeValue . "</td> ");
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
<?php
include 'header.php';
include 'include.php';
connectDB();

$my_file = 'products.xml';
$handle = fopen($my_file, 'w') or die ('Cannot open file: ' .$my_file);




$data = '<Products>';
fwrite ($handle, $data);

$sql = "select * from Products order by Type";

$result = mysqli_query($mysqli, $sql) or die (mysqli_error($mysqli));

if(mysqli_num_rows($result)>0){
	while ($rows = mysqli_fetch_array($result)){
		$data = '<Product>';
		fwrite ($handle, $data);
		
			$productID = $rows['productID'];
			$data = '<productID>' .$productID. '</productID>';
			fwrite ($handle, $data);
			
			$Name = $rows['Name'];
			$data = '<Name>' .$Name. '</Name>';
			fwrite ($handle, $data);
			
			$Type = $rows['Type'];
			$data = '<Type>' .$Type. '</Type>';
			fwrite ($handle, $data);
			
			$ShortDesc = $rows['ShortDesc'];
			$data = '<ShortDesc>' .$ShortDesc. '</ShortDesc>';
			fwrite ($handle, $data);
			
			$Desc = $rows['Description'];
			$data = '<Description>' .$Description. '</Description>';
			fwrite ($handle, $data);
			
			$Price = $rows['Price'];
			$data = '<Price>' .$Price. '</Price>';
			fwrite ($handle, $data);
			
			$Votes = $rows['Votes'];
			$data = '<Votes>' .$Votes. '</Votes>';
			fwrite ($handle, $data);
			
			$Rating = $rows['Rating'];
			$data = '<Rating>' .$Rating. '</Rating>';
			fwrite ($handle, $data);
			
			$ratings = round($Rating/$Votes);
			$data = '<ratings>' .$ratings. '</ratings>';
			fwrite ($handle, $data);
			
		$data = '</Product>' ;
		fwrite ($handle, $data);
	}
}
$data = '</Products>' ;
		fwrite ($handle, $data);
fclose($handle);


?>
<html>
<head>Products XML</head>
<body><a href="products.xml">Click here to view generated xml.</a></body>
<footer></footer>
</html>
<?php
include 'footer.php';
?>
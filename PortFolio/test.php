<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Test moteur de recherche</title>
</head>

	

 <?php
$con = mysqli_connect('localhost', 'root', '') or die(mysqli_error($con)); 
$queried = mysql_real_escape_string($_POST[$con 'query']); // always escape
$keys = explode(" ",$queried);
$sql = "SELECT * FROM documents WHERE titre LIKE '%$queried%' ";
$result = mysqli_query($sql);
?>
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<body>
</body>
</html>
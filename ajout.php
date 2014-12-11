<?php 
echo "coloc : ".$_GET['c'];
echo "<br>sens : ".$_GET['s'];
echo "<br>montant : ".$_GET['m'];
echo "<br>libelle : ".$_GET['l'];

	$db=mysqli_connect("localhost","root","","browniemoneymanagement");
	mysqli_query($db,"INSERT INTO counts(coloc, sens, montant, libelle, date) VALUES ('" . $_GET['c'] . "', '" . $_GET['s'] . "', '" . $_GET['m'] . "', '" . $_GET['l'] . "', curdate())");

    mysqli_close($db);
    
	header('Location: index.php');	
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>

</body>
</html>
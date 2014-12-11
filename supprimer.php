<?php 

	echo $_GET['id'];
	$db = mysqli_connect("localhost","root","","browniemoneymanagement");

	mysqli_query($db,"DELETE FROM counts WHERE id=" . $_GET['id']);

	mysqli_close($db);

	header('Location: index.php');
?>
<?php
session_start();
include("connection.php");
include ("links.php");

if(isset($_POST["uName"]))
{
	$sql ="INSERT into users(name) VALUES ('".$_POST["uName"]."')";

	if($connect->query($sql))
		header('Location: index.php');
	else 
		echo "Error. Please, try again.";
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ajax App</title>
</head>
<body>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Enregistre ton nom</h4>
			</div>
			<div class="modal-body">
				<form action="register.php" method="POST">
					<p>Nom</p>
					<input type="text" name="uName" id="uName" class="form-control" autocomplete="off">
					<br>
					<input type="submit" name="submit" class="btn btn-primary" style="float: right;" value="OK">					
				</form>
			</div>
			
		</div>
		
	</div>
</body>
</html>
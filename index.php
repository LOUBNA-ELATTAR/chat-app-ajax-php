<?php
session_start();
include("connection.php");
include ("links.php");
if(isset($_GET["id"]))
{
	$_SESSION["id"] = $_GET["id"];
	header("location : chat.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AJAX App</title>
</head>
<body>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Veuillez choisir un compte</h4>
			</div>
			<div class="modal-body">
				<ol>
					<?php
						$users = mysqli_query($connect, "SELECT * FROM users");
						while ($user = mysqli_fetch_assoc($users))
						{
							echo '<li>
									<a href="index.php?id='.$user["id"].'">'.$user["name"].'</a>
								  </li>
							';
						}
					?>
				</ol>
				<a href="register.php" style="float: right;">Register here</a>
				
			</div>
			
		</div>
		
	</div>

</body>
</html>
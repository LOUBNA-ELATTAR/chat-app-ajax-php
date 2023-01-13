<?php
session_start();
include("connection.php");
include ("links.php");

$users = mysqli_query($connect, "SELECT * from users where id='".$_SESSION["id"]."' ")
		or die ("Failed to query database". mysql_error() );
		$user = mysqli_fetch_assoc($users);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ajax App</title>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
				<p>Hi <?php echo $user["name"]; ?></p>
				<input type="text" id="fromUser" value=<?php echo $user["id"]; ?> hidden/>
				<p>Envoyer un message à :</p>
				<ul>
					<?php
						$msgs = mysqli_query($connect, "SELECT * from users")
						or die ("Failed to query database". mysql_error() );
						while ($msg = mysqli_fetch_assoc($msgs))
						{
							echo '<li><a href="?toUser='.$msg["id"].'">'.$msg["name"].'</a></li>';
						}
					?>
				</ul>
				<a href="index.php"><-- Retour</a>
			</div>
			<div class="col-md-4">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4>
								<?php
									if(isset($_GET["toUser"])){
										$userName = mysqli_query($connect, "SELECT * from users where id ='" .$_GET["toUser"]."' ")
										or die ("Failed to query database". mysql_error() );
										$uName = mysqli_fetch_assoc($userName);
										echo '<input type="text" value ='.$_GET["toUser"].' id="toUser" hidden/>';
										echo $uName["name"];
									} 
									else{
										$userName = mysqli_query($connect, "SELECT * FROM users")
										or die ("Failed to query database". mysql_error() );
										$uName = mysqli_fetch_assoc($userName);
										$_SESSION['toUser'] = $uName["id"];
										echo '<input type="text" value ='.$_SESSION["toUser"].' id="toUser" hidden/>';
										echo $uName["name"];
									}
								?>
							</h4>
						</div>
						<div class="modal-body" id="msgBody" style="height: 400px; overflow-y: scroll; overflow-x: hidden;">
							<?php 
							if(isset($_GET["toUser"]))
								$chats = mysqli_query($connect, "SELECT * FROM messages WHERE (FromUser = '".$_SESSION["id"]."' AND ToUser = '".$_GET["toUser"]."' ) OR (FromUser = '".$_GET["toUser"]."' AND ToUser = '".$_SESSION["id"]."')")     
								or die ("Failed to query database". mysql_error() );
							
							else
								$chats = mysqli_query($connect, "SELECT * from messages where (FromUser = '".$_SESSION["id"]."' AND ToUser = '".$_SESSION["toUser"]."' ) OR (FromUser = '".$_SESSION["toUser"]."' AND ToUser = '".$_SESSION["id"]."')")     
								or die ("Failed to query database". mysql_error() );
							
								while ($chat = mysqli_fetch_assoc($chats)){
									if ($chat["FromUser"]== $_SESSION["id"])
										echo "
											<div style ='text-align:right;'>
												<p style = 'background-color:lightblue; word-wrap : break-word; display: inline-block; padding:5px; border-radius:10px; max-width : 70%;'>
													".$chat["Message"]."
												</p>
											</div>
											"; 
									else 
										echo "
											<div style ='text-align:left;'>
												<p style = 'background-color:yellow; word-wrap : break-word; display: inline-block; padding:5px; border-radius:10px; max-width : 70%;'>
													".$chat["Messsage"]."
												</p>
											</div>
											"; 
								}
							?>
						</div>
						<div class="modal-footer">
							<textarea id="message" class="form-control" style="height:70px;"></textarea>
							<button id="send" class="btn btn-primary" style="height:70%">Envoyer</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
		$("#send").on("click", function(){
			$.ajax({
				url:"insertMessage.php",
				method:"POST",
				data:{
					fromUser:$("#fromUser").val(),
					toUser:$("#toUser").val(),
					message:$("#message").val()
				},
				dateType:"text",
				success: function(data){
					$("#message").val("");
				}
			});
		});

		setInterval(function(){
			$.ajax({
				url:"realTimeChat.php",
				method:"POST",
				data:{
					fromUser:$("#fromUser").val(),
					toUser:$("#toUser").val(),
				},
				dateType:"text",
				success: function(data){
					$("#msgBody").html(data);
				}
			});
		},700);
	});
</script>
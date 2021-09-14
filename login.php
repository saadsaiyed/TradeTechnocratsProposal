<?php 
	include "php/DBConnection.php";
	
	$redirectingURL = './index.php';
	if(isset($_GET['location']))
		$redirectingURL = $_GET['location'];

	if($_POST["Username"]){
		$Username = $_POST["Username"];
		$Password = $_POST["Password"];
		$match = false;
		$result = runQuery("SELECT * FROM User");
		while ($row = mysqli_fetch_assoc($result)) {
			if($row["User_Name"] == $Username && $row["Password"] == $Password){
				$match = true;
				$User_ID = $row["User_ID"];
			}
		}

		if($match){
			$_SESSION["User_ID"] = $User_ID;
			header("Location:". $redirectingURL);
			exit();
		}
		else
			$err_msg = "Username or Password is Incorrect";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login | TTParikh</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">	
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="js/main.js"></script>

	<!-- <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css"> -->
	<link rel="stylesheet" type="text/css" href="css/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form" id="loginForm" action="login.php?location=<?=$redirectingURL?>" method="post" enctype="multipart/form-data">
					<span class="login100-form-title">Log in</span>

					<span style="color:#F75B54"><?=$err_msg?></span>

					<div class="wrap-input100" data-validate = "Enter username">
						<input class="input100" autocomplete="off" name="Username" id="user" placeholder="Username">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100" data-validate="Enter password">
						<input class="input100" type="password" name="Password" id="pass" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<!-- <div class="contact100-form-checkbox">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
						<label class="label-checkbox100" for="ckb1">
							Remember me
						</label>
					</div> -->

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" onclick="document.getElementById('loginForm').submit();">Login</button>
                    </div>
                    
                    <!-- <div class="text-center">
						<a class="txt1" href="#">
							Forgot Password?
						</a>
					</div> -->

				</form>
			</div>
		</div>
	</div>

	<script>
		window.onload = function(){
			document.getElementById('user').focus();
		};
	</script>
</body>
</html>
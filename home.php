<?php
require_once "php/user.php";
$info = new User();
if(isset($_POST['user']) && isset($_POST['pass'])) {
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$info->getUser($user,$pass);
	if(!$info->wmsg) {
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['pass'] = $pass;
		header("Location: index.php");
	}
	else {
		$warning = $info->showError();
	}
}
if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['dob']) && isset($_POST['gender'])) {
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$dob = $_POST['dob'];
	$gender = $_POST['gender'];	
	$info->createUser($user, $pass, $firstname, $lastname, $dob, $gender);
	if(!$info->wmsg) {
		
	}
	else {
		$warning = $info->showError();
	}
}
?>
<!doctype html>
<html>
<style>


</style>
<head>
	<link rel="stylesheet" href="style/home.css">
	<meta charset="utf-8">
	<meta content=’width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0′ name=’viewport’/>
	<meta name=”viewport” content=”width=device-width”/>
	<title>Home Page</title>
</head>

<body>
	
	<div class="main-container">
		<div class="nav-bar" id="nav-error">
			<form action="home.php" method="POST">
				<img src="images/codechat.png">
				<div class="login">
					<ul>
						<li>&nbsp;</li>
						<div class="tooltip"><li><button type="submit">Log In</button></li>
						<span class="tooltiptext" id="warning"><?php if(isset($warning)) echo $warning ?></span>
						</div>
					</ul>
				</div>
				<div class="login">
					<ul>
						<li>Password</li>
						<li><input type="password" name="pass"></li>
						<li><a href="#">Forgot Password?</a></li>
					</ul>
				</div>
				<div class="login">
					<ul>
						<li>Email or Phone</li>
						<li><input type="text" name="user"></li>
						<li></li>
					</ul>
				</div>
			</form>
		</div>
		<div class="container">
			<div class="col-2">
				&nbsp;
			</div>
			<div class="col-2">
				<form action="home.php" method="POST">
				<h1>Create a new account</h1>
				<div class="input"><input placeholder="First name" size="21" name="firstname" required><input placeholder="Last name" size="20" name="lastname" required>
				</div>
				<div class="input"><input placeholder="Mobile number or email address" name="user" size="47" required>
				</div>
				<div class="input"><input placeholder="New password" size="47" name="pass" required>
				</div>
				<h3>Birthday</h3>
				<div class="input inline-block"><input type="date" size="21" name="dob" required>
				</div>
				<div class="help inline-block"><a href="#">why do I need to provide my date of birth?</a>
				</div>
				<div style="margin-bottom: 10px;"><label class="radio"><input type="radio" name="gender" value="M" required>Male</label><label class="radio"><input type="radio" name="gender" value="F" required>Female</label>
				</div>
				<div><button type="submit">Create Account</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</body>
<script>
var warning = <?php echo $info->wmsg; ?>;
if(warning != 0) {
	element = document.getElementById("warning");
	navbar = document.getElementById("nav-error");
	element.style.animation = "first 1s ease-out both";
	navbar.style.animation = "error-red 1s ease-out both";
	setTimeout(function() {
		navbar.style.animationName = "error-back";
		element.style.animationName = "last";
	}, 5000);
}
</script>
</html>
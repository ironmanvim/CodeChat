<?php
require_once("php/user.php");
session_start();
if(isset($_POST['logout'])) {
	destroySession();
}
if(isset($_SESSION['user']) && isset($_SESSION['pass'])) {
	$info = new User();
	$user = $_SESSION['user'];
	$pass = $_SESSION['pass'];
	$info->getUser($user, $pass);
	if(!$info->wmsg) {
		
	}
	else {
		die($info->showError());
	}
}
else {
	//header("Location: home.php");
}
?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="style/index1.css">
	<meta charset="utf-8">
	<title>CodeChat</title>
</head>

<body>
	<?php include("show_for_all.php");?>
</body>
</html>
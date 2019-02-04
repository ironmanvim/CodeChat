<?php
require_once("php/user.php");
session_start();
if(isset($_POST['logout'])) {
	destroySession();
}
if(isset($_POST['friends'])) {
	header("Location: friends.php");
}
if(isset($_POST['messages'])) {
	header("Location: messages.php");
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
	header("Location: home.php");
}
?>
<?php
require_once 'user.php';
$user = new User();
if(isset($_POST['user']) && isset($_POST['pass'])) {
	$user->getUser($_POST['user'], $_POST['pass']);
	if(isset($_POST['group-name']) && isset($_POST['selected-friends'])) {
		$group_name = $_POST['group-name'];
		$selected_friends = $_POST['selected-friends'];
		$group_members = array();
		$group_members[] = $user->getMyId();
		for($i = 0; $i < count($selected_friends); $i++) {
			$group_members[] = $selected_friends[$i];
		}
		$group_members = implode(",", $group_members);
		$user->createGroup($group_name, $group_members);
		header("location: ../messages.php");
	}
}
?>
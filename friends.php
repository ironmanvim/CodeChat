<?php require_once("routine.php"); ?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="style/index1.css">
	<script src="js/member.js"></script>
	<meta charset="utf-8">
	<title>CodeChat</title>
</head>

<body>
	<script>
user = '<?php echo $info->getUserDet(); ?>';
pass = '<?php echo $info->getPassword(); ?>';
getOnlineUsers(user,pass);
setInterval('getOnlineUsers(user,pass)', 2500);
	</script>
	<?php include("show_for_all.php");?>
	<div class="body">
		<div class="center-vertical">
			<div class="search">
				<form method="get" action="friends.php">
					<img src="images/search.png" style="float: left; width: 50px; padding-right: 10px; padding-top: 10px;">
					<input name="search" type="text" placeholder="Search Member">
					<button type="submit">Search</button>
				</form>
			</div>
		</div>
	<?php if(count($_GET) == 0) { ?>
		<div class="col-3 friends">
			<div class="heading">Friends</div>
			<?php 
			$friends = $info->getFriendsIds();
			$friend = new SearchUser();
			for($i = 0; $i < count($friends); $i++) {
				$friend->searchUserById($friends[$i]);
			?>
			<div class="list-box">
				<form method="get" action="friends.php">
					<div class="list"><button type="submit" name="member" value="<?php echo $friends[$i]; ?>"><?php echo $friend->first_name ?></button></div>
				</form>
			</div>
			<?php } ?>
			<?php if(!count($friends)) { ?>
			<div class="list-box">
				<div class="error">
					No Friends
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="col-3 friends">
			<div class="heading">Friend Requests</div>
			<?php 
			$friends = $info->getRequestsIds();
			for($i = 0; $i < count($friends); $i++) {
				$friend = new SearchUser();
				$friend->searchUserById($friends[$i]);
			?>
			<div class="list-box">
				<form method="get" action="friends.php">
					<div class="list"><button type="submit" name="member" value="<?php echo $friends[$i]; ?>"><?php echo $friend->first_name ?></button></div>
				</form>
			</div>
			<?php } ?>
			<?php if(!count($friends)) { ?>
			<div class="list-box">
				<div class="error">
					No Friend Request to Show
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="col-3 friends">
			<div class="heading">Sent Friend Requests</div>
			<?php 
			$friends = $info->getSentRequestsIds();
			for($i = 0; $i < count($friends); $i++) {
				$friend = new SearchUser();
				$friend->searchUserById($friends[$i]);
			?>
			<div class="list-box">
				<form method="get" action="friends.php">
					<div class="list"><button type="submit" name="member" value="<?php echo $friends[$i]; ?>"><?php echo $friend->first_name ?></button></div>
				</form>
			</div>
			<?php } ?>
			<?php if(!count($friends)) { ?>
			<div class="list-box">
				<div class="error">
					No Sent Requests
				</div>
			</div>
			<?php } ?>
		</div>
	<?php } else { ?>
	<?php 
	if(isset($_GET['member'])) { 
		$id = $_GET['member'];
		if($info->isMyId($id)) {
			header("Location: profile.php");
		}
		$member = new SearchUser();
		$member->searchUserById($id);
		if($member->wmsg == 0) {
	?>
		<div class="member">
			<div class="profile">
				<img src="images/<?php if(isset($member)) if($member->getGender() == 'F') echo 'fe'; ?>male_profile.png" width="100px">
				<p><?php if(isset($member)) echo $member->getName(); ?></p>
			</div>
			<div class="info">
				<div class="heading">Profile Info</div>
				<?php if($member->getEmail() != null) {  ?>
				<div class="list">Email: <?php echo $member->getEmail() ?></div>
				<?php } ?>
				<?php if($member->getPhone() != null) {  ?>
				<div class="list">Phone: <?php echo $member->getPhone() ?></div>
				<?php } ?>
				<div class="list">BirthDay: <?php echo $member->getBirthday() ?></div>
				<div class="list">Gender: <?php if($member->getGender() == "M") echo "Male"; else echo "Female" ?></div>
			</div>
			<div class="button-group">
				<?php if($info->isFriend($id)) { ?>
				<button class="disabled">Friends</button>
				<form method="get" action="messages.php">
					<button type="submit" name="chat" value="<?php echo $id; ?>">Message</button>
				</form>
				<?php } else if($info->didHeSentMeARequest($id)) { ?>
				<button class="accept" value="<?php echo $id; ?>" onClick="acceptRequest('<?php echo $info->getUserDet() ?>', '<?php echo $info->getPassword(); ?>', this.value)" id="accept">Accept</button>
				<?php } else if($info->doISentHimARequest($id)) { ?>
				<button class="disabled">Sent Request</button>
				<?php } else { ?>
				<button class="add" name="add" value="<?php echo $id; ?>" onClick="addFriend('<?php echo $info->getUserDet() ?>', '<?php echo $info->getPassword(); ?>', this.value)" id="add">Add Friend</button>
				<?php } ?>
			</div>
		</div>
	<?php } else { ?>
		<div class="warning">User Not Exist<br>Try Again!</div>
	<?php } ?>
	<?php } else if(isset($_GET['search'])) { ?>
		<div class="col-3 friends">
			<div class="heading">Search Results: </div>
			<?php 
			$search = new SearchUser();
			$search_results = $search->searchUserIdsByPatttern($_GET['search']);
			for($i = 0; $i < count($search_results); $i++) {
				$search->searchUserById($search_results[$i]);
			?>
			<div class="list-box">
				<form method="get" action="friends.php">
					<div class="list"><button type="submit" name="member" value="<?php echo $search_results[$i]; ?>"><?php echo $search->getName(); ?></button></div>
				</form>
			</div>
			<?php } ?>
			<?php if(!count($search_results)) { ?>
			<div class="list-box">
				<div class="error">
					No Member Found
				</div>
			</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php } ?>
	</div>
</body>
<script>
friends = document.getElementById('friends');
messages = document.getElementById('messages');
friends.style.backgroundColor = "black";
messages.style.backgroundColor = "none";
</script>
</html>
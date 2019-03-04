<?php require_once("routine.php"); ?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="style/index1.css">
	<script src="js/member.js"></script>
	<link rel="stylesheet" href="style/chat_module.css">
	<meta charset="utf-8">
	<title>CodeChat</title>
</head>

<body>
<script>
user = '<?php echo $info->getUserDet(); ?>';
pass = '<?php echo $info->getPassword(); ?>';
<?php
if(isset($_GET['chat'])) {
	$id = $_GET['chat'];
	if($info->isFriend($id)) {
?>
id = '<?php echo $id; ?>';
receiveMessage(user,pass,id);
setInterval('receiveMessage(user,pass,id)', 1000);
<?php }} ?>
<?php
if(isset($_GET['chat_group'])) {
	$id = $_GET['chat_group'];
	if($info->isMyGroup($id)) {
		$id = "G".$id;
?>
id = '<?php echo $id; ?>';
receiveMessage(user,pass,id);
setInterval('receiveMessage(user,pass,id)', 1000);
<?php }} ?>
getOnlineUsers(user,pass);
setInterval('getOnlineUsers(user,pass)', 2500);
</script>
	<?php include("show_for_all.php");?>
	<div class="body">
		<div class="chat-body">
  			<div class="chat-list">
				<div class="list-heading">Chat</div>
				<div class="list-heading2">Friends</div>
				<?php 
				$friends = $info->getFriendsIds();
				$friend = new SearchUser();
				for($i = 0; $i < count($friends); $i++) {
					$friend->searchUserById($friends[$i]);
				?>
				<form method="get" action="messages.php">
					<div class="list">
						<button type="submit" name="chat" value="<?php echo $friends[$i]; ?>" id="chat_<?php echo $friends[$i]; ?>"><?php echo $friend->first_name ?></button>
					</div>
				</form>
				<?php } ?>
				<div class="list-heading2">Groups</div>
				<?php 
				$groups = $info->getGroupsIds();
				$group = new SearchGroup();
				for($i = 0; $i < count($groups); $i++) {
					$group->searchGroupById($groups[$i]);
				?>
				<form method="get" action="messages.php">
					<div class="list">
						<button type="submit" name="chat_group" value="<?php echo $groups[$i]; ?>" id="chat_g<?php echo $groups[$i]; ?>"><?php echo $group->group_name; ?></button>
					</div>
				</form>
				<?php } ?>
				<div class="hover-bottom-right">
					<button id="addGroup">Add Group</button>
				</div>
			</div>
			<?php 
			if(isset($_GET['chat'])) {
				$id = $_GET['chat'];
				if($info->isFriend($id)) {
					$member = new SearchUser();
					$member->searchUserById($id);
					if($member->wmsg == 0) {
			?>
			<div class="chat-box">
				<div class="chat-header">
					<form method="get" action="friends.php" style="display: inline">
						<button type="submit" name="member" value="<?php echo $id; ?>"><?php echo $member->first_name ?></button>
					</form>
				</div>
				<div class="chat-messages">
					<div class="chat" id="chat">

					</div>
				</div>
				<div class="chat-sender">
					<div>
						<input type="text" placeholder="Text here" id="message" autofocus>
						<button onClick="sendMessage('<?php echo $info->getUserDet() ?>', '<?php echo $info->getPassword(); ?>', '<?php echo $id; ?>', O('message').value)" id="send">Send</button>
					</div>
				</div>
			</div>
			<?php } else { ?>
			<div class="warning">Something went wrong<br>Try Again!</div>
			<?php } ?>
			<?php } else { ?>
			<div class="warning">Something went wrong<br>Try Again!</div>
			<?php } ?>
			<?php } ?>
			<?php 
			if(isset($_GET['chat_group'])) {
				$id = $_GET['chat_group'];
				if($info->isMyGroup($id)) {
					$group = new SearchGroup();
					$group->searchGroupById($id);
					if($group->wmsg == 0) {
						$id = "G".$id;
			?>
			<div class="chat-box">
				<div class="chat-header">
					<form method="get" action="friends.php" style="display: inline">
						<button type="submit" name="member" value="<?php echo $id; ?>"><?php echo $group->group_name ?></button>
					</form>
				</div>
				<div class="chat-messages">
					<div class="chat" id="chat">

					</div>
				</div>
				<div class="chat-sender">
					<div>
						<input type="text" placeholder="Text here" id="message" autofocus>
						<button onClick="sendMessage('<?php echo $info->getUserDet() ?>', '<?php echo $info->getPassword(); ?>', '<?php echo $id; ?>', O('message').value)" id="send">Send</button>
					</div>
				</div>
			</div>
			<?php } else { ?>
			<div class="warning">Something went wrong<br>Try Again!</div>
			<?php } ?>
			<?php } else { ?>
			<div class="warning">Something went wrong<br>Try Again!</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
	<div id="overlay" class="overlay">
  		<div class="overlay-content">
			<form action="php/create_group.php" method="post">
    			<span class="close">&times;</span>
    			<div class="heading">
					<div class="heading-name">Create Group</div>
				</div>
				<div class="group-name">
					<input type="text" class="input" placeholder="Group Name here" name="group-name" required>
				</div>
				<div class="friends-list">
					<?php 
					$friends = $info->getFriendsIds();
					$friend = new SearchUser();
					for($i = 0; $i < count($friends); $i++) {
						$friend->searchUserById($friends[$i]);
					?>
					<div class="friend"><label>
                            <input type="checkbox" name="selected-friends[]" value="<?php echo $friends[$i]; ?>">
                         <span class="friend-name"><?php echo $friend->first_name; ?></span></label></div>
					<?php } ?>
				</div>
				<div class="submit">
					<input type="hidden" name="user" value="<?php echo $info->getEmail(); ?>">
					<input type="hidden" name="pass" value="<?php echo $info->getPassword(); ?>">
					<button type="submit">Create Group</button>
				</div>
			</form>
  		</div>
	</div>

<script>
friends = document.getElementById('friends');
messages = document.getElementById('messages');
friends.style.backgroundColor = "none";
messages.style.backgroundColor = "black";
scroll = document.getElementById('chat'); 

if(scroll != null) {
	scroll.scrollTop = scroll.scrollHeight;
}
input = O('message');
if(input != null) {
	input.addEventListener("keyup", function(event) {
  		// Cancel the default action, if needed
  		event.preventDefault();
  		// Number 13 is the "Enter" key on the keyboard
  		if (event.keyCode === 13) {
    		// Trigger the button element with a click
    		document.getElementById("send").click();
  		}
	});
}

// Get the modal
var modal = document.getElementById('overlay');

// Get the button that opens the modal
var btn = document.getElementById("addGroup");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
};

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

<?php 
if(isset($_GET['chat'])) {
	$id = $_GET['chat'];
	echo "chat = document.getElementById('chat_$id');";
	echo "chat.style.backgroundColor = '#0277C1';";
	echo "chat.style.color = 'black'";
}
?>
<?php
if(isset($_GET['chat_group'])) {
	$id = $_GET['chat_group'];
	echo "chat = document.getElementById('chat_g$id');";
	echo "chat.style.backgroundColor = '#0277C1';";
	echo "chat.style.color = 'black'";
}
?>
</script>
</body>
</html>
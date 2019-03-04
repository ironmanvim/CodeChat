<div class="nav-bar">
	<ul>
		<p>CodeChat</p>
		<form method="post" action="index.php" style="display: inline">
			<li><button type="submit" name="logout" value="1" id="logout">Logout</button></li>
		</form>
		<form method="post" action="index.php" style="display: inline">
			<li><button type="submit" name="friends" value="1" id="friends">Friends</button></li>
		</form>
		<form method="post" action="index.php" style="display: inline">
			<li><button type="submit" name="messages" value="1" id="messages">Messages</button></li>
		</form>
		<li>
			<button>
				<img style="float: left; margin-right: 5px;" src="images/<?php if(isset($info)) if($info->getGender() == 'F') echo 'fe'; ?>male_profile.png" width="20px">
				<?php if(isset($info)) echo $info->getFirstName(); ?>
			</button>
		</li>
	</ul>
</div>
<div class="side-bar-left">
	<p>Online Friends</p>
	<ul id="online">
		
	</ul>
</div>

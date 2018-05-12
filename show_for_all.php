<div class="nav-bar">
	<ul>
		<p>CodeChat</p>
		<form method="post" action="index.php" style="display: inline">
			<li><button type="submit" name="logout" value="1">Logout</button></li>
		</form>
		<li><button>Friends</button></li>
		<li><button>Messages</button></li>
		<li><button><?php if(isset($info)) echo $info->getFirstName(); ?></button></li>
	</ul>
</div>
<div class="side-bar-left">
	<ul>
		<p>Friends Online Status</p>
		<li><img src="images/online.png"><a href="#" class="online">Vishal</a></li>
		<li><img src="images/offline.png"><a href="#" class="offline">Ajay Kumar</a></li>
		<li><img src="images/online.png"><a href="#" class="online">Tejesh</a></li>
		<li><img src="images/online.png"><a href="#" class="online">Sandeep</a></li>
		<li><img src="images/offline.png"><a href="#" class="offline">Civil</a></li>
	</ul>
</div>

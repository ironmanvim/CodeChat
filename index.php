<?php require_once 'routine.php'?>
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
		<div class="center">
  			<div style="font-size: 50px; text-align: center;">
				<img src="images/<?php if(isset($info)) if($info->getGender() == 'F') echo 'fe'; ?>male_profile.png" width="100px"><br>
				<?php if(isset($info)) echo $info->getName(); ?><br>
				Welcome to CodeChat
			</div>
		</div>
	</div>
</body>
</html>
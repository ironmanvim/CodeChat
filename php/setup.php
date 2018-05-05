<!DOCTYPE html>
<html>

<head>
	<title>Setting up database</title>
</head>

<body>

	<h3>Setting up...</h3>

	<?php // Example 26-3: setup.php
  require_once 'info.php';
  deleteTable('members');
  createTable('members',
              'id INT PRIMARY KEY AUTO_INCREMENT,
			  first_name VARCHAR(30),
			  last_name VARCHAR(30),
			  email VARCHAR(30),
			  phone VARCHAR(16),
              pass VARCHAR(16),	
			  birthday DATE,
			  gender VARCHAR(2),
              online_time INT UNSIGNED');

  createTable('messages',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              user VARCHAR(16),
              time INT UNSIGNED,
              message VARCHAR(4096)');

?>

	<br>...done.
</body>

</html>
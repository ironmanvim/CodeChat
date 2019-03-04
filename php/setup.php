<!DOCTYPE html>
<html>

<head>
<title>Setting up database</title>
</head>

<body>

	<h3>Setting up...</h3>

<?php
// Example 26-3: setup.php
require_once 'info.php';
createTable('members', 'id INT PRIMARY KEY AUTO_INCREMENT,
			  first_name VARCHAR(30),
			  last_name VARCHAR(30), 
			  email VARCHAR(30),
			  phone VARCHAR(16),
              pass VARCHAR(16),	
			  birthday DATE,
			  gender VARCHAR(2),
              online_time INT UNSIGNED');
createTable('friends', 'id INT PRIMARY KEY AUTO_INCREMENT,
			   uid INT,
			   fid INT,
			   status INT');
createTable('messages', 'mid INT PRIMARY KEY AUTO_INCREMENT,
			   uid INT,
			   cid VARCHAR(128),
			   message VARCHAR(500)');
createTable('groups', 'id INT PRIMARY KEY AUTO_INCREMENT,
                group_name VARCHAR(100),
                admin_id VARCHAR(500),
                group_members VARCHAR(500)');
?>

	<br>...done.
</body>

</html>
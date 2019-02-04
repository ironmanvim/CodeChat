<?php
require_once 'user.php';
$user = new User();
if(isset($_POST['user']) && isset($_POST['pass'])) {
    $user->getUser($_POST['user'], $_POST['pass']);
    $user->updateOnlineTime();
    $time = time();
    $result = queryMysql("SELECT * FROM members WHERE online_time >= '$time'");
    for($j = 0; $j < $result->num_rows; $j++) {
        $result->data_seek($j);
        $row = $result->fetch_assoc();
        if($user->isFriend($row['id']))
            echo "<li><a href='messages.php?chat=". $row['id'] ."' class='online'>" . $row['first_name'] . "</li>";
    }
}
?>
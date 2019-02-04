<?php
require_once 'user.php';
$user = new User();
if(isset($_POST['user']) && isset($_POST['pass'])) {
    $user->getUser($_POST['user'], $_POST['pass']);
    
    if(isset($_POST['cid'])) {
        $id = $_POST['cid'];
        $myid = $user->getMyId();
        if($id[0] == 'G') {
            $group_id = substr($id, 1);
            if($user->isMyGroup($group_id))
                $result = queryMysql("SELECT * FROM messages WHERE cid = '$id' ORDER BY mid");
        } else {
            $result = queryMysql("SELECT * FROM messages WHERE (uid = '$myid' AND cid = '$id') OR (uid = '$id' AND cid = '$myid') ORDER BY mid");
        }
        for($i = 0; $i < $result->num_rows; $i++) {
            $result->data_seek($i);
            $row = $result->fetch_assoc();
            $uid = $row['uid'];
            $message = $row['message'];
            $find_user = new SearchUser();
            $find_user->searchUserById($uid);
            echo "<div class='message-line'>";
            if($user->isMyId($uid)) { 
                echo "<div class='me'>$message</div>";
            }
            else {
                echo "<div class='you'>";
                if($id[0] == 'G') {
                    echo "<div class='you-name'>$find_user->first_name</div>";
                }
                echo "$message</div>";
            }
            echo "</div>";
        }
    }
}
?>
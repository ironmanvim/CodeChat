<?php
require_once 'user.php';
$user = new User();
if(isset($_POST['user']) && isset($_POST['pass'])) {
    $user->getUser($_POST['user'], $_POST['pass']);
    
    if(isset($_POST['aid'])) {
        
        $user->acceptFriendRequest($_POST['aid']);
        
        if(!$user->wmsg) {
            echo "Friend Request Accepted";
        }
        else
            die($user->showError());
    }
    else if(isset($_POST['sid'])) {
        
        $user->sendFriendRequest($_POST['sid']);
        
        if(!$user->wmsg) {
            echo "Friend Request Sent";
        }
        else
            die($user->showError());
    }
}

?>
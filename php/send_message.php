<?php
require_once 'user.php';
$user = new User();
if(isset($_POST['user']) && isset($_POST['pass'])) {
    $user->getUser($_POST['user'], $_POST['pass']);
    
    if(isset($_POST['cid']) && isset($_POST['message'])) {
        $user->sendMessageToChatId($_POST['cid'], $_POST['message']);
    }
}
?>
<?php
require_once 'user.php';
$info = new User();
$info->getUser('vishalchiluveri@gmail.com', '6nw3aals');
if(!$info->wmsg) {
    $a = $info->getName();
    echo $a;
} else {
    $info->showError();
}
?>

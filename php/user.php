<?php
require_once 'info.php';
class User {
    private $first_name,$last_name,$email,$phone,$pass,$birthday,$gender,$online_time;
    public $wmsg = 0;
    
    //gets info of user with email or phone and password
    function getUser($user, $pass) {
        $this->wmsg = 0;
        if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $user))
            $result = queryMysql("SELECT * FROM members WHERE email='$user' AND pass='$pass'");
        else if(preg_match("/^[1-9][0-9]{9,10}$/", $user))
            $result = queryMysql("SELECT * FROM members WHERE phone='$user' AND pass='$pass'");
        else {
            $this->wmsg = 3; //Invalid phone or email error
            return;
        }
        if($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->pass = $row['pass'];
            $this->birthday = $row['birthday'];
            $this->gender = $row['gender'];
            $this->online_time = $row['online_time'];
        }
        else {
            $this->wmsg = 2; //User not exist error
        }
    }
    
    //create user
    function createUser($user, $pass, $first_name, $last_name, $birthday, $gender) {
        $this->wmsg = 0;
        $user = sanitizeString($user);
        $pass = sanitizeString($pass);
        $first_name = sanitizeString($first_name);
        $last_name = sanitizeString($last_name);
        $birthday = sanitizeString($birthday);
        $gender = sanitizeString($gender);
        $email = "";
        $phone = "";
        if(!preg_match("/^[a-zA-Z]*$/", $first_name.$last_name)) {
            $this->wmsg = 5; //invalid name
            return;
        }
        
        if(date('Y',time()) - date('Y', strtotime($birthday)) < 13) {
            $this->wmsg = 7; //lesser birthday
            return;
        }
        if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $user)) 
            $email = $user;
        else if(preg_match("/^[1-9][0-9]{9,10}$/", $user))
            $phone = $user;
        else {
            $this->wmsg = 4; //invalid email or phone
            return;
        }
        $time = time();
        $query = queryMysql("SELECT * FROM members where email = '$email' and phone = '$phone'");
        if(!$query->num_rows) {
            $result = queryMysql("INSERT INTO members VALUES(NULL, '$first_name', '$last_name', '$email', '$phone', '$pass', '$birthday', '$gender', '$time')");
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->birthday = $birthday;
            $this->email = $email;
            $this->phone = $phone;
            $this->pass = $pass;
            $this->gender = $gender;
            $this->online_time = $time;
        }
        else 
            $this->wmsg = 1; //User already exist error
    }
    
    //display all data
    function showUser() {
        if(isset($this->email))
            echo "$this->first_name, $this->last_name, $this->email, $this->phone, $this->pass, $this->birthday, $this->gender, $this->online_time";
        else 
            echo "Nothing to display";
    }
    
    function getFirstName() {
        return $this->first_name;
    }
    
    function getLastName() {
        return $this->last_name;
    }
    
    function getEmail() {
        return $this->email;
    }
    
    function getPhone() {
        return $this->phone;
    }
    
    function getBirthday() {
        return $this->birthday;
    }
    
    function getOnlineTime() {
        
    }
    
    function showError() {
        if($this->wmsg == 0)
            return "No error";
        else if($this->wmsg == 1)
            return "User Already Exist Try to Login or Reset your password";
        else if($this->wmsg == 2)
            return "Invalid Credentials Try Again!";
        else if($this->wmsg == 3)
            return "Invalid Email or Phone No Try Again!";
        else if($this->wmsg == 4)
            return "Invalid Email or Phone No Try Again!";
        else if($this->wmsg == 5)
            return "Invalid Name Try Again!";
        else if($this->wmsg == 7)
            return "Lesser Birthday Not Eligible to Signup";
    }
}


?>
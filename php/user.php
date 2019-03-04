<?php
require_once 'info.php';

class User
{

    private $id, $first_name, $last_name, $email, $phone, $pass, $birthday, $gender, $online_time;

    public $wmsg = 0;

    // gets info of user with email or phone and password
    function getUser($user, $pass)
    {
        $this->wmsg = 0;
        $user = sanitizeString($user);
        $pass = sanitizeString($pass);
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $user))
            $result = queryMysql("SELECT * FROM members WHERE email='$user' AND pass='$pass'");
        else if (preg_match("/^[1-9][0-9]{9,10}$/", $user))
            $result = queryMysql("SELECT * FROM members WHERE phone='$user' AND pass='$pass'");
        else {
            $this->wmsg = 3; // Invalid phone or email error
            return;
        }
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->pass = $row['pass'];
            $this->birthday = $row['birthday'];
            $this->gender = $row['gender'];
            $this->online_time = $row['online_time'];
        } else {
            $this->wmsg = 2; // User not exist error
        }
    }

    // create user
    function createUser($user, $pass, $first_name, $last_name, $birthday, $gender)
    {
        $this->wmsg = 0;
        $user = sanitizeString($user);
        $pass = sanitizeString($pass);
        $first_name = sanitizeString($first_name);
        $last_name = sanitizeString($last_name);
        $birthday = sanitizeString($birthday);
        $gender = sanitizeString($gender);
        $email = "";
        $phone = "";
        if (! preg_match("/^[a-zA-Z\\s]*$/", $first_name . $last_name)) {
            $this->wmsg = 5; // invalid name
            return;
        }
        
        if (date('Y', time()) - date('Y', strtotime($birthday)) < 13) {
            $this->wmsg = 7; // lesser birthday
            return;
        }
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $user))
            $email = $user;
        else if (preg_match("/^[1-9][0-9]{9,10}$/", $user))
            $phone = $user;
        else {
            $this->wmsg = 4; // invalid email or phone
            return;
        }
        $time = time();
        $query = queryMysql("SELECT * FROM members where email = '$email' and phone = '$phone'");
        if (! $query->num_rows) {
            queryMysql("INSERT INTO members VALUES(NULL, '$first_name', '$last_name', '$email', '$phone', '$pass', '$birthday', '$gender', '$time')");
            $this->getUser($user, $pass);
        } else
            $this->wmsg = 1; // User already exist error
    }

    // display all data
    function showUser()
    {
        if (isset($this->email))
            echo "$this->first_name, $this->last_name, $this->email, $this->phone, $this->pass, $this->birthday, $this->gender, $this->online_time";
        else
            echo "Nothing to display";
    }

    function getUserDet()
    {
        if ($this->email != null)
            return $this->email;
        else
            return $this->phone;
    }

    function getName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    function getFirstName()
    {
        return $this->first_name;
    }

    function getLastName()
    {
        return $this->last_name;
    }

    function getPassword()
    {
        return $this->pass;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getPhone()
    {
        return $this->phone;
    }

    function getGender()
    {
        return $this->gender;
    }

    function getBirthday()
    {
        return $this->birthday;
    }

    function getOnlineTime()
    {
        return $this->online_time;
    }

    function updateOnlineTime()
    {
        $time = time() + 3;
        queryMysql("UPDATE members SET online_time = '$time' WHERE id = '$this->id'");
    }

    function showError()
    {
        if ($this->wmsg == 0)
            return "No error";
        else if ($this->wmsg == 1)
            return "User Already Exist Try to Login or Reset your password";
        else if ($this->wmsg == 2)
            return "Invalid Credentials Try Again!";
        else if ($this->wmsg == 3)
            return "Invalid Email or Phone No Try Again!";
        else if ($this->wmsg == 4)
            return "Invalid Email or Phone No Try Again!";
        else if ($this->wmsg == 5)
            return "Invalid Name Try Again!";
        else if ($this->wmsg == 7)
            return "Lesser Birthday Not Eligible to Signup";
        else if ($this->wmsg == 8)
            return "Friend Request not sent by the user";
        else if ($this->wmsg == 9)
            return "User not exist to send request";
        else if ($this->wmsg == 10)
            return "Friend request already sent by the user you have to accept";
        else if ($this->wmsg == 11)
            return "You both are friends";
        else if ($this->wmsg == 12)
            return "Friend request already sent";
        else if ($this->wmsg == 13)
            return "No friends to show";
        else if ($this->wmsg == 14)
            return "No friends Request to show";
        else if ($this->wmsg == 15)
            return "No friends Request sent";
        else if ($this->wmsg == 16)
            return "No Groups for you";
    }

    function getMyId()
    {
        return $this->id;
    }

    function sendFriendRequest($id)
    {
        $id = sanitizeString($id);
        $query = queryMysql("SELECT * FROM members WHERE id = '$id'");
        if ($query->num_rows == 1) {
            $query = queryMysql("SELECT * FROM friends WHERE fid = '$this->id' AND uid = '$id'");
            if ($query->num_rows == 0) {
                $query = queryMysql("SELECT * FROM friends WHERE fid = '$id' AND uid = '$this->id'");
                if ($query->num_rows == 0) {
                    queryMysql("INSERT INTO friends VALUES(NULL, '$this->id', '$id', 0)");
                } else
                    $this->wmsg = 12; // Friend request already sent
            } else {
                $query = queryMysql("SELECT * FROM friends WHERE fid = '$this->id' AND uid = '$id' AND status = 0");
                if ($query->num_rows == 1)
                    $this->wmsg = 10; // Friends Request already sent by the user u have to accept
                else
                    $this->wmsg = 11; // You are already friends
            }
        } else {
            $this->wmsg = 9; // User not exist to send friend request
        }
    }

    function acceptFriendRequest($id)
    {
        $id = sanitizeString($id);
        $query = queryMysql("SELECT status FROM friends WHERE fid = '$this->id' AND uid = '$id'");
        if ($query->num_rows == 1) {
            $row = $query->fetch_assoc();
            if ($row['status'] == 0)
                queryMysql("UPDATE friends SET status = 1 WHERE fid = '$this->id' AND uid = '$id'");
            else
                $this->wmsg = 11; // You both are already friends
        } else
            $this->wmsg = 8; // Friend request not sent by this user
    }

    function getFriendsIds()
    {
        $query1 = queryMysql("SELECT fid FROM friends WHERE uid = '$this->id' AND status = 1");
        $query2 = queryMysql("SELECT uid FROM friends WHERE fid = '$this->id' AND status = 1");
        $friends = array();
        if ($query1->num_rows > 0) {
            for ($i = 0; $i < $query1->num_rows; $i ++) {
                $query1->data_seek($i);
                $row = $query1->fetch_assoc();
                $friends[] = $row['fid'];
            }
        }
        if ($query2->num_rows > 0) {
            for ($i = 0; $i < $query2->num_rows; $i ++) {
                $query2->data_seek($i);
                $row = $query2->fetch_assoc();
                $friends[] = $row['uid'];
            }
        }
        if (count($friends) == 0) {
            $this->wmsg = 13; // no friends
            return;
        }
        return $friends;
    }

    function getRequestsIds()
    {
        $query1 = queryMysql("SELECT uid FROM friends WHERE fid = '$this->id' AND status = 0");
        $friends = array();
        if ($query1->num_rows > 0) {
            for ($i = 0; $i < $query1->num_rows; $i ++) {
                $query1->data_seek($i);
                $row = $query1->fetch_assoc();
                $friends[] = $row['uid'];
            }
        }
        if (count($friends) == 0) {
            $this->wmsg = 14; // no friend requests
            return;
        }
        return $friends;
    }

    function getSentRequestsIds()
    {
        $query1 = queryMysql("SELECT fid FROM friends WHERE uid = '$this->id' AND status = 0");
        $friends = array();
        if ($query1->num_rows > 0) {
            for ($i = 0; $i < $query1->num_rows; $i ++) {
                $query1->data_seek($i);
                $row = $query1->fetch_assoc();
                $friends[] = $row['fid'];
            }
        }
        if (count($friends) == 0) {
            $this->wmsg = 15; // no sent friend requests
            return;
        }
        return $friends;
    }

    function isFriend($id)
    {
        $query = queryMysql("SELECT * FROM friends WHERE (fid = '$this->id' AND uid = '$id' AND status = 1) OR (fid = '$id' AND uid = '$this->id' AND status = 1)");
        if ($query->num_rows == 1) {
            return TRUE;
        }
        return FALSE;
    }

    function doISentHimARequest($id)
    {
        $query = queryMysql("SELECT * FROM friends WHERE (fid = '$id' AND uid = '$this->id' AND status = 0)");
        if ($query->num_rows == 1) {
            return TRUE;
        }
        return FALSE;
    }

    function didHeSentMeARequest($id)
    {
        $query = queryMysql("SELECT * FROM friends WHERE (fid = '$this->id' AND uid = '$id' AND status = 0)");
        if ($query->num_rows == 1) {
            return TRUE;
        }
        return FALSE;
    }

    function isMyId($id)
    {
        if ($this->id == $id)
            return TRUE;
        return FALSE;
    }

    function sendMessageToChatId($id, $message)
    {
        $message = sanitizeString($message);
        queryMysql("INSERT INTO messages VALUES(null, '$this->id', '$id', '$message')");
    }
    
    function getGroupsIds() {
        $query = queryMysql("SELECT id FROM GROUPS WHERE group_members REGEXP '$this->id,|^$this->id$|,$this->id$'");
        $groups = array();
        if ($query->num_rows > 0) {
            for ($i = 0; $i < $query->num_rows; $i++) {
                $query->data_seek($i);
                $row = $query->fetch_assoc();
                $groups[] = $row['id'];
            }
        }
        if (count($groups) == 0) {
            $this->wmsg = 16; // no friends
            return;
        }
        return $groups;
    }
    
    function createGroup($group_name, $group_members) {
        $group_members = sanitizeString($group_members);
        $group_name = sanitizeString($group_name);
        queryMysql("INSERT INTO groups VALUES(null, '$group_name', '$this->id', '$group_members')");
    }
    
    function isMyGroup($id)
    {
        $query = queryMysql("SELECT * FROM groups WHERE id = '$id' AND group_members REGEXP '$this->id,|^$this->id$|,$this->id$'");
        if ($query->num_rows == 1) {
            return TRUE;
        }
        return FALSE;
    }
}

class SearchUser
{

    public $id, $first_name, $last_name, $email, $phone, $birthday, $gender, $online_time, $wmsg = 0;

    function searchUserById($id)
    {
        $result = queryMysql("SELECT * FROM members WHERE id = '$id'");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->birthday = $row['birthday'];
            $this->gender = $row['gender'];
            $this->online_time = $row['online_time'];
        } else
            $this->wmsg = 1; // Member not exist
    }

    function searchUserIdsByPatttern($pattern)
    {
        $result1 = queryMysql("SELECT id FROM members WHERE first_name LIKE '$pattern%' OR first_name LIKE '% $pattern%'");
        $result2 = queryMysql("SELECT id FROM members WHERE last_name LIKE '$pattern%' OR last_name LIKE '% $pattern%'");
        $search_results = array();
        if ($result1->num_rows > 0) {
            for ($i = 0; $i < $result1->num_rows; $i ++) {
                $result1->data_seek($i);
                $row = $result1->fetch_assoc();
                $search_results[] = $row['id'];
            }
        }
        if ($result2->num_rows > 0) {
            for ($i = 0; $i < $result2->num_rows; $i ++) {
                $result2->data_seek($i);
                $row = $result2->fetch_assoc();
                $search_results[] = $row['id'];
            }
        }
        if (count($search_results) == 0) {
            $this->wmsg = 2; // no member found
            return;
        }
        return array_unique($search_results);
    }

    function getName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    function getFirstName()
    {
        return $this->first_name;
    }

    function getLastName()
    {
        return $this->last_name;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getPhone()
    {
        return $this->phone;
    }

    function getGender()
    {
        return $this->gender;
    }

    function getBirthday()
    {
        return $this->birthday;
    }

    function getOnlineTime()
    {}

    function showError()
    {
        if ($this->wmsg == 0)
            return "No error";
        else if ($this->wmsg == 1)
            return "Member Not Exist";
        else if ($this->wmsg == 2)
            return "Search Unsuccess No member found";
    }
}

class SearchGroup
{

    public $id, $group_name, $group_admins, $group_members, $wmsg;

    function SearchGroupById($id)
    {
        $result = queryMysql("SELECT * FROM groups WHERE id = '$id'");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->group_name = $row['group_name'];
            $group_admins = $row['admin_id'];
            $group_members = $row['group_members'];
            $this->group_admins = preg_split("/,/", $group_admins); 
            $this->group_members = preg_split("/,/", $group_members); 
        } else
            $this->wmsg = 1; // Member not exist
    }

    function showError()
    {
        if ($this->wmsg == 0)
            return "No error";
        else if ($this->wmsg == 1)
            return "Group Not Exist";
        else if ($this->wmsg == 2)
            return "Search Unsuccess No member found";
        return "";
    }
}

?>



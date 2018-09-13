<?php
require_once('connection.php'); //conect with database
session_start();
unset($_SESSION['error_messages']); //to reset the messages

if (isset($_POST['action'])) {
    $_SESSION['error_messages'] = array();
    $action                     = $_POST['action'];   
    # Each possible action that the user can do
    if ($action == 'register') {
        # Validations
        if (empty($_POST['first_name'])) {
            array_push($_SESSION['error_messages'], 'First name is required');
        }
        
        if (empty($_POST['last_name'])) {
            array_push($_SESSION['error_messages'], 'Last name is required');
        }
        
        if (empty($_POST['email'])) {
            array_push($_SESSION['error_messages'], 'Email is required');
        }
        
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $sql_e = "SELECT * FROM users WHERE email='{$email}'";
        $res_e = mysqli_query($connection, $sql_e);
        
        if (mysqli_num_rows($res_e) > 0) {
            array_push($_SESSION['error_messages'], 'This email address is already taken.');
        }
        
        if (empty($_POST['password'])) {
            array_push($_SESSION['error_messages'], 'Password is required');
        }
        
        if (empty($_SESSION['error_messages'])) {
            $pass = md5($_POST['password']); # password encryption
            
            # MySQL injection prevention
            $first = mysqli_real_escape_string($connection, $_POST['first_name']);
            $last  = mysqli_real_escape_string($connection, $_POST['last_name']);
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            
            $query = "INSERT INTO users
                (first_name, last_name, email, password, created_at, updated_at)
                VALUES ('{$first}', '{$last}', '{$email}', '{$pass}', NOW(), NOW())";
            
            if (mysqli_query($connection, $query)) {
                header('Location: login.php');
            } else {
                array_push($_SESSION['error_messages'], 'Failed to sign up.');
                header('Location: register.php');
            }
        } else {
            header('Location: register.php');
        }
    } elseif ($action == 'login') {
        if (empty($_POST['email'])) {
            array_push($_SESSION['error_messages'], 'Email is required');
        }
        
        if (empty($_POST['password'])) {
            array_push($_SESSION['error_messages'], 'Password is required');
        }
        
        if (empty($_SESSION['error_messages'])) {
            $password = md5($_POST['password']);
            $email    = mysqli_real_escape_string($connection, $_POST['email']);
            $query    = "SELECT * FROM users where users.password = '{$password}' AND users.email = '{$email}' LIMIT 1";
            $result   = mysqli_query($connection, $query);
            $user     = mysqli_fetch_assoc($result);
            
            if (empty($user)) {
                array_push($_SESSION['error_messages'], 'Wrong username/password combination.');
                header('Location: login.php');
            } else {
                $_SESSION['user'] = $user;
                header('Location: main.php');
            }
        } else {
            header('Location: login.php');
        }
    } elseif ($action == 'add_message') {
        if (empty($_POST['message'])) {
            array_push($_SESSION['error_messages'], 'The message is mandatory.');
        } else {
            $message = mysqli_real_escape_string($connection, $_POST['message']);
            $query   = "INSERT INTO messages (user_id, message, created_at, updated_at)
                VALUES ('{$_SESSION['user']['id']}', '{$message}', NOW(), NOW());";
            
            if (mysqli_query($connection, $query)) {
                
            } else {
                array_push($_SESSION['error_messages'], 'Failed to create message.');
            }
        }
        header('Location: main.php');
        
    } elseif ($action == 'add_comment') {
        if (empty($_POST['comment'])) {
            array_push($_SESSION['error_messages'], 'The comment is mandatory.');
        } else {
            $comment = mysqli_real_escape_string($connection, $_POST['comment']);
            $query   = "INSERT INTO comments (user_id, message_id, comment, created_at, updated_at)
                VALUES ('{$_SESSION['user']['id']}', '{$_POST['message_id']}', '{$comment}', NOW(), NOW());";
            
            if (mysqli_query($connection, $query)) {
                
            } else {
                array_push($_SESSION['error_messages'], 'Failed to create the comment.');
            }
        }
        header('Location: main.php');
        
    } elseif ($action == 'logout') {
        session_destroy();
        header('Location: index.php');
    }    
}
?>
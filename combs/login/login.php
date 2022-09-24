<?php
    require_once('../includes/tables.php');
    require_once('../../inc/functions.php');
    require_once('../../inc/form_functions.php');

    start_session('acclzsess');
        
    if(isset($_POST) && !empty($_POST)){
        $return = array();
        $errors = array();

        //start form validation
        $required = array();
        $required[] = 'name';
        $required[] = 'password';

        //check for errors
        if(empty($errors)){
            //check user in database
            $query = "SELECT * FROM {$users_table} WHERE username = ? ";
            $check_user = $connection->prepare($query);
            $check_user->bind_param("s", $_POST['username']);
            $check_user->execute();
            //echo $connection->error;
            $result = $check_user->get_result();
            $count = 0;
            $count2 = 0;
            while($user = $result->fetch_assoc()){
                $count++;
                if($user['block'] == 0){
                    if(password_verify($_POST['password'], $user['hashed_password'])){
                        $count2 = 1;
                        //user found. Log user in
                        $user_id = $user['id'];
                        $_SESSION['logged_in'] = 1;
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_name'] = $user['firstname'];
                        //record log in details
                        $ip_addr = $_SERVER['REMOTE_ADDR'];
                        $time = time();
                        $query = "INSERT INTO {$logs_table} (user_id, time, ip_addr) VALUES (?, ?, ?)";
                        $record = $connection->prepare($query);
                        $record->bind_param('iis', $user_id, $time, $ip_addr);
                        $record->execute();

                        $query = "UPDATE {$users_table} SET last_logged_in = ? WHERE id = ?";
                        $update = $connection->prepare($query);
                        $update->bind_param('ii', $time, $user_id);
                        $update->execute();
                        
                        $return['status'] = "success";
                        if($user['admin'] > 0){
                            $_SESSION['admin'] = $user['admin'];
                            $return['redirect'] = "../admin/";
                        } else {
                            $return['redirect'] = "../";
                        }
                    }
                } else {
                    $return['status'] = "failed";
                    $return['reason'] = "This User has been BLOCKED. Please contact your admin for rectification.";
                }
            }
            if($count < 1){
                //user not found
                $return['status'] = "failed";
                $return['reason'] = "User not found";
            } elseif($count > 1){
                //multiple users found. Raise alarm
                $return['status'] = "failed";
                $return['reason'] = "Multiple users found. Please contact the admin immediately.";
            } else {
                if($count2 != 1){
                    //user not found
                    $return['status'] = "failed";
                    $return['reason'] = "Invalid Password";
                }
            }
            echo json_encode($return);
        } else {
            print_r($errors);
        }
    }
?>
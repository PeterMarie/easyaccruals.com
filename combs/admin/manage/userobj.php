<?php
    require_once('../../includes/tables.php');
    require_once('../../../inc/functions.php');

    start_session('acclzsess');
    $error_redirect = '../../';
    check_admin($error_redirect);

    $user = new User();
    $return = array();

    if(isset($_GET['u']) && !empty($_GET['u'])){
        $user->set_user_id($_GET['u']);
        if(isset($_GET['act']) && !empty($_GET['act'])){
            switch ($_GET['act']) {
                case 'blk':
                    $user->block();
                    break;
                
                case 'unblk':
                    $user->unblock();
                    break;
                
                case 'dlt':
                    $user->delete();
                    break;
                
                case 'mk_admin':
                    $user->new_admin();
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }

    if(isset($_POST) && !empty($_POST)){
        $user->create_new_user();
    }


    Class User{
        protected $user_id;

        public function set_user_id($id){
            $this->user_id = $id;
        }
        public function get_user_id(){
            return $this->user_id;
        }

        public function block(){
            global $users_table;
            global $connection;
            $query = "UPDATE {$users_table} SET block = 1 WHERE id = ?";
            $block_user = $connection->prepare($query);
            $block_user->bind_param("i", $this->user_id);
            $block_user->execute();
            if($block_user){
                echo "success";
            } else {
                echo "Error: Unable to Block specified user";
            }
        }
        
        public function unblock(){
            global $users_table;
            global $connection;
            $query = "UPDATE {$users_table} SET block = 0 WHERE id = ?";
            $block_user = $connection->prepare($query);
            $block_user->bind_param("i", $this->user_id);
            $block_user->execute();
            if($block_user){
                echo "success";
            } else {
                echo "Error: Unable to end specified user's suspension";
            }
        }
        
        public function delete(){
            global $users_table;
            global $deleted_users_table;
            global $connection;
            $query = "SELECT * FROM {$users_table} WHERE id = ?";
            $get_user = $connection->prepare($query);
            $get_user->bind_param("i", $this->user_id);
            $get_user->execute();
            $result = $get_user->get_result();
            while($user = $result->fetch_assoc()){
                $time = time();
                $query = "INSERT INTO {$deleted_users_table} (name, phone, email, username, user_id, time_added, time_deleted, admin_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $record_deletion = $connection->prepare($query);
                $record_deletion->bind_param("ssssiiii", $user['name'], $user['phone'], $user['email'], $user['username'], $user['id'], $user['time_added'], $time, $_SESSION['user_id']);
                $record_deletion->execute();
            }
            $query = "DELETE FROM {$users_table} WHERE id = ?";
            $delete_user = $connection->prepare($query);
            $delete_user->bind_param("i", $this->user_id);
            $delete_user->execute();
            
            if($delete_user){
                echo "success";
            } else {
                echo "Error: Unable to delete user";
            }
        }
        
        public function new_admin(){
            global $users_table;
            global $connection;
            $query = "UPDATE {$users_table} SET admin = ? WHERE id = ?";
            $block_user = $connection->prepare($query);
            $block_user->bind_param("ii", $_GET['stat'], $this->user_id);
            $block_user->execute();
            if($block_user){
                echo "success";
            } else {
                echo "Error: Unable to update admin status";
            }
        }

        public function create_new_user(){
            global $users_table;
            global $connection;
            $return = array();
            $return['admin_level'] = $_SESSION['admin'];
            
            $count = $this->check_unique_values();
            if($count['value'] == 0){
                $time = time();
                $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                $query = "INSERT INTO {$users_table} (firstname, lastname, username, phone, hashed_password, time_added, added_by ";
                if(!empty($_POST['email'])){
                    $query .= ", email ";
                }
                $query .= ") VALUES (?, ?, ?, ?, ?, ?, ?";
                if(!empty($_POST['email'])){
                    $query .= ", ? ";
                }
                $query .= ")";

                $create_user = $connection->prepare($query);
                if(!empty($_POST['email'])){
                    $create_user->bind_param("ssssiis", $_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['phone'], $hashed_password, $time, $_SESSION['user_id'], $_POST['email']);
                } else {
                    $create_user->bind_param("ssssii", $_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['phone'], $hashed_password, $time, $_SESSION['user_id']);
                }
                $create_user->execute();
                if($create_user){
                    $return['status'] = "success";
                    $return['new_id'] = $connection->insert_id;
                } else {
                    $return['status'] = "failed";
                    $return['error'] = "Unable to add new cashier";
                }
            }else{
                $return['status'] = "failed";
                $return['error'] = $count['field'] . "must be unique!";
            }
            echo json_encode($return);
        }

        private function check_unique_values(){
            global $users_table;
            global $connection;
            $count_array = array();
            $count_array['value'] = 0;

            //check username
            $query = "SELECT * FROM {$users_table} WHERE username = ?";
            $check_username = $connection->prepare($query);
            $check_username->bind_param("s", $_POST['username']);
            $check_username->execute();
            $result = $check_username->get_result();
            while($check_username2 = $result->fetch_assoc()){
                $count_array['value'] = 1;
                $count_array['field'] = "Username";
            }
            
            /*//check name
            $query = "SELECT * FROM {$users_table} WHERE name = ?";
            $check_username = $connection->prepare($query);
            $check_username->bind_param("s", $_POST['firstname']);
            $check_username->execute();
            $result = $check_username->get_result();
            while($check_username2 = $result->fetch_assoc()){
                $count_array['value'] = 1;
                $count_array['field'] = "Name";
            }*/
            
            //check phone
            $query = "SELECT * FROM {$users_table} WHERE phone = ?";
            $check_username = $connection->prepare($query);
            $check_username->bind_param("s", $_POST['phone']);
            $check_username->execute();
            $result = $check_username->get_result();
            while($check_username2 = $result->fetch_assoc()){
                $count_array['value'] = 1;
                $count_array['field'] = "Phone Number";
            }

            return $count_array;
        }
    }

?>
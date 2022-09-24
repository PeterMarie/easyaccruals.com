<?php
    require_once('includes/tables.php');
    require_once('../inc/functions.php');
    require_once('../inc/form_functions.php');

    start_session('acclzsess');
    $error_redirect = 'login';
    check_log_in($error_redirect);

    //$sale = new Sale();
    $item = new Item();

    if(isset($_POST) && !empty($_POST)){
        switch ($_POST['act']) {
            case 'checkout':
                $return = $item->checkout();
                break;

            case 'search':
                $return = $item->search();
                break;

            default:
                # code...
                break;
        }
        echo json_encode($return);
    }

    
        class Sale{
            public function checkout(){
                global $connection;
                global $sales_table;
                global $receipts_table;
                $return_array = array();
                $errors = array();

                $cashier_id = $_SESSION['user_id'];
                $cashier_name = $_SESSION['cashier_name'];
                $time = time();
                $date = strftime("", $time);

                //Get receipt number
                $query = "SELECT * FROM {$receipts_table}";

                $cart = json_decode($_POST['cart'], true);

                for ($i=1; $i <= $cart['count']; $i++) {
                    if(key_exists($i, $cart)){
                        $item = $cart[$i]['item'];
                        $quantity = $cart[$i]['quantity'];
                        $item_id = $this->get_decrypted_id($item);

                        $query = "INSERT INTO {$sales_table} (item_id, quantity, time, cashier_id) VALUES (?, ?, ?, ?)";
                        $insert_sale = $connection->prepare($query);
                        $insert_sale->bind_param("iiii", $item_id, $quantity, $time, $cashier_id);
                        $insert_sale->execute();
                        if($insert_sale){
                            
                        } else {
                            $cart_item_count = "item_" . $i;
                            $errors[$cart_item_count] = $connection->error;
                        }
                    }
                }

                if(empty($errors)){
                    $return_array['status'] = "success";
                    $return_array['date'] = $date;
                } else {
                    $return_array['status'] = "failed";
                    $return_array['errors'] = $errors;
                }
                
                return $return_array;

            }

            //END OF CLASS
        }

        class Item extends Sale{
            public function search(){
                global $connection;
                global $items_table;
                $return_array = array();
                $return_array['items'] = array();

                $count = 0;

                $query = "SELECT * FROM {$items_table}";
                $get_items = $connection->prepare($query);
                $get_items->execute();
                if($get_items){
                    $result = $get_items->get_result();
                    while($items = $result->fetch_assoc()){
                        $length = strlen($items['name']);
                        $compare = stripos($items['name'], $_POST['entry']);
                        if($compare === false){
                            //Do Nothing
                        } else {
                            if($compare == 0){
                                $count++;
                                $return_array['items'][$count] = array();
                                $return_array['items'][$count]['item'] = $this->get_encrypted_id($items['id']);
                                $return_array['items'][$count]['item_name'] = $items['name'];
                                $return_array['items'][$count]['item_price'] = $items['price'];
                            }
                        }
                        if($count == 3){
                            break;
                        }
                    }
                    $return_array['count'] = $count;
                    $return_array['status'] = "success";
                } else {
                    $return_array['status'] = "failed";
                    $return_array['errors'] = "Failed to retrieve sales items";
                }

                return $return_array;
            }
            
            protected function get_decrypted_id($encrypted_id){
                // Get the cipher method 
                $ciphering = "AES-128-CTR"; 
                
                // Use OpenSSl Encryption method 
                $options = 0; 
                
                // Non-NULL Initialization Vector for encryption 
                $encryption_iv = '1234567898765432'; 
                
                // Get the encryption key 
                $encryption_key = "EjiroMoses"; 
         
                $decrypted_id = openssl_decrypt($encrypted_id, $ciphering, $encryption_key, $options, $encryption_iv);

                return $decrypted_id;
            }
            
            protected function get_encrypted_id($item_id){
                // Get the cipher method 
                $ciphering = "AES-128-CTR"; 
                
                // Use OpenSSl Encryption method 
                $options = 0; 
                
                // Non-NULL Initialization Vector for encryption 
                $encryption_iv = '1234567898765432'; 
                
                // Get the encryption key 
                $encryption_key = "EjiroMoses"; 
         
                $encrypted_id = openssl_encrypt($item_id, $ciphering, $encryption_key, $options, $encryption_iv);

                return $encrypted_id;
            }
        }
?>
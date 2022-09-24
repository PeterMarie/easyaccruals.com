<?php
    require_once('../../includes/tables.php');
    require_once('../../../inc/functions.php');

    start_session('acclzsess');
    $error_redirect = '../../';
    check_admin($error_redirect);

    $item = new Item();
    $header = new Header();

    if(isset($_GET) && !empty($_GET)){
        switch ($_GET['act']) {
            case 'item_table':
                $return = $item->get_all();
                break;

            default:
                # code...
                break;
        }
        echo json_encode($return);
    }

    if(isset($_POST) && !empty($_POST)){
        switch ($_POST['act']) {
            case 'updt':
                //Request received to update item
                $item->set_item_id($_POST['item_id']);
                $return = $item->update_item();
                break;
                
            case 'crt':
                //New item to be created
                $return = $item->create_item();
                break;
                
            case 'dlt':
                //Request received to delete item
                $item->set_item_id($_POST['item_id']);
                $return = $item->delete_item();
                break;
            
            case 'updt_header':
                //Request received to update header
                $header->set_header_id($_POST['header_id']);
                $return = $header->update_header();
                break;
                
            case 'dlt_header':
                //Request received to delete header
                $header->set_header_id($_POST['header_id']);
                $return = $header->delete_header();
                break;
            
            case 'check_position':
                $return = $header->check_position();
                break;
                
            case 'ins_header':
                //Insert new Header
                $return = $header->insert_header();
                break;

                case 'updt_inventory':
                    //Insert new Header
                    $item->set_item_id($_POST['item_id']);
                    $return = $item->updt_inventory();
                    break;

            default:
                # code...
                break;
        }

        echo json_encode($return);
    }

    Class Item{
        protected $item_id;

        public function set_item_id($id){
            $this->item_id = $id;
        }
        public function get_item_id(){
            return $this->item_id;
        }

        private function get_item_count(){
            global $connection;
            global $items_table;

            $query = "SELECT * FROM {$items_table}";
            $get_count = $connection->prepare($query);
            $get_count->execute();
            $result = $get_count->get_result();
            $c = 0;
            while($count = $result->fetch_assoc()){
                $c++;
            }
            return $c;
        }

        public function get_all(){
            global $connection;
            global $items_table;
            global $items_headers_table;
            $return_array = array();
            
            $table = "<table class=\"items-table\" cellspacing=\"0\"><tr class=\"table-header\"><th class=\"items-cell\">Item</th><th class=\"items-cell\">Price</th><th class=\"items-cell\">Qty in Stock</th><th class=\"items-cell\"></th><th class=\"items-cell\"></th></tr>";

            //Get Items Headers
            $items_headers = array();
            $query = "SELECT * FROM {$items_headers_table}";
            $get_headers = $connection->prepare($query);
            $get_headers->execute();
            $result = $get_headers->get_result();
            while($headers = $result->fetch_assoc()){
                //Store each header in array
                if(array_key_exists($headers['position'], $items_headers)){
                    // FIGURE THIS OUT

                } else {
                    $items_headers[$headers['position']] = array("id" => $headers['id'], "header" => $headers['header']);
                }
            }

            //Get Items Table
            $query = "SELECT * FROM {$items_table} ORDER BY position ASC ";
            $get_items = $connection->prepare($query);
            $get_items->execute();
            $result = $get_items->get_result();
            while ($items = $result->fetch_assoc()){
                $item_name = $items['name'];
                $item_price = $items['price'];
                $item_id = $items['id'];
                $item_position = $items['position'];
                if(is_null($items['quantity_in_stock'])){
                    $quantity_in_stock = "";
                } else {
                    $quantity_in_stock = $items['quantity_in_stock'];
                }
                if(isset($items_headers[$item_position]) && !empty($items_headers[$item_position])){
                    $table .= "<tr id=\"header_" . $items_headers[$item_position]["id"] . "_row\"><td colspan=\"2\" class=\"items-cell items-table-header\"><span class=\"editable-cell-span\" id=\"items_header_" . $items_headers[$item_position]["id"] . "\" ondblclick=\"edit_header(" . $items_headers[$item_position]["id"] . ")\">" . strtoupper($items_headers[$item_position]["header"]) . "</span></td><td class=\"items-cell\"></td><td class=\"items-cell\"><button class=\"dlt-btn table-btn\" onclick=\"dlt_header('" . $items_headers[$item_position]["id"] . "')\">Delete</button></td><td class=\"items-cell\"></td></tr>
                        <tr id=\"item_" . $item_id . "_row\" ><td class=\"items-cell\"><span class=\"editable-cell-span\" id=\"name-" . $item_id . "\" ondblclick=\"edit_cell('name', "
                        . $item_id . ")\">" . $item_name . "</span></td><td class=\"items-cell\">N<span class=\"editable-cell-span\" id=\"price-" . $item_id . "\" ondblclick=\"edit_cell('price', "
                        . $item_id . ")\">" . $item_price . "</span></td><td class=\"items-cell\" style=\"text-align:center;\"><span id=\"current_qty_" . $item_id . "\">" . $quantity_in_stock . "</span></td><td class=\"items-cell\"><button class=\"dlt-btn table-btn\" onclick=\"dlt_item('" . $item_id . "')\">Delete</button></td><td class=\"items-cell\"><button class=\"item-select-btn table-btn\" style=\"min-height:2em;\" onclick=\"updt_inventory('" . $item_id . "')\">Update Inventory</button></td></tr>";
                } else {
                    $table .= "<tr id=\"item_" . $item_id . "_row\" ><td class=\"items-cell\"><span class=\"editable-cell-span\" id=\"name-" . $item_id . "\" ondblclick=\"edit_cell('name', "
                        . $item_id . ")\">" . $item_name . "</span></td><td class=\"items-cell\">N<span class=\"editable-cell-span\" id=\"price-" . $item_id . "\" ondblclick=\"edit_cell('price', "
                        . $item_id . ")\">" . $item_price . "</span></td><td class=\"items-cell\" style=\"text-align:center;\"><span id=\"current_qty_" . $item_id . "\">" . $quantity_in_stock . "</span></td><td class=\"items-cell\"><button class=\"dlt-btn table-btn\" onclick=\"dlt_item('" . $item_id . "')\">Delete</button></td><td class=\"items-cell\"><button class=\"item-select-btn table-btn\" style=\"min-height:2em;\" onclick=\"updt_inventory('" . $item_id . "')\">Update Inventory</button></td></tr>";
                }
            }

            $table .= "</table>";

            $return_array['status'] = "success";
            $return_array['table'] = $table;

            return $return_array;

        }

        public function update_item(){
            global $connection;
            global $items_table;
            $return_array = array();

            if($_POST['col'] == "name"){
                $query = "UPDATE {$items_table} SET name = ? WHERE id = ? ";
                $update_item = $connection->prepare($query);
                $update_item->bind_param("si", $_POST['val'], $this->item_id);
            } else {
                $query = "UPDATE {$items_table} SET price = ? WHERE id = ? ";
                $update_item = $connection->prepare($query);
                $update_item->bind_param("ii", $_POST['val'], $this->item_id);
            }
            $update_item->execute();

            if($update_item){
                $return_array['status'] = "success";
                $return_array['updated_column'] = $_POST['col'];
                $return_array['item_id'] = $this->item_id;
                $return_array['val'] = $_POST['val'];
            }else {
                $return_array['status'] = "failed";
                $return_array['error'] = "Update failed";
            }
            return $return_array;
        }

        public function delete_item(){
            global $connection;
            global $items_table;
            $return_array = array();

            $query = "SELECT position FROM {$items_table} WHERE id = ? ";
            $get_position = $connection->prepare($query);
            $get_position->bind_param("i", $this->item_id);
            $get_position->execute();
            $result = $get_position->get_result();
            $position = $result->fetch_assoc();

            $query = "DELETE FROM {$items_table} WHERE id = ?";
            $delete = $connection->prepare($query);
            $delete->bind_param("i", $this->item_id);
            $delete->execute();

            if($delete){
                //move items
                $item_count = $this->get_item_count();
                $this->move_items($position['position'], $item_count);

                $return_array['status'] = "success";
                $return_array['item_id'] = $this->item_id;
            } else {
                $return_array['status'] = "failed";
                $return_array['error'] = "Unable to delete item. Please try again";
            }
            return $return_array;
        }

        public function create_item(){
            global $connection;
            global $items_table;
            $return_array = array();

            $new_item_count = $this->get_item_count() + 1;
            $new_item_position = $_POST['position'] + 1;
            $this->move_items($new_item_count, $new_item_position);

            $query = "INSERT INTO {$items_table} (name, price, position) VALUES (?, ?, ?) ";
            $new_item = $connection->prepare($query);
            $new_item->bind_param("sii", $_POST['name'], $_POST['price'], $new_item_position);
            $new_item->execute();
            if($new_item){
                $return_array['status'] = "success";
                $return_array['id'] = $connection->insert_id;
            } else {
                $return_array['status'] = "failed";
                $return_array['error'] = "Unable to create item. Please try again";
            }
            return $return_array;
        }

        private function move_items($old_position, $new_position){
            global $connection;
            global $items_table;

            if($old_position < $new_position){
                /*
                    1. Select all items with position between (old + 1) and new
                    2. Substract 1 to their positions
                    3. Update each item
                */
                $old_position_query = $old_position + 1;
                $new_position_query = $new_position + 1;
                $query = "SELECT id, position FROM {$items_table} WHERE position BETWEEN ? AND ? ";
                $get_new_position_items = $connection->prepare($query);
                $get_new_position_items->bind_param("ii", $old_position_query, $new_position_query);
                $get_new_position_items->execute();
                $result = $get_new_position_items->get_result();
                while($new_position_items = $result->fetch_assoc()){
                    $position = $new_position_items['position'] - 1;
                    $query = "UPDATE {$items_table} SET position = ? WHERE id = ? ";
                    $update_position = $connection->prepare($query);
                    $update_position->bind_param("ii", $position, $new_position_items['id']);
                    $update_position->execute();
                }
            } elseif($old_position > $new_position){
                /*
                    1. Select all items with position between (old - 1) and new
                    2. Add 1 from their positions
                    3. Update each item
                */
                $old_position_query = $old_position - 1;
                $query = "SELECT id, position FROM {$items_table} WHERE position BETWEEN ? AND ? ";
                $get_new_position_items = $connection->prepare($query);
                $get_new_position_items->bind_param("ii", $new_position, $old_position_query);
                $get_new_position_items->execute();
                $result = $get_new_position_items->get_result();
                while($new_position_items = $result->fetch_assoc()){
                    $position = $new_position_items['position'] + 1;
                    $query = "UPDATE {$items_table} SET position = ? WHERE id = ? ";
                    $update_position = $connection->prepare($query);
                    $update_position->bind_param("ii", $position, $new_position_items['id']);
                    $update_position->execute();
                }
            } else {
                // old and new positions are the same, do nothing
            }
            return;
        }

        public function updt_inventory(){
            global $connection;
            global $items_table;
            global $inventory_update_log_table;

            $return_array = array();
            $time = time();

            //Set New Inventory values
            $query = "UPDATE {$items_table} SET quantity_in_stock = ? WHERE id = ? ";
            $update_inventory = $connection->prepare($query);
            $update_inventory->bind_param("ii", $_POST['new_quantity'], $this->item_id);
            $update_inventory->execute();

            //Save Old Inventory values
            $query = "INSERT INTO {$inventory_update_log_table} (item_id, time_updated, updated_by, previous_quantity_in_stock, new_quantity_in_stock) VALUES (?, ?, ?, ?, ?)";
            $log_update = $connection->prepare($query);
            $log_update->bind_param("iiiii", $this->item_id, $time, $_SESSION['user_id'], $_POST['old_quantity'], $_POST['new_quantity']);
            $log_update->execute();

            $return_array['status'] = "success";
            $return_array['new_quantity'] = $_POST['new_quantity'];
            $return_array['item_id'] = $this->item_id;
            return $return_array;
            
        }

        //End of Class Item
    }

    Class Header{
        protected $header_id;

        public function set_header_id($id){
            $this->header_id = $id;
        }
        public function get_header_id(){
            return $this->header_id;
        }

        public function update_header(){
            global $connection;
            global $items_headers_table;
            $return_array = array();
            
            $query = "UPDATE {$items_headers_table} SET header = ? WHERE id = ? ";
            $update_header = $connection->prepare($query);
            $update_header->bind_param("si", $_POST['val'], $this->header_id);
            $update_header->execute();
            if($update_header){
                $return_array['status'] = "success";
            }else{
                $return_array['status'] = "failed";
            }
            return $return_array;
        }

        public function delete_header(){
            global $connection;
            global $items_headers_table;
            $return_array = array();

            $query = "DELETE FROM {$items_headers_table} WHERE id = ?";
            $delete = $connection->prepare($query);
            $delete->bind_param("i", $this->header_id);
            $delete->execute();
            if($delete){
                $return_array['status'] = "success";
            } else {
                $return_array['status'] = "failed";
            }
            return $return_array;
        }

        public function insert_header(){
            global $connection;
            global $items_headers_table;
            $return_array = array();

            $query = "INSERT INTO {$items_headers_table} (header, position) VALUES (?, ?) ";
            $new_item = $connection->prepare($query);
            $new_item->bind_param("si", $_POST['header'], $_POST['position']);
            $new_item->execute();
            if($new_item){
                $return_array['status'] = "success";
                $return_array['id'] = $connection->insert_id;
            } else {
                $return_array['status'] = "failed";
                $return_array['error'] = "Unable to insert header. Please try again";
            }
            return $return_array;
        }

        public function check_position(){
            global $connection;
            global $items_headers_table;
            $count = 0;

            $query = "SELECT position FROM {$items_headers_table}";
            $get_headers = $connection->prepare($query);
            $get_headers->execute();
            $result = $get_headers->get_result();
            while($headers = $result->fetch_assoc()){
                //Match each position value against given value
                if($headers['position'] == $_POST['position']){
                    $count++;
                    break;
                }
            }
            if($count == 0){
                return 0;
            } else {
                return 1;
            }
        }
    }

?>
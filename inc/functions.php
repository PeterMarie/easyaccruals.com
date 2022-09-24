<?php

    function check_connect($query) {
        global $connection;
        if(!$query){
            die("error: " . mysqli_error($connection));
        }
   }

   function start_session($name){
        session_name($name);
        session_start();
        setcookie(session_name(),session_id());
   }
   
   function check_log_in_for_login_page(){
         if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] == 1)) {
             $location = "location: ../";
             header($location);
        }       
   }

   function check_log_in($redirect){
         if(!isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] != 1)) {
             $location = "location:" . $redirect;
             header($location);
        }       
   }

   function check_admin($redirect){
         if(!isset($_SESSION['admin']) && ($_SESSION['admin'] < 1)) {
            if(!isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] != 1)){
                $location = "location:" . $redirect . "login";
                header($location);
                //header("location:../login");
            } else {
                $location = "location:" . $redirect;
                header($location);
            }
        }
   }

   function get_values_by_id($table, $id){ //returns array of attributes
         global $connection;
         $query = "SELECT * FROM ";
         $query .= $table;
         $query .= " WHERE id= " . $id;
         $get_values = mysqli_query($connection, $query);
        // check_connect($get_values, "142");
         $value = mysqli_fetch_array($get_values);
         return $value;
     }
     
    function trunc($phrase, $max_words) { //Open Source function! Tks guys!!!
        $phrase_array = explode(' ',$phrase);
        if(count($phrase_array) > $max_words && $max_words > 0)
            $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
        return $phrase;
    }
?>

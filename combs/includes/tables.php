<?php
	if(file_exists('../inc/db.php')){
		require_once('../inc/db.php');
	}
	if(file_exists('../../inc/db.php')){
		require_once('../../inc/db.php');
	}
	if(file_exists('../../../inc/db.php')){
		require_once('../../../inc/db.php');
	}

	//Change this line for each new business//
	define("BUSINESS_NAME_DB", "combs", true);
	//Change this line for each new business//
	
	$query = "SELECT business_name_full, phone_business, business_address, email_business FROM clients WHERE business_name_short = ? ";
	$business_name_db = BUSINESS_NAME_DB;
	$get_business_details = $connection->prepare($query);
	$get_business_details->bind_param("s", $business_name_db);
	$get_business_details->execute();
	$result = $get_business_details->get_result();
	$business_details = $result->fetch_assoc();

	$sales_table = BUSINESS_NAME_DB . "_sales";
	$users_table = BUSINESS_NAME_DB . "_users";
	$items_table = BUSINESS_NAME_DB . "_items";
	$items_headers_table = BUSINESS_NAME_DB . "_items_headers";
	$logs_table = BUSINESS_NAME_DB . "_logs";
	$deleted_users_table = BUSINESS_NAME_DB . "_deleted_users";
	$deleted_items_table = BUSINESS_NAME_DB . "_deleted_items";
	$deleted_headers_table = BUSINESS_NAME_DB . "_deleted_headers";
	$inventory_update_log_table = BUSINESS_NAME_DB . "_inventory_update_log";
	$receipt_table = BUSINESS_NAME_DB . "_receipts";

?>
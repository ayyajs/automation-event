<?php
	include('includes/common.php');
	
	if  ($_POST['hd_event_id'] > 0) {
		$response = $objEvent_type->deleteEventtype($_POST['hd_event_id']);
	}
	$event_type = $objEvent_type->listEventtype($data);
	include('template/list_event_type.php');
?>
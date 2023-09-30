<?php
	include('includes/common.php');
	if  ($_POST['hd_event_id'] > 0) {
		$response = $objEvent->deleteEvent($_POST['hd_event_id']);
	}
	$event_data = $objEvent->listEvent($data);
	$event_type  = $objEvent->getEventtype();
	include('template/list_event.php');
?>
<?php
	include('includes/common.php');

	if ($_POST) {
		$response = $objEvent_type->updateEventtype($_POST);
	}
	$event_data   =  $objEvent_type->editEventtype($_GET['event_id']); 
	include('template/edit_event_type.php');
?>
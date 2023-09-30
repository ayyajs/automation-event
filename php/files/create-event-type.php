<?php
	include('includes/common.php');
	if ($data) {
		$response	=	$objEvent_type->createEventtype($data);
		if ($response['message_cls'] != 'error_msg') {
			unset($data);
		}
	}
	include('template/create_event_type.php');
?>
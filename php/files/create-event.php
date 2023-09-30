<?php
	include('includes/common.php');
	if ($_POST['frmsubmit']) {
		$response	=	$objEvent->createEvent($data, $_FILES);
		if ($response['message_cls'] != 'error_msg') {
			unset($data);
		}
	}
	$event_type  = $objEvent->getEventtype();
	$location    = $objEvent->getLocation();
	include('template/create_event.php');
?>
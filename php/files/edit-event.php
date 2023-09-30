<?php
	include('includes/common.php'); 
	if ($_POST['frmsubmit']) { 
		$response =  $objEvent->updateEvent($_POST, $_FILES, $_GET);
	}
	$response_img 	  =	 $objEvent->deleteImage($_POST['hd_event_id'],  $_POST['hd_event_id_name']);
	$event_data   =  $objEvent->editEvent($_GET['event_id']);
	$event_type   =  $objEvent->getEventtype();
	$location     =  $objEvent->getLocation();
	include('template/edit_event.php');
?>
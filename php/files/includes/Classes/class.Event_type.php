<?php
class Event_type 
{	
	public function escape($value)
	{
		return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
	}	
	public function createEventtype($data)
	{
		global $objCommon;
		$data['frm_created_at'] 	= date('Y-m-d H:i:s');
		$data['frm_updated_at'] 	= date('Y-m-d H:i:s');		
		$where ="WHERE event_name = '".$this->escape($data['frm_event_name'])."' ";
		$check = $objCommon->doTotalRecordCnt(EVENT_TYPE, $where, 'id');
		if($check == 0) {
			$event_id = $objCommon->AddInfoToDB($data,'frm_', EVENT_TYPE);
			if ($event_id && $event_id > 0) {				
				$response['message']     = "Event details stored successfully";
				$response['message_cls'] = "success_msg";
			} else {
				$response['message']     = "Something wrong to create";
				$response['message_cls'] = "error_msg";
			} 
		} else {
			$response['message']     = "Event type  is already exists.";
			$response['message_cls'] = "error_msg";
		}
		return $response;
	}
	public function listEventtype($data)
	{
		global $objCommon;

		$where_cls =  " WHERE id!='' ";
		
		if ($data['filter_event_type']) {
			$where_cls .= "AND  event_name LIKE '%".$data['filter_event_type']."%'  ";
		}
		if ($data['filter_status']) {
			$where_cls .= "AND  status = '".$data['filter_status']."' ";
		}

		return $objCommon->doGetTableListing(EVENT_TYPE,$where_cls,'event_name, status, id','', 'ORDER BY ' . 'id' . ' DESC');
	}
	public function editEventtype($id)
	{
		global $objCommon;
		$where = "WHERE id = '".(int)$id."' ";
		return $objCommon->doGetTableSingleRecord(EVENT_TYPE, $where);
	}
	public function updateEventtype($data)
	{	
		global $objCommon;

		$id = $_GET['event_id'];
		$where ="WHERE event_name = '".$this->escape($data['frm_event_name'])."' AND id != '".(int)$id."' ";
		$check = $objCommon->doTotalRecordCnt(EVENT_TYPE, $where, 'id');	
		if($check == 0) {
			$response = array();
			$where = " WHERE id = '".(int)$id."' ";
			$objCommon->UpdateInfoToDB($data, 'frm_', EVENT_TYPE, $where);
			$response['message']     = "Event details updated successfully"; 
			$response['message_cls'] = "success_msg";
		} else {
			$response['message']     = "Event type  is already exists.";
			$response['message_cls'] = "error_msg";
		}
		return $response;
	}
	public function deleteEventtype($id)
	{
		global $objCommon;
		if ($id != '') {
			$where = "WHERE id = '".(int)$id."'"; 
			$count = $objCommon->doTotalRecordCnt(EVENT_TYPE, $where, 'id');
			if ($count > 0) {
				$array = $objCommon->doDeleteById(EVENT_TYPE, 'id', $id);				
				$response['message']     = "Event details deleted successfully"; 
				$response['message_cls'] = "success_msg";
				return $response;
			}
		}
	}
}
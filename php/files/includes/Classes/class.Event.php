<?php
class Event 
{	
	public function escape($value)
	{
		return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
	}
	public function getEventtype($orderBy = 'event_name', $showAll = false)
	{
		global $objCommon;
		$where = '';
		if (!$showAll) {
			$where ="WHERE status = 'active'";
		}
		return $objCommon->doGetTableListing(EVENT_TYPE,$where,'event_name, status, id','', 'ORDER BY ' . $orderBy . ' ASC');
	}	
	public function getLocation($orderBy = 'location', $showAll = true)
	{
		global $objCommon;
		$where = '';
		if (!$showAll) {
			$where ="WHERE status = 'active'";
		}
		return $objCommon->doGetTableListing(LOCATION,$where,'location, location_id','', 'ORDER BY ' . $orderBy . ' ASC');
	}	
	public function createEvent($data, $file_data)
	{
		global $objCommon;
		$data['frm_event_date'] 	= date('Y-m-d', strtotime($data['frm_event_date']));
		$data['frm_food']			    = implode(",", $data['frm_food']);
		$data['frm_created_at'] 	= date('Y-m-d H:i:s');
		$data['frm_updated_at'] 	= date('Y-m-d H:i:s');		
		$where ="WHERE event_title = '".$this->escape($data['frm_event_title'])."' ";
		$check = $objCommon->doTotalRecordCnt(EVENT, $where, '*');
		if($check == 0) {
			$event_id = $objCommon->AddInfoToDB($data,'frm_', EVENT);

			if ($event_id && $event_id > 0) {
				if ($file_data['frm_image']['tmp_name']) {
    			$this->eventImageUpload($file_data, $event_id);
        }				
				$response['message']       = "Event details stored successfully";
				$response['message_cls']   = "success_msg";
			} else {
				$response['message']       = "Something wrong to create";
				$response['message_cls']	 = "error_msg";
			} 
		} else {
			$response['message']         = "Event title  is already exists.";
			$response['message_cls'] 		 = "error_msg"; 
		}
		return $response;
	}
	public function listEvent($data)
	{
		global $objCommon;
	
		// $where_cls =  " WHERE id!='' ";

		if ($data['filter_event_type']) {
			$where_cls .= "AND  B.event_name = '".$data['filter_event_type']."'  ";
		}
		if ($data['filter_event_title']) {
			$where_cls .= "AND  B.event_title LIKE '%".$data['filter_event_title']."%'  ";
		}
		if ($data['filter_from_date'] && $data['filter_to_date']) {
			$from_date = $data['filter_from_date'];
			$to_date = $data['filter_to_date'];
			$where_cls .= "AND  B.event_date BETWEEN '".$from_date."' AND '".$to_date."' ";
		}
		if ($data['filter_status']) {
			$where_cls .= "AND  B.status = '".$data['filter_status']."' ";
		}

		$sql    =   "SELECT A.event_name, B.event_title, B.event_id, B.image, B.status, B.event_date FROM ".EVENT_TYPE." AS A , ".EVENT." AS B WHERE A.id = B.event_name $where_cls ORDER BY B.event_id DESC ";
			return $objCommon->getSelectQuery($sql);

	}
	public function editEvent($id)
	{
		global $objCommon;
		$where = "WHERE event_id = '".(int)$id."' ";
		return $objCommon->doGetTableSingleRecord(EVENT, $where);
	}
	public function updateEvent($data, $file_data, $get_data)
	{	
		global $objCommon;	
		$id = $get_data['event_id'];
		$data['frm_event_date'] 	= 	date('Y-m-d', strtotime($data['frm_event_date']));
		$data['frm_food']			= implode(",", $data['frm_food']);
		$where ="WHERE event_title = '".$this->escape($data['frm_event_title'])."' AND event_id!='".(int)$id."' ";
		$check = $objCommon->doTotalRecordCnt(EVENT, $where, '*');
		if($check == 0) {
			$response = array();
			$where = " WHERE event_id = '".(int)$id."' ";
			$objCommon->UpdateInfoToDB($data, 'frm_', EVENT, $where);
			if ($file_data['frm_image']['tmp_name']) {
          $this->eventImageUpload($file_data, $id);
      }
			$response['message']     = "Event details updated successfully"; 
			$response['message_cls'] = "success_msg";
		} else {
			$response['message']     = "Event title  is already exists.";
			$response['message_cls'] = "error_msg";
		}
		return $response;
	}
	public function deleteEvent($event_id)
	{
		global $objCommon;
		if ($event_id != '') {
			$where = "WHERE event_id = '".(int)$event_id."'"; 
			$count = $objCommon->doTotalRecordCnt(EVENT, $where, 'event_id');
			if ($count > 0) {
				$row = $objCommon->doGetTableSingleRecord(EVENT, $where, 'image');
        if ($row['image']) {
            $this->unlinkPath($row['image']);
        }
				$array = $objCommon->doDeleteById(EVENT, 'event_id', $event_id);				
				$response['message']     = "Event details deleted successfully"; 
				$response['message_cls'] = "success_msg";
				return $response;
			}
		}
	}
	public function eventImageUpload($file_array, $event_id)
  {
    global $objCommon;
    global $global_config;

    $path           = $global_config["SiteUploadPath"];
    $filename       = time()."_".$file_array['frm_image']['name'];
    $destination    = $path.$filename;
    copy($file_array['frm_image']['tmp_name'], $destination);
    $image_size     =  getimagesize($file_array['frm_image']['tmp_name']);

    //Thumb
    $thumb_path =  $path.'thumb/'.$filename;
    if ($image_size[0] > $image_size[1]) {
      $objCommon->createthumb($destination, $thumb_path,"80","");
    } else {
      $objCommon->createthumb($destination, $thumb_path,"","80");                     
    }

    // Normal
    $normal_path   =  $path.'normal/'.$filename;
    if ($image_size[0] > $image_size[1]) {
      $objCommon->createthumb($destination, $normal_path,"100","");
    } else {
      $objCommon->createthumb($destination, $normal_path,"","100");                    
    }

    $sql = "UPDATE ".EVENT." SET image = '".$filename."' WHERE event_id = '".(int)$event_id."' ";
   return $objCommon->ExecuteQry($sql);
  }
  public function deleteImage($event_id, $event_id_name)
  {
		global $objCommon;
    if ($event_id > 0) {
        $this->unlinkPath($event_id_name);
        $sql = "UPDATE ".EVENT." SET image = '' WHERE event_id = '".(int)$event_id."' ";
        $objCommon->ExecuteQry($sql);
        
        $response_img['message']     =   "Image deleted successfully.";
        $response_img['message_cls'] =   "success_msg"; 
        return $response_img; 
    }
  }
  public function unlinkPath($event_id_name)
  {
	  global $objCommon;
    global $global_config;

    $event_image = $global_config["SiteUploadPath"].trim($event_id_name);
    if (file_exists($event_image)) {
       unlink($event_image);            
    } 

    $thumb_image = $global_config["SiteUploadPath"].'thumb/'.trim($event_id_name);
    if (file_exists($thumb_image)) {
       unlink($thumb_image);            
    } 

    $normal_image = $global_config["SiteUploadPath"].'normal/'.trim($event_id_name);
    if (file_exists($normal_image)) {
       unlink($normal_image);   
    } 
	}
}
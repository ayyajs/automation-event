
<?php include('header_event.php');?>
	<section id="outer_section">
		<?php include('left_menu.php');?>
		<?php if ($event_data) { ?>
		<div class="outer_form">
			<form name="frm_event_management" id="frm_event_management" method="POST" action="" enctype="multipart/form-data">
			<input type="hidden" name="hd_event_id" id="hd_event_id" value="">
            <input type="hidden" name="hd_event_id_name" id="hd_event_id_name" value=""> 
            <?php
               $image_available = 0;
               if ($event_data['image']) { 
                  $image_available = 1;
               } 
            ?>
               <input type="hidden" name="hd_image_exists" id="hd_image_exists" value="<?php echo $image_available; ?>">
               <h1 class="head">Edit Event</h1>
			<?php if ($response['message']) { ?>
					<div class="<?php echo $response['message_cls'];  ?>"> <?php echo $response['message']; ?></div>                
				<?php } ?>
				<?php if ($response_img['message']) { ?>
					<div class="<?php echo $response_img['message_cls'];  ?>"> <?php echo $response_img['message']; ?></div>                
				<?php } ?>
				<div class="center">
					<div class="input_row">
						<label class="input_label">Event Type<span class="star">*</span></label>
						<select name="frm_event_name" id="frm_event_name" class="option">
							<option value="">Please select</option>
							<?php							
							foreach($event_type as $event) {
							 ?>
								<option value="<?php echo $event['id']; ?>" <?php if ($event['id'] == $event_data['event_name']) { ?> selected <?php } ?>>
								<?php echo ucfirst($event['event_name']); ?>	
								</option>
							<?php } ?>	
						</select>
						</select>
					</div>
					<div class="input_row">
						<label class="input_label">Event Title<span class="star">*</span></label>
						<input type="text" name="frm_event_title" id="frm_event_title" value="<?php echo $event_data['event_title'] ?>" class="input_field">
						</select>
					</div>
					<div class="input_row">
						<label class="input_label">Event Location<span class="star">*</span></label>
						<select name="frm_event_location" id="frm_event_location" class="option">
							<option value="">Please select</option>
							<?php							
							foreach($location as $location_data) {
							 ?>
								<option value="<?php echo $location_data['location_id']; ?>" <?php if ($location_data['location_id'] == $event_data['event_location']) { ?> selected <?php } ?>>
								<?php echo ucfirst($location_data['location']); ?>	
								</option>
							<?php } ?>	
						</select>
					</div>
					<div class="input_row">
						<label class="input_label">Event Venue<span class="star">*</span></label>
						<textarea name="frm_venue" id="frm_venue" class="input_field" rows="5"><?php echo $event_data['venue'] ?></textarea>
					</div>
					<div class="input_row">
						<label class="input_label">Photo<span class="star">*</span></label>
						<input type="file" name="frm_image" id="frm_image" value="" class="input_field">
						<?php if ($event_data['image']) { ?>
                  		<img src="<?php $global_config["SiteGlobalPath"]?>uploads/thumb/<?php echo $event_data['image'];  ?>" class="edit_img" >
                  		<a onclick="return doDeleteImage('<?php echo $event_data['event_id']; ?>', '<?php echo $event_data['image']; ?>')" class="delete_img">Delete</a>
                  <?php } ?>
					</div>
				</div>
				<div class="center">
					<div class="input_row">
						<label class="input_label">Event Date<span class="star">*</span></label>
						<input type="text" name="frm_event_date" id="frm_event_date" class="input_field" value="<?php echo date("d-m-Y", strtotime($event_data['event_date'])) ?>">
					</div>
					<div class="input_row">
						<label class="input_label">Event Start Time<span class="star">*</span></label>
						<select name="frm_start_time" id="frm_start_time" class="option">
							<option value="">Please select</option>
							<?php for($i = 1; $i <= 24; $i++) { 
								$time = date("h.i A", strtotime("$i:00"));
							?>
    							<option value="<?php echo $time ?>" <?php echo ($time == $event_data['start_time'])?'selected':'' ?>><?php echo $time ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="input_row">
						<label class="input_label">Event End Time<span class="star">*</span></label>
						<select name="frm_end_time" id="frm_end_time" class="option">
							<option value="">Please select</option>
							<?php for($i = 1; $i <= 24; $i++) { 
								$time = date("h.i A", strtotime("$i:00"));
							?>
    							<option value="<?php echo $time ?>" <?php echo ($time == $event_data['end_time'])?'selected':'' ?>><?php echo $time ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="input_row">
						<div>
						<label class="input_label">Food<span class="star">*</span></label>
						<?php 
                     		$food =$event_data['food'];
                     		$arr=explode(",", $food); 
                  		?>
							<input type="checkbox" name="frm_food[]" id="frm_south_indian" class="" value="south indian" <?php if(in_array('south indian',$arr)){echo 'checked';} ?>>
							<label for="frm_south_indian" class="cursor">South Indian</label>
							<input type="checkbox" name="frm_food[]" id="frm_north_indian" class="" value="north indian" <?php if(in_array('north indian',$arr)){echo 'checked';} ?>>
							<label for="frm_north_indian" class="cursor">North Indian</label>
							<input type="checkbox" name="frm_food[]" id="frm_chinese" class="" value="chinese" <?php if(in_array('chinese',$arr)){echo 'checked';} ?>>
							<label for="frm_chinese" class="cursor">Chinese</label>
						</div>
						<label for="frm_food[]" generated="true" class="error"></label>


					</div>
					<div class="input_row">
						<label class="input_label">Status<span class="star">*</span></label>
							<div class="status_field">
								<input type="radio" name="frm_status" id="frm_active" class="radio_field" value="active" <?php if ($event_data['status'] == 'active') { ?> checked <?php } ?>>
								<label for="frm_active" class="cursor">Active</label>
								<input type="radio" name="frm_status" id="frm_inactive" class="" value="inactive" <?php if ($event_data['status'] == 'inactive') { ?> checked <?php } ?>>
								<label for="frm_inactive" class="cursor">Inactive</label>
							</div>
					</div>
						<label for="frm_status" generated="true" class="error"></label>
	           </div>
	           <div class="button_row">
	                  <input type="submit" name="frmsubmit" id="frmsubmit" value="UPDATE" class="button">
	                  <input type="reset" name="frmreset" id="frmreset" value="BACK" class="button" onclick="window.top.location='list-event.php'">
	               </div>
			</form>
		</div>
		<?php } else { ?>
         <div class="error_record" id="record">
            <p class="no_record">Invalid data.</p>
         </div>
         <?php } ?> 
	</section>
<?php include('footer.php');?>
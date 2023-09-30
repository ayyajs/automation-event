
<?php include('header_event.php');?>
	<section id="outer_section">
		<?php include('left_menu.php');?>
		<div class="outer_form">
			<form name="frm_event_management" id="frm_event_management" method="POST" action="" enctype="multipart/form-data">
			<input type="hidden" name="hd_image_exists" id="hd_image_exists" value="0">		<h1 class="head">Create Event </h1>
			<?php if ($response['message']) { ?>
					<div class="<?php echo $response['message_cls'];  ?>"> <?php echo $response['message']; ?></div>                
				<?php } ?>
				<div class="center">
					<div class="input_row">
						<label class="input_label">Event Type<span class="star">*</span></label>
						<select name="frm_event_name" id="frm_event_name" class="option">
							<option value="">Please select</option>
							<?php							
							foreach($event_type as $event) {
							?>
								<option value="<?php echo $event['id']; ?>" <?php if ($event['id'] == $data['frm_event_name']) { ?> selected <?php } ?>>
								<?php echo ucfirst($event['event_name']); ?>	
								</option>
							<?php } ?>	   
						</select>
						</select>
					</div>
					<div class="input_row">
						<label class="input_label">Event Title<span class="star">*</span></label>
						<input type="text" name="frm_event_title" id="frm_event_title" value="<?php echo $data['frm_event_title'] ?>" class="input_field">
						</select>
					</div>
					<div class="input_row"> 
						<label class="input_label">Event Location<span class="star">*</span></label>
						<select name="frm_event_location" id="frm_event_location" class="option">
							<option value="">Please select</option>
							<?php							
							foreach($location as $location_data) {
							 ?>
								<option value="<?php echo $location_data['location_id']; ?>" <?php if ($location_data['location_id'] == $data['frm_event_location']) { ?> selected <?php } ?>>
								<?php echo ucfirst($location_data['location']); ?>	
								</option> 
							<?php } ?>	
						</select>
					</div>
					<div class="input_row">
						<label class="input_label">Event Venue<span class="star">*</span></label>
						<textarea name="frm_venue" id="frm_venue" class="input_field" rows="5"><?php echo $data['frm_venue'] ?></textarea>
					</div>
					<div class="input_row">
						<label class="input_label">Photo<span class="star">*</span></label>
						<input type="file" name="frm_image" id="frm_image" value="" class="input_field">
					</div>
				</div>
				<div class="center">
					<div class="input_row">
						<label class="input_label">Event Date<span class="star">*</span></label>
						<input type="text" name="frm_event_date" id="frm_event_date" class="input_field" value="<?php echo $data['frm_event_date'] ?>"  >
					</div>
					<div class="input_row">
						<label class="input_label">Event Start Time<span class="star">*</span></label>
						 <select name="frm_start_time" id="frm_start_time" class="option">
							<option value="">Please select</option>
							<?php for($i = 1; $i <= 24; $i++) { 
								$time = date("h.i A", strtotime("$i:00"));
							?>
    							<option value="<?php echo $time ?>" <?php echo ($time == $data['frm_start_time'])?'selected':'' ?>><?php echo $time ?></option>
							<?php } ?>
						</select> 
					</div>
					<div class="input_row">
						<label class="input_label">Event End Time<span class="star">*</span></label>
						 <select name="frm_end_time" id="frm_end_time" class="option">

							<option value="">Please select</option>
							<?php for($j = 1; $j <= 24; $j++) { 
								$time = date("h.i A", strtotime("$j:00"));
							?>
    							<option value="<?php echo $time ?>" <?php echo ($time == $data['frm_end_time'])?'selected':'' ?>><?php echo $time ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="input_row">
						<div>
						<label class="input_label">Food<span class="star">*</span></label>
							<input type="checkbox" name="frm_food[]" id="frm_south_indian" class="" value="south indian" <?php if(in_array("south indian",$data['frm_food']?? [])){echo 'checked';} ?> >
							<label for="frm_south_indian" class="cursor">South Indian</label>
							<input type="checkbox" name="frm_food[]" id="frm_north_indian" class="" value="north indian" <?php if(in_array("north indian",$data['frm_food']?? [])){echo 'checked';} ?>>
							<label for="frm_north_indian" class="cursor">North Indian</label>
							<input type="checkbox" name="frm_food[]" id="frm_chinese" class="" value="chinese" <?php if(in_array("chinese",$data['frm_food']?? [])){echo 'checked';} ?>>
							<label for="frm_chinese" class="cursor">Chinese</label>
						</div>
						<label for="frm_food[]" generated="true" class="error"></label>


					</div>
					<div class="input_row">
						<label class="input_label">Status<span class="star">*</span></label>
							<div class="status_field">
								<input type="radio" name="frm_status" id="frm_active" class="radio_field" value="active" <?php if ($data['frm_status'] == 'active') { ?> checked <?php } ?>>
								<label for="frm_active" class="cursor">Active</label>
								<input type="radio" name="frm_status" id="frm_inactive" class="" value="inactive" <?php if ($data['frm_status'] == 'inactive') { ?> checked <?php } ?>>
								<label for="frm_inactive" class="cursor">Inactive</label>
							</div>
					</div>
						<label for="frm_status" generated="true" class="error"></label>
	           </div>
	           <div class="button_row">
	                  <input type="submit" name="frmsubmit" id="frmsubmit" value="SUBMIT" class="button" onsubmit =" Compare();">
	                  <input type="reset" name="frmreset" id="frmreset" value="CLEAR" class="button">
	               </div>
			</form>
		</div>
	</section>
	<?php include('footer.php');?>

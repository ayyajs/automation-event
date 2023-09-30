<?php include('header_event_type.php');?>
	<section id="outer_section">
		<?php include('left_menu.php');?>
		<?php if ($event_data) { ?>
		<div class="outer_form">
			<form name="frm_event_type" id="frm_event_type" method="POST" action="" enctype="multipart/form-data">				
				<h1 class="head">Edit Event Type</h1>
				<?php if ($response['message']) { ?>
					<div class="<?php echo $response['message_cls'];  ?>"> <?php echo $response['message']; ?></div>                
				<?php } ?>
				<div class="input_row">
					<div class="left_col">
						<label class="input_label">Event Type<span class="star">*</span></label>
					</div>
					<div class="right_col">
						<input type="text" name="frm_event_name" id="frm_event_name" class="input_field" value="<?php echo $event_data['event_name']?>">
					</div>
				</div>
				<div class="input_row">
					<div class="left_col">
						<label class="input_label">Description<span class="star">*</span></label>
					</div>
					<div class="right_col">
						<textarea name="frm_description" id="frm_description" class="input_field" rows="5"><?php echo $event_data['description']?></textarea>
					</div>
				</div>
				<div class="input_row">
					<div class="left_col">
						<label class="input_label">Status<span class="star">*</span></label>
					</div>
					<div class="right_col">
						<div class="status_field">
							<input type="radio" name="frm_status" id="frm_active" class="radio_field" value="active" <?php echo ($event_data['status'] == 'active')?'checked':'' ?> >
							<label for="frm_active" class="cursor">Active</label>
							<input type="radio" name="frm_status" id="frm_inactive" class="" value="inactive"  <?php echo ($event_data['status'] == 'inactive')?'checked':'' ?> >
							<label for="frm_inactive" class="cursor">In Active</label>
						</div>
						<label for="frm_status" generated="true" class="error"></label>
					</div>
				</div>
				<div class="button_row">
                  <input type="submit" name="frmsubmit" id="frmsubmit" value="UPDATE" class="button">
                  <input type="reset" name="frmreset" id="frmreset" value="BACK" class="button" onclick="window.top.location='list-event-type.php'">
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

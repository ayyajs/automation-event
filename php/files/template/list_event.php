
<?php include('header_event.php'); ?>
      <section id="section">
         <?php include('left_menu.php'); ?>
         <div class="table_out">
            <form name="frm_event_type" id="frm_event_type" method="POST" action="">
               <input type="hidden" name="hd_event_id" id="hd_event_id" value="">
               <h1 class="title  ">List Event </h1>
                <?php if ($response['message']) { ?>
               <div class="<?php echo $response['message_cls'];  ?>"> <?php echo $response['message']; ?></div>                
            <?php } ?>
            <div class="fillter">
               <!-- <div class="left_side"> -->
                  <div class="heading">
                        <h4 >FILTER BY</h4>
                     </div>
                  <div class="row_one">
                     
                     <div class="fillter_field">
                        <label class="filter_name">Event Type<span class="star">*</span></label>
                        <select name="filter_event_type" id="filter_event_type" class="filter_text">
                           <option value="">Please select</option>
                           <?php                   
                           foreach($event_type as $event) {
                            ?>
                              <option value="<?php echo $event['id']; ?>" <?php if ($event['id'] == $_POST['filter_event_type']) { ?> selected <?php } ?>>
                              <?php echo ucfirst($event['event_name']); ?> 
                              </option>
                           <?php } ?>  
                        </select>
                     </div>
                     <div class="fillter_field">
                        <label class="filter_name">Event Tilte<span class="star">*</span></label>
                        <input type="text" name="filter_event_title" id="filter_event_title" value="<?php echo $data['filter_event_title']; ?>" class="filter_text">
                     </div>
                     <div class="fillter_field">
                        <label class="filter_name status">Status<span class="star">*</span></label>
                        <input type="radio" name="filter_status" id="filter_status_active" value="active" <?php if ($data['filter_status'] == 'active') { ?> checked <?php } ?> >
                        <label for="filter_status_active" class="cursor">Active</label> 
                        <input type="radio" name="filter_status" id="filter_status_inactive" value="inactive" <?php if ($data['filter_status'] == 'inactive') { ?> checked <?php } ?>>
                        <label for="filter_status_inactive" class="cursor">InActive</label>  
                     </div>
                  </div>
                  <div class="row_one">
                     <div class="fillter_field">
                         <label class="filter_name from_date">Form Date<span class="star">*</span></label>
                        <input type="date" name="filter_from_date" id="filter_from_date" class="filter_text" value="<?php echo $_POST['filter_from_date'] ?>">
                     </div>
                     <div class="fillter_field">
                         <label class="filter_name to_date">To Date<span class="star">*</span></label>
                        <input type="date" name="filter_to_date" id="filter_to_date" class="filter_text" value="<?php echo $_POST['filter_to_date'] ?>">
                     </div>
                     
                     <div class="serach_button">
                        <input type="submit" name="Search" id="Search" value="Search" class="button_search">
                        <input type="button" name="view" id="view" value="View All" onclick="window.top.location='list-event.php'" class="button_search">
                     </div>
                  </div>
               </div>
               <div class="table">
                  <div class="tr">
                     <div class="th number">Sl.No.</div>
                     <div class="th">Event Type</div>
                     <div class="th">Event Title</div>
                     <div class="th">Event Date</div>
                     <div class="th">Event Photo</div>
                     <div class="th action">Status</div>
                     <div class="th action">Action</div>
                  </div>
                  <?php if($event_data) { ?>
                  <?php
                     $inc = 1;
                     foreach($event_data as $event) { ?>
                  <div class="tr">
                     <div class="td"><?php echo $inc++; ?></div>
                      <div class="td"><?php echo $event['event_name'];  ?></div>
                     <div class="td "><?php echo $event['event_title'];  ?></div>
                     <div class="td event_date">
                        <?php
                           $date    =   $event['event_date'];
                           echo date('d-m-Y', strtotime($date));
                        ?>                           
                     </div>
                     <div class="td photo">
                        <?php if ($event['image']) { ?>   
                        <img src="<?php $global_config["SiteGlobalPath"]?>uploads/normal/<?php echo $event['image'];  ?>" >
                        <?php } ?>
                     </div>
                     <div class="td data_action">
                     <?php 
                     if($event['status'] == 'active') { ?>
                        <div class="green"><?php echo ucfirst($event['status']);?></div>
                     <?php } else { ?>
                        <div class="red"><?php echo ucfirst(ucwords($event['status'],"n"));?></div>

                      <?php } ?>       
                     </div>
                     <div class="td data_action"> <a href="edit-event.php?event_id=<?php echo $event["event_id"]; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> | <a onclick="return doDeleteEvent('<?php echo $event["event_id"] ; ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                  </div>
                  <?php } ?>
                  <?php }else { ?>
               </div>
               <div class="record"> No Record(s) found.</div>
               <?php }?>
            </form>
         </div>
      </section>
   <?php include('footer.php'); ?>
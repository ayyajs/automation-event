<?php include('header_event_type.php'); ?>
      <section id="section">
         <?php include('left_menu.php'); ?>
         <div class="table_out">
            <form name="frm_event_type" id="frm_event_type" method="POST" action="">
               <input type="hidden" name="hd_event_id" id="hd_event_id" value="">
               <h1 class="title">List Event Type</h1>
               <?php if ($response['message']) { ?>
               <div class="<?php echo $response['message_cls'];  ?>"> <?php echo $response['message']; ?></div>                
               <?php } ?>
               <div class="fillter">
                  <div class="inside_fillter">
                     <div class="heading">
                        <h4 >FILTER BY</h4>
                     </div>
                     <div class="fillter_field">
                        <label class="filter_name">Name<span class="star">*</span></label>
                        <input type="text" name="filter_event_type" id="filter_event_type" value="<?php echo $data['filter_event_type']; ?>" class="filter_text">

                        <label class="filter_name">Status<span class="star">*</span></label>
                        <input type="radio" name="filter_status" id="filter_status_active" value="active" <?php if ($data['filter_status'] == 'active') { ?> checked <?php } ?>>
                        <label for="filter_status_active" class="cursor">Active</label> 
                        <input type="radio" name="filter_status" id="filter_status_inactive" value="inactive" <?php if ($data['filter_status'] == 'inactive') { ?> checked <?php } ?>>
                        <label for="filter_status_inactive" class="cursor">InActive</label>  
                     </div>
                     <div class="serach_button">
                        <input type="submit" name="Search" id="Search" value="Search" class="button_search">
                        <input type="button" name="view" id="view" value="View All" onclick="window.top.location='list-event-type.php'" class="button_search">
                     </div>
                  </div>
               </div>
               <div class="table">
                  <div class="tr">
                     <div class="th number">Sl.No.</div>
                     <div class="th">Event Name</div>
                     <div class="th action">Status</div>
                     <div class="th action">Action</div>
                  </div>
                  <?php if($event_type) { ?>
                  <?php
                     $inc = 1;
                     foreach($event_type as $event) { ?>
                  <div class="tr">
                     <div class="td"><?php echo $inc++; ?></div>
                     <div class="td"><?php echo $event['event_name'];  ?></div>
                     <div class="td data_action">
                     <?php 
                     if($event['status'] == 'active') { ?>
                        <div class="green"><?php echo ucfirst($event['status']);?></div>
                     <?php } else { ?>
                        <div class="red"><?php echo ucfirst(ucwords($event['status'],"n"));?></div>

                      <?php } ?>       
                     </div>
                     <div class="td data_action"> <a href="edit-event-type.php?event_id=<?php echo $event["id"]; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> | <a onclick="return doDeleteEventtype('<?php echo $event["id"] ; ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
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
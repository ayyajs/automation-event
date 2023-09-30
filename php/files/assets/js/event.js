
$(function () {
   $("#frm_event_management").validate({ 
      rules    : {
         frm_event_name: {
            required :  true,
         },
         frm_event_title : {
            required :  true,
         },
         frm_event_location : {
            required :  true,
         },
         frm_venue:{
            required :  true,
         },
         frm_image: {
           required : function(element) {
               var image_exists = $("#hd_image_exists").val();
               if(image_exists == 0) {
                   return true;
               } else { 
                   return false;
               }
           },
           extension: "jpg|jpeg|png",
         },
         frm_event_date : {
            required : true, 
         },
         frm_start_time : {
            required :  true,

         },
         frm_end_time : {
            required :  true,
         },
         'frm_food[]' : {
            required :  true,
         },

         frm_status : {
            required :  true,
         },
      },
      messages : {
         frm_event_name : {
            required    :  "Please select event type",
         },
         frm_event_title : {
            required    :  "Please enter event title",
         },
         frm_event_location : {
            required    :  "Please select event location",
         },
          frm_venue : {
            required    :  "Please  enter event venue",
         },
         frm_image: {
            required: "Please upload image",
            extension: "Please upload file in these format only (jpg, jpeg, png)",
         },
         frm_event_date : {
            required    :  "Please select event date",
         },
         frm_start_time : {
            required    :  "Please select event start time",
         },
         frm_end_time : {
            required    :  "End time always greater then start time",
         },
         'frm_food[]' : {
            required    :  "Please choose food ",
         },
         frm_status : {
            required    :  "Please select status",
         },
      },
      submitHandler: function(form) {
               form.submit();
         }
   });
});
$(function() {
   $( "#frm_event_date" ).datepicker({ minDate: 0, dateFormat: 'dd-mm-yy'});
 });


function doDeleteImage(event_id, event_photo) 
{
    if (confirm("Are you sure you want to delete this image?")) {
        jQuery("#hd_event_id").val(event_id);
        jQuery("#hd_event_id_name").val(event_photo);
        jQuery("#frm_event_management").submit();                   
        return true;
    } else {
        return false;
    }
}
function doDeleteEvent(event_id) 
{
    if (confirm("Are you sure you want to delete this event?")) {    
        jQuery("#hd_event_id").val(event_id);
        jQuery("#frm_event_type").submit();
        return true;
    } else {
        return false;
    }
}

$(function () {
   $("#frm_event_type").validate({ 
      rules    : {
         frm_event_name        :  {
            required :  true,
         },
         frm_description        :  {
            required :  true,
         },
         frm_status        :  {
            required :  true,
         },
      },
      messages : {
         frm_event_name        :  {
            required    :  "Please enter event name",
         },
         frm_description :  {
            required    :  "Please enter event description",
         },
         frm_status :  {
            required    :  "Please select status",
         },
      },
      submitHandler: function(form) {
               form.submit();
      }
   });
});
function doDeleteEventtype(event_id) 
{
    if (confirm("Are you sure you want to delete this event type?")) {    
        jQuery("#hd_event_id").val(event_id);
        jQuery("#frm_event_type").submit();
        return true;
    } else {
        return false;
    }
}
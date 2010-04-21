$(document).ready(function(){

  $("#start_date, #end_date").datepicker();

  /**
   * toggle the text field to fill in "other" location
   */
  $('#location').change(function() {
    toggle_section('location_other',$(this).val()=='other');
  });
  toggle_section('location_other',$('#location').val()=='other');

  /**
   * toggle the edndate to show if HireType::Intern selected
   */
  $('#hire_type').change(function() {
    toggle_section('end_date_row',$(this).val()=='Intern');
  });
  toggle_section('end_date_row',$('#hire_type').val()=='Intern');

  /**
   * For the two checkboxes, toggle the sections the represent
   */
  $('#mail_needed').click(function() {
    toggle_section('mail_box',$(this).attr('checked'));
  });
  $('#machine_needed').click(function() {
    toggle_section('machine_box',$(this).attr('checked'));
  });
  toggle_section('mail_box',$('#mail_needed').attr('checked'));
  toggle_section('machine_box',$('#machine_needed').attr('checked'));

  update_default_username_display();

  /**
   * update the default username lable
   * note: this is display only, it is recalculated server-side
   */
  $('#first_name, #last_name').focusout(function() {
    update_default_username_display();
  });

  

});

function toggle_section(section_id, change_to_show) {
  if(change_to_show) {
    $('#'+section_id).show();
  } else {
    $('#'+section_id).hide();
  }
}
function update_default_username_display() {
    if($("#first_name").length>0) {
      var first = $("#first_name").val().length>0?$("#first_name").val()[0].toLowerCase():'';
      $("#default_username").val(
        first + $("#last_name").val().toLowerCase()
      );
    }
}

$(document).on('keydown',function(e) {
 	// ESCAPE key pressed
 	if (e.keyCode == 27) {
    $('#confirm').modal('hide');
    $('#restore-item').modal('hide');
    $(".modal_body").modal('hide');
    $('#nqpopup').modal('hide');
    $('#emailpopup').modal("hide");
    $('#appointment_modal').modal("hide");
  }
});
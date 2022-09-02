https://bootstrap-datepicker.readthedocs.io/en/latest/

https://uxsolutions.github.io/bootstrap-datepicker

--------------------

JS File

<script src="assets/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="assets/libraries/bootstrap-datepicker/js/bootstrap-datepicker-function.js"></script>

--------------------

SCSS File

/* Libraries S */
@import "libraries/bootstrap-datepicker/scss/bootstrap-datepicker.min";
/* Libraries E */

--------------------

HTML

<div class="form-group ac-form-group">
    <label class="ac-label" for="acLastName">DOB</label>
    <input type="text" class="form-control ac-input ac-datepicker-basic" id="acDOB" name="acBOB" onpaste="return false;" ondrop="return false;">
</div>

<div class="ac-datepicker-basic input-daterange">
    <input type="text" class="form-control ac-input" id="acLastName" name="acLastName" maxlength="60" onpaste="return false;" ondrop="return false;">
    <input type="text" class="form-control ac-input" id="acLastName" name="acLastName" maxlength="60" onpaste="return false;" ondrop="return false;">
</div>

--------------------

Use Class

$(document).ready(function() {
    acDatepickerBasic (".ac-datepicker-basic");
});

$(document).ready(function() {
    acDatepickerPrevDisabled (".ac-datepicker-basic");
});

$(document).ready(function() {
    acDatepickerNextDisabled (".ac-datepicker-basic");
});

$(document).ready(function() {
    acDatepickerBasicRange (".ac-datepicker-basic", "Select the (EVENT) date.");
});
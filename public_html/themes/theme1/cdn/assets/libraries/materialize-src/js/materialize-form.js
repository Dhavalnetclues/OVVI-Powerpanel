var label, target;

function Form(){}

Form.prototype.alterForm = function(){    
    $('.ac-form-md input, .ac-form-md  textarea').focus(function(e){
        form.setLabel(e.target);
        form.checkFocused();
    });
    $('.ac-form-md input, .ac-form-md textarea').focusout(function(e){
        form.setLabel(e.target);
        form.checkUnfocused(e.target);
    });
};

Form.prototype.setLabel = function(target){
    label= $('label[for='+target.id+']');
};

Form.prototype.getLabel = function(){
    return label;
};

Form.prototype.checkFocused = function(){
    form.getLabel().addClass('ac-active','');
};

Form.prototype.checkUnfocused = function(target){
    if($(target).val().length == 0){
        form.getLabel().removeClass('ac-active');
    }
};

form = new Form();

function initialize(){
    form.alterForm();
}
initialize();

$("form.ac-form-md ").ready(function() {
    //$(this).find('input[placeholder], input[disabled], input[readonly], textarea[placeholder], textarea[disabled], textarea[readonly], select').parents('.form-group').addClass('ac-active-label');
    if($('input[value], textarea[value]').val().length > 0){
        $(this).find('input[value], textarea[value]').parents('.form-group').children('.ac-label').addClass('ac-active');
    }
});

jQuery.each(jQuery('textarea[data-autoresize]'), function() {
    var offset = this.offsetHeight - this.clientHeight; 
    var resizeTextarea = function(el) {
        jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
    };
    jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
});

$(".ac-input").on("change", function () {
//   var fileName = $(this).val();
    $('input[type="file"]').change(function (e) {
            var fileName = e.target.files[0].name;
//            $(".file-name").html(fileName);
   $(this).attr('data-file', fileName);
        });
});
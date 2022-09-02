var Validate = (function() {
  var handleForm = function() {
    $(".privacy-removal-form").validate({
      errorElement: "span", //default input error message container
      errorClass: "dpl_required", // default input error message class
      ignore: [],
      rules: {
        first_name: {
          required: true,
          xssProtection: true,
          check_special_char: true,
          no_url: true
        },
        last_name: {
          xssProtection: true,
          check_special_char: true,
          no_url: true
        },
        email: {
          required: true,
          emailFormat: true
        },
        authorized: {
          required: true
        },
        reason: {
          messageValidation: true,
          xssProtection: true,
          check_special_char: true,
          badwordcheck: true,
          no_url: true
        },
        "g-recaptcha-response": {
          required: true
        }
      },
      messages: {
        first_name: {
          required: "First Name is required"
        },
        last_name: {
          required: "Last Name is required"
        },
        email: {
          required: "Please enter your email address"
        },
        authorized: {
          required: "Please confirm your authorization"
        },
        "g-recaptcha-response": {
          required: "Please select I'm not a robot"
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("id") == "g-recaptcha-response") {
          error.insertAfter(element.parent());
        } else if (element[0].type == "checkbox") {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      },
      invalidHandler: function(event, validator) {
        $(".alert-danger", $(".privacy-removal-form")).show();
      },
      highlight: function(element) {
        // hightlight error inputs
        $(element)
          .closest(".form-group")
          .addClass("has-error"); // set error class to the control group
      },
      unhighlight: function(element) {
        $(element)
          .closest(".form-group")
          .removeClass("has-error"); // set error class to the control group
      },
      submitHandler: function(form) {
        form.submit();
      }
    });

    $(".privacy-removal-form input").keypress(function(e) {
      if (e.which == 13) {
        if (
          $(".privacy-removal-form")
            .validate()
            .form()
        ) {
          $(".privacy-removal-form").submit(); //form validation success, call ajax form submit
        }
        return false;
      }
    });
  };
  return {
    //main function to initiate the module
    init: function() {
      handleForm();
    }
  };
})();

jQuery(document).ready(function() {
  Validate.init();

  jQuery.validator.addMethod(
    "noSpace",
    function(value, element) {
      if (value.trim().length <= 0) {
        return false;
      } else {
        return true;
      }
    },
    "No space please and don't leave it empty"
  );

  var blacklist = /\b(nude|naked|sex|porn|porno|sperm)\b/; /* many more banned words... */
  jQuery.validator.addMethod(
    "badwordcheck",
    function(value) {
      return !blacklist.test(value.toLowerCase());
    },
    "Please remove bad word/inappropriate language."
  );

  jQuery.validator.addMethod(
    "emailFormat",
    function(value, element) {
      // allow any non-whitespace characters as the host part
      return (
        this.optional(element) ||
        /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(
          value
        )
      );
    },
    "Enter valid email format"
  );

  jQuery.validator.addMethod(
    "messageValidation",
    function(value, element) {
      // allow any non-whitespace characters as the host part
      return this.optional(element) ||
        /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(
          value
        ) == false
        ? true
        : false;
    },
    "Enter valid message"
  );

  jQuery.validator.addMethod(
    "xssProtection",
    function(value, element) {
      // allow any non-whitespace characters as the host part
      return this.optional(element) ||
        /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(
          value
        ) == false
        ? true
        : false;
    },
    "Enter valid Name"
  );

  $.validator.addMethod(
    "check_special_char",
    function(value, element) {
      if (value != "") {
        if (value.match(/^[\x20-\x7E\n]+$/)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    },
    "Please enter valid input"
  );

  $.validator.addMethod(
    "no_url",
    function(value, element) {
      var re = /^[a-zA-Z0-9\-\.\:\\]+\.(com|org|net|mil|edu|COM|ORG|NET|MIL|EDU)$/;
      var re1 = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
      var trimmed = $.trim(value);
      if (trimmed == "") {
        return true;
      }
      if (trimmed.match(re) == null && re1.test(trimmed) == false) {
        return true;
      }
    },
    "URL not allow"
  );
  $("input[name=email]").change(function() {
    var email = $(this).val();
    var trim_email = email.trim();
    if (trim_email) {
      $(this).val(trim_email);
      return true;
    }
  });
});

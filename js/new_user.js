$(function() {
    $.validator.addMethod("regex", function(value, element, pattern) {
        if (pattern instanceof Array) {
            for (p of pattern) {
                if (!p.test(value))
                    return false;
            }
            return true;
        } else {
            return pattern.test(value);
        }
    }, "Please enter a valid input.");

    $('#new_user_form').validate({
        rules: {
            mail_user: {
                remote: {
                    url: 'member/mail_validate',
                    type: 'post',
                    data: {
                        mail: function() {
                            return $("#mail").val();
                        }
                    }
                },
                required: true,
                regex: [/[@]/]
            },
            name_user: {
                required: true,
                minlength: 3,
            },
            password_user: {
                required: true,
                minlength: 8,
                maxlength: 16,
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
            }
        },
        messages: {
            mail_user: {
                remote: "This  mail already exist",
                required: "Please  enter a mail",
                regex: 'bad format for mail, must contain @',
            },
            name_user: {
                required: 'Please enter your fullName',
                minlength: 'minimum   3 characters',
            },
            password_user: {
                required: 'Please enter  a password',
                minlength: 'minimum  8 characters',
                maxlength: 'maximum  16 characters',
                regex: 'bad password  format',
            }
        }
    });
    $("input:text:first").focus();
});
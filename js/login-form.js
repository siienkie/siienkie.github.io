$().ready(function() {

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

    $("#window").validate({
        rules: {
            mail: {
                remote: {
                    url: 'member/mail_validate_exist',
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
            password: {
                required: true,
                minlength: 8,
                maxlength: 16,
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/]
            }
        },
        messages: {
            mail: {
                remote: "This mail is not exist",
                required: "Please enter your mail",
                regex: 'bad format for mail, must contain @',
            },
            password: {
                required: "Please enter your password",
                minlength: "Your password must contain min 8 characters",
                maxlength: "Your password must contain max 16 characters",
                regex: "bad password format",
            }
        }
    })

    $("input:text:first").focus();
})
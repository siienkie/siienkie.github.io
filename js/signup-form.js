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

    $('#window').validate({
        rules: {
            mail: {
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
            fullName: {
                required: true,
                minlength: 3,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 16,
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
            },
            confirm_password: {
                required: true,
                minlength: 8,
                maxlength: 16,
                equalTo: "#password",
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
            }
        },
        messages: {
            mail: {
                remote: "This mail already exist",
                required: "Please enter a mail",
                regex: 'bad format for mail, must contain @',
            },
            fullName: {
                required: 'Please enter yout fullName',
                minlength: 'minimum 3 characters',
            },
            password: {
                required: 'Please enter a password',
                minlength: 'minimum 8 characters',
                maxlength: 'maximum 16 characters',
                regex: 'bad password format',
            },
            password_confirm: {
                required: 'Please confirm a password',
                minlength: 'minimum 8 characters',
                maxlength: 'maximum 16 characters',
                equalTo: 'must be identical to password above',
                regex: 'bad password format',
            }
        }
    });
    $("input:text:first").focus();
})



// $().ready(function() {

//     $('#window').validate({
//         rules: {
//             mail: {
//                 remote: {
//                     url: 'member/mail_validate',
//                     type: 'post',
//                     data: {
//                         mail: function() {
//                             return $("#mail").val();
//                         }
//                     }
//                 },
//                 required: true,
//                 //regex: /^[a-zA-Z][a-zA-Z0-9]*$/,
//                 //must contain @
//             },
//             fullName: {
//                 required: true,
//                 minlength: 3,
//             },
//             password: {
//                 required: true,
//                 minlength: 8,
//                 maxlength: 16,
//                 regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
//             },
//             confirm_password: {
//                 required: true,
//                 minlength: 8,
//                 maxlength: 16,
//                 equalTo: "#password",
//                 regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
//             }
//         },
//         messages: {
//             mail: {
//                 remote: 'this mail is already taken',
//                 required: 'required',
//                 //regex: 'bad format for mail',
//             },
//             fullName: {
//                 required: 'required',
//                 minlength: 'minimum 3 characters',
//             },
//             password: {
//                 required: 'required',
//                 minlength: 'minimum 8 characters',
//                 maxlength: 'maximum 16 characters',
//                 regex: 'bad password format',
//             },
//             password_confirm: {
//                 required: 'required',
//                 minlength: 'minimum 8 characters',
//                 maxlength: 'maximum 16 characters',
//                 equalTo: 'must be identical to password above',
//                 regex: 'bad password format',
//             }
//         }
//     });
//     $("input:text:first").focus();
// })
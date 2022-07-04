$(function() {
    //console.log("asass");

    $(".btn_delete_users").click(function(e) {
        e.preventDefault(); //empeche d'executer le SUBMIT
        let idUser = this.id.substr(4);
        //console.log(idUser);
        // console.log("entre");

        var ret = null;
        $('#confirmDialog_users').dialog({


            resizable: false,
            height: 300,
            width: 500,
            modal: true,
            autoOpen: true,
            buttons: {
                Oui: function() {
                    $.post('member/delete_user', {
                            id_user: idUser,
                            id_board: 2
                        },
                        function(data) {
                            //cote javascript
                            location.href = "member/index";
                        });

                    $(this).dialog("close");
                },
                Non: function() {
                    ret = "non";
                    $(this).dialog("close");
                }
            },
            close: function() {
                if (ret !== null)
                    $("#content").html("rep:" + ret);
                else
                    $("#content").html("error");
            }
        });
    });
});
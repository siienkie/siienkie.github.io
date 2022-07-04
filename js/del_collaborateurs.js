$(function() {
    //console.log("asass");

    $(".btn_delete_colla").click(function(e) {
        e.preventDefault(); //empeche d'executer le SUBMIT
        let idUser = this.id.substr(4);
        // console.log(idColla);
        // console.log("entre");

        var ret = null;
        $('#confirmDialog_colla').dialog({
            show: {
                effect: "fade",
                duration: 500
            },
            hide: {
                effect: "blind",
                duration: 500
            },
            // resizable: false,
            height: 230,
            width: 370,
            // modal: true,
            autoOpen: true,
            buttons: {
                Oui: function() {
                    $.post('board/delete_collaborate', {
                            id_user: idUser,
                            id_board: 14
                        },
                        function(data) {
                            //cote javascript
                            location.href = "board/collaborators/" + 14;
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


    function deleteCollaborator(id) {

        $.post("board/delete_collaborate_ajax/" + idBoard, { "id_user": id, "id_board": idBoard }, function(data) {
            recupCollaborators();
            getPotentialCollaborators();
        });



    }

});
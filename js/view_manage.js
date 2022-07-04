let del_collabo = $('#delete_collaborator');
var id;
var collaborators;
var collaborator;
$(function() {

    recupCollaborators()
    getPotentialCollaborators()


    function recupCollaborators() {

        var html;
        var collaborator;

        $.get("board/get_collaborator/" + idBoard, function(data) {
            let html = "";
            collaborators = $(JSON.parse(data));

            if (collaborators.length === 0) { html += "<tr><td>No collaborators yet !</td></tr>" } else {
                collaborators.each(function() {

                    var fullName = this.fullName;
                    var mail = this.mail;
                    var id = this.id;
                    //html += " <input type='hidden' name='id_board' value=<?= $id_board ?>>";
                    html += fullName + " (" + mail + ")";
                    html += "<input id='value-coll-" + id + "' class='value-coll' type='hidden' name='id_board' value=" + id + ">";
                    html += "<input id='coll-" + id + "'  class='poubelle' type='button' name='collaborators' value='🗑'>";
                    html += "<br>";



                });
            };


            $(".list_collaborators").html(html);
            $('.poubelle').click(function(e) {


                e.preventDefault(); //empeche d'executer le SUBMIT

                var idcollaborator = $("#value-" + this.id).val()
                var ret = null;
                $('#confirmDialog_user').dialog({
                    resizable: false,
                    height: 300,
                    width: 500,
                    modal: true,
                    autoOpen: true,
                    buttons: {
                        Oui: function() {

                            deleteCollaborator(idcollaborator);
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
        })


    }



    function deleteCollaborator(id) {

        $.post("board/delete_collaborate_ajax/" + idBoard, { "id_user": id, "id_board": idBoard }, function(data) {
            recupCollaborators();
            getPotentialCollaborators();
        });



    }

    function getPotentialCollaborators() {

        $.get("board/get_potential/" + idBoard, function(data) {
            var html = "";
            collaborators = $(JSON.parse(data));

            if (collaborators.length === 1) {


                html += "<option > No one to add </option>";
                $('.add_collaborator').prop("disabled", true);


            } else {
                collaborators.each(function() {

                    if (this.id !== idOwner) {
                        $('.add_collaborator').attr("disabled", false);
                        html += "<option id= 'potential-" + this.id + "' value='" + this.id + "'>" + this.fullName + " (" + this.mail + ")</option>";

                    }

                });
            }


            $("#coll-pot").html(html);

        });

    }
    $(".add_collaborator").click(function(e) {
        e.preventDefault();

        var newcollaborator = $('#coll-pot option:selected').attr('value');
        $.post("board/add_collaborate_ajax/" + idBoard, { "collaborator": newcollaborator, "id_board": idBoard }, function(data) {
            recupCollaborators();
            getPotentialCollaborators();
        });




    });



});
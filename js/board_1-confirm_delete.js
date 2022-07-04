$(function() {
    var idText;
    var idSub;


    $(".btn_delete_column").click(function(e) {

        e.preventDefault(); //empeche d'executer le SUBMIT
        let idColumn = this.id.substr(4);



        var ret = null;

        $('#confirmDialog_column').dialog({
            show: {
                effect: "fade",
                duration: 500
            },
            hide: {
                effect: "blind",
                duration: 500
            },
            height: 230,
            width: 370,
            autoOpen: true,
            buttons: {
                Oui: function() {
                    $.post('board/delete_column', {
                            id_column: idColumn
                        },
                        function(data) {
                            //cote javascript
                            location.href = "board/board/" + idBoard;
                        });
                    $(this).dialog("close");
                },
                Non: function() {
                    $(this).dialog("close");
                }
            },
        });
    });




    $("#column_form").validate({

        rules: {
            add_column: {
                remote: {
                    url: 'board/column_name_validate_exist',
                    type: 'post',
                    data: {
                        id_board: function() {
                            return $("#this_board").val();
                        },
                        add_column: function() {
                            return $("#add_col").val();

                        }

                    }
                },
                minlength: 3
            }
        },
        messages: {
            add_column: {
                remote: "This column name already exist",
                minlength: "This name must contain min 3 characters",
            }
        }
    });





    $(".butt_card").click(function(e) {

        $("#f-" + this.id)


        var text = $(".v-" + this.id).val();
        //revoir la doc de each
        $("#f-" + this.id).validate({

            rules: {
                add_card: {
                    remote: {
                        url: 'board/card_name_validate_exist',
                        type: 'post',
                        data: {
                            id_board: function() {
                                return $("#this_board").val();

                            },
                            add_card: function() {
                                return text;
                            }
                        }
                    },
                    minlength: 3
                }
            },
            messages: {
                add_card: {
                    remote: "This card name already exist",
                    minlength: "This name must contain min 3 characters",
                }
            }
        });
    });








    $(".btn_delete_card").click(function(e) {
        e.preventDefault(); //empeche d'executer le SUBMIT
        let idCard = this.id.substr(4);




        var ret = null;

        $('#confirmDialog_card').dialog({
            show: {
                effect: "fade",
                duration: 500
            },
            hide: {
                effect: "blind",
                duration: 500
            },
            height: 230,
            width: 370,
            autoOpen: true,
            buttons: {
                Oui: function() {
                    $.post('board/delete_card', {
                            id_card: idCard
                        },
                        function(data) {
                            //cote javascript
                            location.href = "board/board/" + idBoard;
                        });
                    $(this).dialog("close");
                },
                Non: function() {
                    $(this).dialog("close");
                }
            },
        });
    });


    $(".btn_delete_board").click(function(e) {
        e.preventDefault(); //empeche d'executer le SUBMIT

        $('#confirmDialog_board').dialog({
            show: {
                effect: "fade",
                duration: 500
            },
            hide: {
                effect: "blind",
                duration: 500
            },
            height: 230,
            width: 370,
            autoOpen: true,
            buttons: {
                Oui: function() {
                    $.post('board/delete_board', {
                            id_board: idBoard
                        },
                        function(data) {
                            //cote javascript
                            location.href = "board";
                        });
                    $(this).dialog("close");
                },
                Non: function() {
                    $(this).dialog("close");
                }
            },
        });
    });


    function getColumns() {
        // éviter de mettre ce message à chaque fois, sinon ça donne un effet moins fluide
        //tblMessages.html("<tr><td>Loading...</td></tr>");

        $.get("board/get_visible_columns_service/" + "<?= $board->ID ?>", function(data) {
            columns = data;

            //sortMessages();
            displayTable();
        }, "json").fail(function() {
            tblMessages.html("<tr><td>Error encountered while retrieving the messages!</td></tr>");
        });

    }




    function displayTable() {

        for (var c of columns) {
            c.Title;
        }

        //  tblMessages.html(html);
        //  $('#col_' + sortColumn).append(sortAscending ? ' &#9650;' : ' &#9660;');
    }
});

// $(function() {
//     alert('Heloo');
// });
$(function() {


    function newBoard() {
        var html = "";
        var idText = Math.random();
        var idSub = Math.random();
        html += "<input type='text' placeholder='add board' id=" + idText + " name='add_board'>";
        html += "<input type= 'submit'id=" + idSub + " value='Add'>";
        $("#newBoard").html(html);
    }
    $("#newBoard").validate({
        rules: {
            add_board: {
                remote: {
                    url: 'board/board_name_validate_exist',
                    type: 'post',
                    data: {
                        title_board: function() {
                            return $(".title_board").val();
                        }
                    }
                },
                minlength: 3
            }

        },
        messages: {
            add_board: {
                remote: "This board name already exist",
                minlength: "This name must contain min 3 characters",
            }
        }
    });




});
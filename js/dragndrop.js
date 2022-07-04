$(function() {

    function disableButton() {
        $(".arrow").removeAttr("type").attr("type", "hidden");
    }



    function getColumnDrag() {
        var columns = $(".board-dnd").children("div");
        var count = columns.length;
        for (var i = 0; i < count; i++) {
            var column = columns.get(i).id;
            $.post("board/update_position_column_ajax/", { "set-postion": i, "col-drag": column });
        }
    }

    function getCardDrag() {
        var columns = $(".board-dnd").children("div");
        var count = columns.length;
        for (var i = 0; i < count; i++) {
            var column = columns.get(i);
            var columnpost = columns.get(i).id;
            var div = $(column).children("div");
            var groupcard = $(div).children("article");
            var countcard = groupcard.length;
            for (var c = 0; c < countcard; c++) {
                var card = groupcard.get(c).id;
                $.post("board/update_position_card_ajax/", { "set-card": c, "card-drag": card, "col": columnpost });
            }
        }
    }


    var cards = $(".column").children("article");

    disableButton();
    $(".column").sortable({
        connectWith: ".column",
        update: function(event, ui) {
            getCardDrag();

        }
    }).disableSelection();


    $(".board-dnd").sortable({
        connectWith: ".board-dnd",
        update: function(event, ui) {
            getColumnDrag();

        }
    }).disableSelection();

    function disableButton() {
        $(".arrow").removeAttr("type").attr("type", "hidden");
    }
});
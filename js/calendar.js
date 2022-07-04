$(function() {
    var calendar;
    getCalendar();
    getAllBoards();


    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }


    function getCalendar() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'UTC',
            initialView: 'dayGridMonth',
            editable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,dayGridMonth,listMonth'
            },


            eventClick: function(info) {
                let html = "";
                html += "ID: " + info.event.id + "<br>";
                html += "Titre: " + info.event.title + "<br>";
                html += "Due date: " + info.event.start + "<br>";


                $('#popup').html(html);
                $('#popup').dialog({
                    autoOpen: false,
                    title: "Informations des cartes",
                    width: 400,
                    height: 200,
                    show: {
                        effect: "fade",
                        duration: 500
                    },
                    hide: {
                        effect: "blind",
                        duration: 500
                    },
                });
                $('#popup').dialog('open');

            },

            events: [],

        });

        calendar.render();
    }




    function getAllBoards() {

        $.get("board/all_board_getting", function(data) {
            let html = "";
            var boards = $(JSON.parse(data));

            boards.each(function() {
                idBoard = this.ID;
                var color = getRandomColor();
                html += "<input type='checkbox' style='color:" + color + ";' name='" + this.Title + "' id='board-" + this.ID + "' class ='isCheck' value='" + this.ID + "' checked />";
                html += "<label style='color:" + color + ";' for='" + this.Title + "'>" + this.Title + "</label>";
                $(".list_boards").html(html);
                var items = [];
                $.post("board/all_card_from_board/", { "id_board": idBoard }, function(data) {
                    var cards = $(JSON.parse(data));
                    cards.each(function() {
                        if (this.DueDate !== null) {
                            items = { id: this.ID, title: this.Title, start: this.DueDate, end: this.DueDate };
                            calendar.addEvent(items);

                        }

                    });




                });


            });









            $('.isCheck').click(function() {
                var color_event = $("#" + this.id).css("color")

                if ($('#' + this.id).is(":checked")) {
                    idBoard = this.value;

                    $.post("board/all_card_from_board/", { "id_board": idBoard }, function(data) {
                        var cards = $(JSON.parse(data));
                        cards.each(function() {
                            if (this.DueDate === null) {

                            } else {
                                var event = calendar.getEventById(this.ID);
                                if (event !== null) {
                                    event.remove();
                                }
                                var date = $.datepicker.formatDate('yy-mm-dd', new Date());
                                var datum = this.DueDate;
                                if (date > datum) { color_event = "red" } else if (date === datum) { color_event = 'orange' }



                                var items = { id: this.ID, title: this.Title, start: this.DueDate, end: this.DueDate, backgroundColor: color_event };
                                calendar.addEvent(items);





                            }

                        });
                    });


                } else if ($('#' + this.id).not(":checked")) {
                    idBoard = this.value;

                    $.post("board/all_card_from_board/", { "id_board": idBoard }, function(data) {
                        var cards = $(JSON.parse(data));
                        cards.each(function() {
                            if (this.DueDate !== null) {
                                var event = calendar.getEventById(this.ID);
                                event.remove();
                            }
                        });
                    });
                }
            });
        });
    }


    function update(idBoard) {
        $.post("board/all_card_from_board/", { "id_board": idBoard }, function(data) {
            var cards = $(JSON.parse(data));
            //console.log(cards);
            cards.each(function() {
                if (this.DueDate === null) {

                } else {
                    var date = $.datepicker.formatDate('yy-mm-dd', new Date());
                    var datum = this.DueDate;
                    if (date > datum) { color_event = "red" } else if (date === datum) { color_event = 'orange' }

                    var items = { id: this.ID, title: this.Title, start: this.DueDate, end: this.DueDate, backgroundColor: color_event };
                    calendar.addEvent(items);
                }
            });
        });
    }

})
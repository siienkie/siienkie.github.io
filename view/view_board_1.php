<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <title>Board</title>

    <link rel="stylesheet" href="css/styles.css" />

    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
    <script src="lib/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>

    <script>
        var idBoard = <?= $board->ID ?>;
    </script>
    <script src="js/dragndrop.js"></script>
    <script src="js/board_1-confirm_delete.js"></script>

</head>

<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">

    <div class="user_menu">
        <a id="selected">Board "<?= $board->Title ?>"</a>
        <a id="idle" href="board/index">Boards</a>
        <?php if (Member::is_admin_members($user->id)) : ?>
            <a id="idle" href="member/index">Manage users</a>
        <?php endif; ?>
        <a id="idle" href="board/calendar">Calendar</a>
        <?php if (Member::is_admin_members($user->id)) : ?>🤴<?php else : ?>🧑<?php endif; ?>
        <label id="idle"><?= $user->fullName ?></label>
        <a href='main/logout'>⛔</a>
    </div>
</header>

<body>

    <div class="title">
        <a id="title">Board "<?= $board->Title ?>"</a>

        <a href="board/edit_board/<?= $board->ID ?>"><img id="icon_edit" src="images/bouton_edit.png"></a>
        <?php if ($user->id == $board->Owner) : ?>

            <a href="board/collaborators/<?= $board->ID ?>"><img id="icon_collab" src="images/collab.png"></a>
            <a class="btn_delete_board" href="board/delete_board_view/<?= $id_board ?>"><img id="icon_delete" src="images/bouton_delete.png"></a>
        <?php endif; ?>
    </div>
    <br>
    <?php echo "Created " . ControllerBoard::diffDate() . " by " . '<a id="lien">' . $owner->fullName . '</a>' . ControllerBoard::modified($board->ModifiedAt); ?>
    <br>
    <div class="board">
        <div class="board-dnd" ondrop="drop(event)" ondragover="allowDrop(event)">
            <?php foreach ($columns as $column) : ?>
                <div class="div_col" id="<?= $column->ID ?>" draggable="true" ondragstart="drag(event)">

                    <?php $cards = Card::get_cards_by_column($column->ID); ?>

                    <?= $column->Title ?>
                    <br>
                    <form id='delColumn' class='link' action='board/delete_column_view/<?= $column->ID ?>' method='post'>
                        <input id="id_column" type="hidden" name="id_column" value=<?= $column->ID ?>>
                        <input type="hidden" name="id_board" value=<?= $column->Board ?>>
                        <button class="btn_delete_column" id="btn-<?= $column->ID ?>" type='submit'><img id="icon_delete" src="images/bouton_delete.png"></button>

                    </form>

                    <a href="board/edit_column/<?= $column->ID ?>"><img id="icon_edit" src="images/bouton_edit.png"></a>
                    <br>

                    <form class='link' action='board/move_column_up' method='post'>
                        <input type="hidden" name="position" value=<?= $column->Position ?>>
                        <input type="hidden" name="id_board" value=<?= $column->Board ?>>

                        <input class="arrow" type='submit' <?php if ($column->Position == 0) { ?> disabled="disabled" <?php } ?> name="up_col" value='◀'>
                    </form>

                    <form class='link' action='board/move_column_down' method='post'>
                        <input type="hidden" name="position" value=<?= $column->Position ?>>
                        <input type="hidden" name="id_board" value=<?= $column->Board ?>>
                        <input class="arrow" type='submit' <?php if ($column->Position == count($columns) - 1) { ?> disabled="disabled" <?php } ?> name="down_col" value='▶'>
                    </form>

                    <div class="column" ondrop="drop(event)" ondragover="allowDrop(event)" id=<?= $column->ID ?>>
                        <div class="empty"></div>
                        <?php foreach ($cards as $card) : ?>
                            <?php if (Card::check_due_date($card->DueDate) == true && $card->DueDate != NULL) : ?>
                                <article class='card_due_date' id="<?= $card->ID ?>" draggable="true" ondragstart="drag(event)" data-id=<?= $card->ID ?>><?= $card->Title ?>
                                <?php else : ?>
                                    <article class='card' id="<?= $card->ID ?>" draggable="true" ondragstart="drag(event)" data-id=<?= $card->ID ?>><?= $card->Title ?>
                                    <?php endif; ?>
                                    <br>
                                    <a id="title_card" class="link" href="card/view/<?= $card->ID ?>"><img id="icon_view" src="images/view.png"></a>
                                    <?php if (Comment::get_number_comment($card->ID) > 0) : ?>
                                        🗨(<?= Comment::get_number_comment($card->ID) ?>)
                                    <?php endif; ?>
                                    <a id="title_card" class="link" href="card/edit/<?= $card->ID ?>">📝</a>
                                    <a class="btn_delete_card" id="btn-<?= $card->ID ?>" href="card/delete_card/<?= $card->ID ?>"><img id="icon_delete" src="images/bouton_delete.png"></a>

                                    <br>

                                    <form class='link' action='board/move_card_inside_up' method='post'>
                                        <input type="hidden" name="position_up" value=<?= $card->Position ?>>
                                        <input type="hidden" name="id_board" value=<?= $column->Board ?>>
                                        <input type="hidden" name="id_column" value=<?= $card->Column ?>>
                                        <input class="arrow" type='submit' <?php if ($card->Position == 0) { ?> disabled="disabled" <?php } ?> name="up_card" value='🔼'>
                                    </form>


                                    <form class='link' action='board/move_card_inside_down' method='post'>
                                        <input type="hidden" name="position_down" value=<?= $card->Position ?>>
                                        <input type="hidden" name="id_board" value=<?= $column->Board ?>>
                                        <input type="hidden" name="id_column" value=<?= $card->Column ?>>
                                        <input class="arrow" type='submit' <?php if ($card->Position == count($cards) - 1) { ?> disabled="disabled" <?php } ?> name="down_card" value='🔽'>
                                    </form>

                                    <form class='link' action='board/move_card_outside_left' method='post'>
                                        <input type="hidden" name="id_column" value=<?= $card->Column ?>>
                                        <input type="hidden" name="position_before_left" value=<?= $card->Position ?>>
                                        <input type="hidden" name="id_board" value=<?= $column->Board ?>>
                                        <input type="hidden" name="id_card2" value=<?= $card->ID ?>>
                                        <input type="hidden" name="position_col" value=<?= $column->Position ?>>
                                        <input class="arrow" type='submit' <?php if ($column->Position == 0) { ?> disabled="disabled" <?php } ?> name="up_col" value='⏮'>
                                    </form>

                                    <form class='link' action='board/move_card_outside_right' method='post'>
                                        <input type="hidden" name="id_column" value=<?= $card->Column ?>>
                                        <input type="hidden" name="position_before_right" value=<?= $card->Position ?>>
                                        <input type="hidden" name="id_board" value=<?= $column->Board ?>>
                                        <input type="hidden" name="id_card2" value=<?= $card->ID ?>>
                                        <input type="hidden" name="position_col" value=<?= $column->Position ?>>
                                        <input class="arrow" type='submit' <?php if ($column->Position == count($columns) - 1) { ?> disabled="disabled" <?php } ?> name="down_col" value='⏭'>
                                    </form>
                                    </article>
                                <?php endforeach; ?>
                    </div>


                    <td>
                        <form class="form_card" id="f-s-<?= $column->ID ?>" action="board/board/<?= $board->ID ?>" method="post">
                            <input type="text" id="add_col" placeholder="add card" class="v-s-<?= $column->ID ?>" name="add_card" <?php if (isset($_POST['add_card']) && !empty($errors) && $_POST['add_card'] != "" && $_POST['id_column'] == $column->ID) : ?> value='<?= $_POST['add_card'] ?>' <?php endif; ?>>
                            <input type="hidden" name="id_column" value=<?= $column->ID ?>>
                            <input type="hidden" id="this_board" name="id_board" value=<?= $board->ID ?>>
                            <input id="s-<?= $column->ID ?>" class="butt_card" type="submit" value="+">
                        </form>
                    </td>
                </div>
            <?php endforeach; ?>
            <br>
        </div>
    </div>
    <div class="div_col">

        <form id="column_form" action="board/board/<?= $board->ID ?>" method="post">
            <input type="hidden" id="this_board" name="id_board" value=<?= $board->ID ?>>
            <input type="text" placeholder="add column" id="add_col" name="add_column" <?php if (isset($_POST['add_column']) && !empty($errors) && $_POST['add_column'] != "") : ?> value='<?= $_POST['add_column'] ?>' <?php endif; ?>>
            <input type="submit" class="add_butt" value="+">
        </form>
    </div>
    <?php if (count($errors) != 0) : ?>
        <div class='errors'>
            <p>Please correct the following error(s) :</p>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>




    <div id="content"></div>
    <div id="confirmDialog_card" title="‼ Delete card ‼" hidden>
        <p>Confirmez-vous la suppression de cette carte ? </p>
    </div>

    <div id="confirmDialog_column" title="‼ Delete column ‼" hidden>
        <p>Confirmez-vous la suppression de cette colonne ? </p>
    </div>

    <div id="confirmDialog_board" title="‼ Delete board ‼" hidden>
        <p>Confirmez-vous la suppression de cet tableau ?</p>
    </div>


</body>

</html>
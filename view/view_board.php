<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?= $web_root ?>" />
    <title>Boards</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
    <script src="lib/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>

</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">
    <div class="user_menu">
        <a id="selected">Boards</a>
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
    <h2>Your boards</h2>
    <ul>
        <?php foreach ($boards as $board) : ?>

            <?php if ($user->id == $board->Owner) : ?>
                <form action="board/board/<?= $board->ID ?>" method="post">
                    <a id="bouton_board" href="board/board/<?= $board->ID ?>"><?= $board->Title ?> (<?= Board::get_number_column($board->ID) ?> columns)</a>

                </form>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <form id="newBoard" action="board/index" method="post">
        <input type="text" placeholder="Add a board" id="add_col" class="title_board" name="add_board" <?php if (isset($_POST['add_board']) && !empty($errors) && $_POST['add_board'] != "") : ?> value=<?= $_POST['add_board'] ?> <?php endif; ?>>
        <input class="add_butt" type="submit" value="+"> 
    </form>

    <br>

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
    <br>
    <?php if (Member::get_number_collab($user->id) > 0) : ?>
        <h2>Boards shared with you</h2>

        <ul>
            <?php foreach ($boards as $board) : ?>


                <?php if (Member::is_collaborate($user->id, $board->ID)) : ?>
                    <a id="collab_board" href="board/board/<?= $board->ID ?>"><?= $board->Title ?> (<?= Board::get_number_column($board->ID) ?> columns)</a>

                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <br><br>
    <?php if (Member::is_admin_members($user->id)) : ?>
        <h2>Other's boards</h2>
        <ul>
            <?php foreach ($boards as $board) : ?>
                <?php if ($user->id != $board->Owner && !Member::is_collaborate($user->id, $board->ID)) : ?>
                    <a id="other_board" href="board/board/<?= $board->ID ?>"><?= $board->Title ?> (<?= Board::get_number_column($board->ID) ?> columns)</a>

                <?php endif; ?>
            <?php endforeach; ?>

        </ul>
    <?php endif; ?>
    <br><br>
    <!-- <form action='main/logout' method="post">
        <input type="submit" value="Log out">
    </form> -->
    <script src="js/board-form.js"></script>
</body>

</html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css">
    <title>Edit Board</title>
</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">

    <div class="user_menu">
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
    
    <h1>Edit name Board </h1>
    <form action='board/edit_board/<?= $_GET['param1'] ?>' method="post">
        <input type="text" name="edit_title_board" <?php if (isset($_POST['edit_title_board'])) : ?>value='<?= $_POST['edit_title_board'] ?>' <?php else : ?> value='<?= $board->Title ?>' <?php endif; ?>>
        <input id="bouton_form" type='submit' value='Edit'>
    </form>
    <a id="bouton_form_delete_cancel" href="board/board/<?= $_GET['param1'] ?>">Cancel</a>
    <br>
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

   

</body>

</html>
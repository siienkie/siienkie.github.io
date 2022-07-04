<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <title>Edit column</title>
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

    <h1>Column "<?= $column->Title ?>"</h1>
    <!-- Created <?= $column->CreatedAt ?> by <?= $author->fullName ?>.  -->

    <!--<?php echo "Created " . ControllerBoard::diffDate() . " by " . $author_fullname . ControllerBoard::modified($column->ModifiedAt); ?>-->



    <form action="board/edit_column/<?= $column->ID ?>" method="POST">
        <h2>Title</h2>

        <input type="text" name="Title_column" <?php if (isset($_POST['Title_column'])) : ?>value='<?= $_POST['Title_column'] ?>' <?php else : ?> value='<?= $column->Title ?>' <?php endif; ?>>

        <h2>Board</h2>
        <input type="text" name="Title" disabled="disabled" value='<?= $board->Title; ?>'>
        <input class="form_butt" id="bouton_form" type="submit" value="Edit Column">

    </form>

    <a id="bouton_form" href="board/board/<?= $column->Board ?>">Cancel</a>
    <br><br>


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
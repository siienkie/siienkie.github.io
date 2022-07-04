<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
    <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
    <script src="lib/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>

    <title>Add user</title>
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
    <h1>Add User</h1>
    <form id="new_user_form" action='member/add_user' method="POST">

        <h2>Name</h2>
        <input type="text" name="name_user" placeholder="Enter Full Name" <?php if (isset($_POST['name_user'])) : ?>value=<?= $_POST['name_user'] ?><?php endif; ?> required>

        <h2>Mail</h2>
        <input type="text" id="mail" name="mail_user" placeholder="Enter Mail" <?php if (isset($_POST['mail_user'])) : ?>value=<?= $_POST['mail_user'] ?><?php endif; ?> required>

        <h2>Password</h2>
        <input type="password" name="password_user" placeholder="Enter Password" required>

        <input class="form_butt" id="bouton_form" type="submit" value="Add User">

    </form>


    <a id="bouton_form" href="member/index">Cancel</a>
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
    <script src="js/new_user.js"></script>
</body>

</html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <title>Delete user</title>
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

<div id="container_delete">
        <div id="window_delete">
            <div id="delete_text">

    <h1>Delete User "<?= $user->fullName ?>"</h1>
    <h1>Are you sure?</h1>
    <p>Do you really want to delete this user?</p>


    <form class='link' action='member/index' method='post'>
        <input type="hidden" name="id_user" value=<?= $_GET['param1'] ?>>
        <input type="hidden" name="delete_user" value="oui">
        <input id="bouton_form_delete" type='submit' value='Delete'>
    </form>

    <a id="bouton_form_delete_cancel" href="member/index">Cancel</a>

            </div></div></div>




</body>

</html>
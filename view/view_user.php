<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <title>Users</title>

    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
    <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
    <script src="lib/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>
    <script src="js/del_users.js"></script>

</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">
    <div class="user_menu">
        <a id="selected">Manage users</a>
        <a id="idle" href="board/index">Boards</a>
        <a id="idle" href="board/calendar">Calendar</a>
        <?php if (Member::is_admin_members($user->id)) : ?>🤴<?php else : ?>🧑<?php endif; ?>
        <label id="idle"><?= $user->fullName ?></label>
        <a href='main/logout'>⛔</a>
    </div>
</header>

<body>
    <table id="tab_user">
        <tr>
            <td>Name</td>
            <td>Mail</td>
            <td>ID</td>
            <td>Role</td>
            <td>Registered</td>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->fullName ?></td>
                <td><?= $user->mail ?></td>
                <td><?= $user->id ?></td>
                <td><?= $user->role ?></td>
                <td><?= ControllerMember::diffDateUser($user->id) ?></td>
                <td>
                    <a href="member/edit/<?= $user->id ?>"><img id="icon_edit" src="images/bouton_edit.png"></a>
                </td>
                <?php if ($user->id != $logged->id) : ?>
                    <td>
                        <a class="btn_delete_users" id="btn-<?= $user->id ?>" href="member/delete_user_view/<?= $user->id ?>"><img id="icon_delete" src="images/bouton_delete.png"></a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <div id="confirmDialog_users" title="‼ Delete user ‼" hidden>
        <p>Confirmez-vous la suppression de cet user ?</p>
    </div>

    <a id="bouton_form" href="member/add_user">Add user</a>
</body>

</html>
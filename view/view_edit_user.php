<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <title>Edit user</title>
</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">

    <div class="user_menu">
        <a href="board/index">Boards</a>
        <?php if (Member::is_admin_members($user->id)) : ?>
            <a href="member/index">Manage users</a>
        <?php endif; ?>
        <a href="board/calendar">Calendar</a>
        <?php if (Member::is_admin_members($user->id)) : ?>🤴<?php else : ?>🧑<?php endif; ?>
        <label><?= $user->fullName ?></label>
        <a href='main/logout'>⛔</a>
    </div>
</header>

<body>
    <h1>User "<?= $user->fullName; ?>"</h1>
    <?php echo "Registered " .  ControllerMember::diffDate(); ?>

    <form action='member/edit/<?= $user->id; ?>' method="POST">

        <h2>Name</h2>
        <input type="text" name="name_user" <?php if (isset($_POST['name_user'])) : ?>value='<?= $_POST['name_user'] ?>' <?php else : ?> value='<?= $user->fullName ?>' <?php endif; ?>>

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

        <h2>Mail</h2>
        <input type="text" name="mail_user" <?php if (isset($_POST['mail_user'])) : ?>value='<?= $_POST['mail_user'] ?>' <?php else : ?> value='<?= $user->mail ?>' <?php endif; ?>>

        <h2>Change Password</h2>
        <input type="password" name="password_user" placeholder="Enter a new password">

        <?php if ($logged->id != $user->id) : ?>
            <h2>Role</h2>
            <select name="role_user" size="1">
                <option <?php if ($user->role == "user") : ?>selected<?php endif; ?>>user</option>
                <option <?php if ($user->role == "admin") : ?>selected<?php endif; ?>>admin</option>
            </select>
        <?php endif; ?>
        </br>
        </br>
        <input type="hidden" name="id_user" value=<?= $user->id ?>>
        <input id="bouton_form" type="submit" value="Edit User">

    </form>


    <a id="bouton_form" href="members/index">Cancel</a>
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
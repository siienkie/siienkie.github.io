<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <title>Collaborators</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />
    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>

    <script>
        const idBoard = "<?= $id_board ?>";
        const idOwner = "<?= $owner->id ?>";
    </script>
    <script src="js/view_manage.js"></script>


    <script src="js/del_collaborateurs.js"></script>



</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">
    <div class="user_menu">
        <a id="idle" href="board/board/<?= $board->ID ?>">Board "<?= $board->Title ?>"</a>
        <a id="idle" href="board/index">Boards</a>

        <?php if (Member::is_admin_members($user->id)) : ?>
            <a id="idle" href="member/index">Manage users</a>
        <?php endif; ?>
        <a id="idle" href="board/calendar">Calendar</a>
        <?php if (Member::is_admin_members($user->id)) : ?>🤴<?php else : ?>🧑<?php endif; ?>
        <label id="idle" ><?= $user->fullName ?></label>

        <a href='main/logout'>⛔</a>
    </div>
</header>

<body>
    <ul class='list_collaborators'>

        <?php foreach ($collaborators as $collaborator) : ?>

            <!-- <form action="board/delete_collaborate/<?= $id_board ?>" method="POST"> -->
            <form action="board/delete_colla_view/<?=$id_board?>" method="POST">
                <input type="hidden" name="id_board" value=<?= $id_board ?>>
                <input type="hidden" name="id_user" value=<?= $collaborator->id ?>>
                <label for="collaborators"><?= $collaborator->fullName ?> (<?= $collaborator->mail ?>)</label>
                <!-- <input type="submit" name="collaborators" value="🗑"> -->
                <input class="btn_delete_colla" id="btn-<?= $collaborator->id ?>" type='submit' value='🗑'>
            </form>

        <?php endforeach; ?>


    </ul>

    <br><br>


    <form id="collaborator_form" action="board/add_collaborate/<?= $id_board ?>" method="POST">
        <input type="hidden" name="id_board" value=<?= $id_board ?>>
        <label for="collaborators">Add a new collaborator </label>

        <select id="coll-pot" name="collaborators">
            <option selected disabled="true">Select a collaborator</option>
            <?php foreach ($users as $collaborator) : ?>
                <?php if (!Member::is_collaborate($collaborator->id, $board->ID) && $collaborator->id != $owner->id) : ?>
                    <option value=<?= $collaborator->id ?>><?= $collaborator->fullName ?> (<?= $collaborator->mail ?>)</option>
                <?php endif; ?>
            <?php endforeach; ?>
            <input class="add_collaborator" type="submit" value="➕">

        </select>
        <br><br>
        <div id="confirmDialog_user" title="‼ Delete Collaborator ‼" hidden>
            <p>Supprimer ce collaborateur ?</p>
        </div>

    </form>



    <div id="confirmDialog_colla" title="‼ Delete collaborateur ‼" hidden>
        <p>Confirmez-vous la suppression de cet collaborateur ?</p>
    </div>
</body>

</html>
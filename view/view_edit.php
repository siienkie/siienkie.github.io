<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <title>Edit card</title>
</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">
    <div class="user_menu">
        <a id="selected">Card "<?= $card->Title ?>"</a>
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
    <h1>Edit a card</h1>
    <?php echo "Created " . " by " . $author->fullName . " " . ControllerCard::diffDate() . "." . ControllerCard::modified($card->ModifiedAt) . "."; ?>
    <form action='card/edit/<?= $card->ID; ?>' method="POST">
        <h2>Title</h2>
        <input type="text" name="Title_card" value="<?= $card->Title ?>">
        <h2>Body</h2>
        <textarea name="Body" rows="10" cols="268"><?= $card->Body; ?></textarea>
        <h2>Due Date</h2>
        <input type="date" <?php if (!$card->DueDate == null) : ?> value=<?= $card->DueDate ?> <?php endif; ?> name="due_date" min=<?= Card::get_date_without_time($card->ID) ?>>
        <input type="hidden" name="id_board" value=<?= $board->ID ?>>
        <input type="hidden" name="id_card" value=<?= $card->ID ?>>
        <br><br>
        <input id="bouton_form" type="submit" value="Edit Card">
    </form>
    <a id="bouton_form_delete_cancel" href="board/board/<?= $board->ID ?>">Cancel</a>
    <br><br>
    <!-- <input type="hidden" name="id_card" value=<?= $card->ID ?>>
            <input type='submit' <?php if (!$card->DueDate == null) : ?> disabled="disabled" <?php endif; ?> value='add due date'>
            <input type='submit' <?php if ($card->DueDate == null) : ?> disabled="disabled" <?php endif; ?> value='modif due date'>
        </form> -->

    <!-- <form action="card/edit/<?= $card->ID ?>" method="POST">
            <input type="hidden" name="delete_due_date"  value=<?= $card->ID ?>>
            <input type='submit' <?php if ($card->DueDate == null) : ?> disabled="disabled" <?php endif; ?> value='delete date'>
        </form> -->

    <?php if ($participant != 0) : ?>
        <h4>Current participant(s)"</h4>
        <?php foreach ($participant as $user) : ?>
            <li>
                <?= $user->fullName; ?>(<?= $user->mail; ?>)
                <form action="card/delete_participant/<?= $card->ID; ?>" method="post">
                    <input type="hidden" name="id_card_del_part" value=<?= $card->ID ?>>
                    <input type="hidden" name="id_part_del_part" value=<?= $user->id ?>>
                    <input type="submit" value='Delete Participant'>
                </form>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h4>This card has no participant yet.</h4>
    <?php endif; ?>

    <h2>Add a new participant</h2>

    <form id="form_scroll" action='card/add_participant/<?= $card->ID ?>' method="post">
        <select id="scroll_select" name="participants">
            <option selected disabled="true">Select participant</option>
            <?php if (!Card::is_participate($owner->id, $card->ID)) : ?>
                <option value=<?= $owner->id ?>><?= $owner->fullName ?> ( <?= $owner->mail ?> )</option>
            <?php endif; ?>

            <?php foreach ($collaborators as $user) : ?>
                <?php if (!Card::is_participate($user->id, $card->ID)) : ?>
                    <option value=<?= $user->id ?>><?= $user->fullName ?> ( <?= $user->mail ?> )</option>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (empty($collaborators)) : ?>
                <option disabled> -- No collaborators yet -- </option>
            <?php endif; ?>
        </select>
        <input id="select_button" type="submit" value="+">
    </form>



    <h2>Board</h2>
    <input type="text" name="Board" disabled value='<?= $board->Title ?>'>

    <h2>Column</h2>
    <input type="text" name="Column" disabled value='<?= $column->Title ?>'>

    <!-- <h2>Commentaires</h2>
        <?php $comments = Comment::get_comment_per_card($card->ID); ?>
        <?php foreach ($comments as $comment) : ?>
            <li>
                <?= $comment->Body; ?> by <?= ControllerCard::author_name($comment->Author) ?>
                <?php if (isset($comment->ModifiedAt)) : ?>
                    <?= ControllerCard::modifiedcom($comment->ID) ?>
                <?php else : ?>
                    <?= ControllerCard::diffDatecom($comment->ID) ?>
                <?php endif; ?>
                <?php if ($user->id == $comment->Author) : ?>
                    <form class='link' action='card/edit_coms' method='post'>
                        <input type="hidden" name="id_comment" value=<?= $comment->ID ?>>
                        <input type='submit' value='edit'>
                    </form>
                <?php endif; ?>
                <?php if ($user->id == $comment->Author || $user->id == $board->Owner) : ?>
                    <form class='link' action='card/edit/<?= $card->ID ?>' method='post'>
                        <input type="hidden" name="id_comment" value=<?= $comment->ID ?>>
                        <input type="hidden" name="delete_comment" value=<?= $card->ID ?>>
                        <input type='submit' value='delete'>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        <form action="card/edit/<?= $card->ID ?>" method="POST">
            <input type="text" name="add_comment" placeholder="Enter a comment" id="add_comment">
            <input type="submit" value="Add comment">
        </form>
        <?php if (count($errorscom) != 0) : ?>
            <div class='errors'>
                <p>Please correct the following error(s) :</p>
                <ul>
                <?php foreach ($errorscom as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?> -->

    

    <?php if (count($errors) != 0) : ?>
        <div class='errors'>
            <p>Please correct the following error(s) :</p>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (strlen($success) != 0) : ?>
        <p><span class='success'><?= $success ?></span></p>
    <?php endif; ?>

</body>

</html>
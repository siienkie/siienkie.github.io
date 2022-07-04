<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $web_root ?>" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <title>View card</title>
</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">
    <div class="user_menu">
        <a>Card "<?= $card->Title ?>"</a>
        <a href="board/index">Boards</a>
        <?php if (Member::is_admin_members($user->id)) : ?>
            <a href="member/index">Manage users</a>
        <?php endif; ?>
        <a id="idle" href="board/calendar">Calendar</a>
        <?php if (Member::is_admin_members($user->id)) : ?>🤴<?php else : ?>🧑<?php endif; ?>
        <label><?= $user->fullName ?></label>
        <a href='main/logout'>⛔</a>
    </div>
</header>

<body>

    <div class="title">
        <a id="title">Card "<?= $card->Title; ?>"</a>
        <a href='card/edit/<?= $card->ID ?>'><img id="icon_edit" src="images/bouton_edit.png"></a>
        <a href='card/delete_card/<?= $card->ID ?>'><img id="icon_delete" src="images/bouton_delete.png"></a>
    </div>

    <br>

    <?php echo "Created " . ControllerCard::diffDate() . " by " . '<a id="lien">' . $author->fullName . '</a>' . '.' . ControllerCard::modified($card->ModifiedAt) . '.'; ?></br>
    This card is on the board "<a id="lien" href="board/board/<?= $board->ID ?>"><?= $board->Title ?></a>", column "<a id="lien"><?= $column->Title ?></a>", Position <?= $card->Position ?>

    <h2>Body</h2>
    <textarea disabled="disabled" name="Body" rows="2" cols="250"><?= $card->Body; ?>
            </textarea>


    <?php if ($card->DueDate != NULL) : ?>
        <h2>Due Date</h2>
        <input type="date" value=<?= $card->DueDate ?> disabled>
    <?php else : ?>
        <h4>This card has no due date yet.</h4>
    <?php endif; ?>

    <?php if ($participant != 0) : ?>
        <h2>Current participants</h2>

        <?php foreach ($participant as $user) : ?>
            <li>
                <?= $user->fullName; ?>(<?= $user->mail; ?>)
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <h4>This card has no participant yet.</h4>
    <?php endif; ?>
    <h2>Commentaires</h2>
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
                <a href='comment/edit/<?= $comment->ID ?>'><img id="icon_edit" src="images/bouton_edit.png"></a>
            <?php endif; ?>
            <?php if ($user->id == $comment->Author || $user->id == $board->Owner) : ?>
                <a href="card/delete_com/<?= $comment->ID ?>"><img id="icon_delete" src="images/bouton_delete.png"></a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    <form action="card/view/<?= $card->ID ?>" method="POST">
        <input type="text" name="add_comment" placeholder="Enter a comment" id="add_comment">
        <input type="hidden" name="id_card" value=<?= $card->ID ?>>
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
    <?php endif; ?>
</body>

</html>
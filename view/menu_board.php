<div class="menu">

    <a class="trello" href="board/index">Trello </a>
    <div class="user_menu">
        <a href="board/board/<?= $board->ID ?>">Board : <?= $board->Title ?></a>
        <label><?= $user->fullName ?> 👨‍💼 </label>

        <?php $val = Member::is_collaborate($user->id, $board->ID) ?>
        <?php $admin = ControllerBoard::is_admin($user->id) ?>
        <?php if ($val == false || $admin == true) : ?>
            <a href="board/collaborators/<?= $id_board ?>"> Manage Collaborate </a>

            <a href="board/calendar">Calendar</a>
        <?php endif; ?>

        <a href='main/logout'> ⛔</a>

    </div>
</div>
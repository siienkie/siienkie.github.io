<div class="menu">
    <label>Card <?= $card->Title ?></label>
    <a href="board/index">Boards </a>
    <label><?php $member = Member::get_member_by_id($card->Author) ?><?= $member->fullName ?></label>
    <a href='main/logout'> ⛔</a>
</div>
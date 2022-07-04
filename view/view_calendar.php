<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8' />
  <base href="<?= $web_root ?>" />

  <link href='lib/fullcalendar/main.css' rel='stylesheet' />
  <script src='lib/fullcalendar/main.js'></script>
  <script src='lib/fullcalendar/locales-all.js'></script>
  <link rel="stylesheet" href="css/styles.css" />

  <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
  <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
  <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />

  <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
  <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
  <script src="lib/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>

  <script>
    const idUser = <?= "$user->id" ?>;
  </script>
  <script src="js/calendar.js"></script>



</head>

<header>
  <img id="img_trello" src="images/Illustration_sans_titre.png">

  <div class="user_menu">
    <a id="selected">Calendar</a>
    <a id="idle" href="board/index">Boards</a>
    <?php if (Member::is_admin_members($user->id)) : ?>
      <a id="idle" href="member/index">Manage users</a>
    <?php endif; ?>
    <?php if (Member::is_admin_members($user->id)) : ?>🤴<?php else : ?>🧑<?php endif; ?>
    <label id="idle"><?= $user->fullName ?></label>
    <a href='main/logout'>⛔</a>
  </div>
</header>

<body>
  <br>
  <form class='list_boards' method="POST">

  </form>
  <div id='calendar'></div>

  <div id="popup"></div>


  <div id="info_card" title="Information des cartes" hidden>
    <p>Card 1 etc etc</p>
  </div>
</body>



</html>
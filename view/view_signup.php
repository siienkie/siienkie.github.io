<!DOCTYPE html>
<html>

<head>
    <base href="<?= $web_root ?>" />
    <meta charset="UTF-8">
    <title>Sign Up</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css" type="text/css" />
    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>
    <script src="js/signup-form.js"></script>
</head>
<header>
    <img id="img_trello" src="images/Illustration_sans_titre.png">

</header>

<body>
    <div id="container">
        <form action="main/signup" id="window" method="POST">
            <a href="main"><img id="bouton_retour" src="images/bouton_retour.png"></a>
            <h1>Sign up</h1>

            <label><b>Mail</b></label>
            <input type="text" placeholder="Enter mail" name="mail" id="mail" value="<?= $mail ?>" required>

            <label><b>Full Name</b></label>
            <input type="text" placeholder="Enter full name" name="fullName" id="fullName" value="<?= $fullname ?>" required>

            <label><b>Password</b></label>
            <input type="password" placeholder="Enter password" name="password" id="password" value="<?= $password ?>" required>


            <label><b>Confirm your password</b></label>
            <input type="password" placeholder="Confirm password" name="confirmPassword" id="confirmPassword" value="<?= $password ?>" required>

            <input type="submit" id='bouton' value='Sign Up'>
        </form>

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


    </div>

</body>

</html>
<?php
require_once 'framework/View.php';
require_once 'model/Member.php';
require_once 'model/Board.php';
require_once 'framework/Controller.php';


class ControllerMain extends Controller
{

    public function index()
    {
        if ($this->user_logged()) {
            $this->redirect("board", "index");
        } else {
            (new View("index"))->show();
        }
    }

    public function login()
    {
        $mail = '';
        $password = '';
        $errors = [];

        if (isset($_POST['mail']) && isset($_POST['password'])) {
            $mail = $_POST['mail'];
            $password = $_POST['password'];

            $errors = Member::validate_login($mail, $password);
            if (empty($errors)) {
                $this->log_user(Member::get_member_by_mail($mail));
            }
        }

        (new View("login"))->show(array("mail" => $mail, "password" => $password, "errors" => $errors));
    }

    public function signup()
    {
        $mail = '';
        $fullname = '';
        $password = '';
        $password_confirm = '';
        $errors = [];

        if (isset($_POST['mail']) && isset($_POST['fullName']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
            $mail = trim($_POST['mail']);
            $fullname = trim($_POST['fullName']);
            $password = trim($_POST['password']);
            $password_confirm = trim($_POST['confirmPassword']);

            $member = new Member($mail, Tools::my_hash($password), $fullname);
            $errors += Member::validate_unicity($mail);
            $errors += Member::validate_mail($mail);
            $errors += Member::validate_fullname($fullname);
            $errors += array_merge($errors, $member->validate());
            $errors += array_merge($errors, Member::validate_passwords($password, $password_confirm));
            if (count($errors) == 0) {
                $member->update(); //sauve l'utilisateur
                $this->redirect("main", "login");
            }
        }

        (new View("signup"))->show(array(
            "mail" => $mail, "fullname" => $fullname, "password" => $password,
            "password_confirm" => $password_confirm, "errors" => $errors
        ));
    }

    public function logout()
    {

        session_unset();
        (new View("index"))->show();
    }
}

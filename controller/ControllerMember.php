<?php

require_once 'model/Member.php';
require_once 'model/Board.php';
require_once 'controller/ControllerBoard.php';

class ControllerMember extends Controller
{
    public function index()
    {
        $user=$this->get_user_or_redirect();
        $users = Member::get_members();
        $logged = $this->get_user_or_redirect();
        $errors = [];

        if (isset($_POST['delete_user'])) {
            $this->delete_user();
            $this->redirect("member", "index");
        }

        if (isset($_POST['mail_user']) && isset($_POST['name_user']) && isset($_POST['password_user'])) {
            $mail = trim($_POST['mail_user']);
            $fullname = trim($_POST['name_user']);
            $password = trim($_POST['password_user']);

            $member = new Member($mail, Tools::my_hash($password), $fullname);
            $errors += Member::validate_unicity($mail);
            $errors += Member::validate_mail($mail);
            $errors += Member::validate_fullname($fullname);
            $errors += array_merge($errors, $member->validate());

            if (count($errors) == 0) {
                $member->update(); //sauve l'utilisateur
                $this->redirect("member", "index");
            }
        }

        (new View("user"))->show(array("users" => $users, "logged" => $logged,"user"=>$user));
    }

    public function edit()
    {
        $user = Member::get_member_by_id($_GET['param1']);
        $logged = $this->get_user_or_redirect();
        $errors = [];
        $success = "";
        $date = date('y-m-j h:i:s');

        if (isset($_POST['name_user']) && $_POST['name_user'] != $user->fullName) {
            $errors = $this->edit_name($_POST['id_user']);
        }

        if (isset($_POST['mail_user']) && $_POST['mail_user'] != $user->mail) {
            $errors = $this->edit_mail($_POST['id_user']);
        }

        if (isset($_POST['password_user']) && $_POST['password_user'] != "") {
            $errors = $this->edit_password($_POST['id_user']);
        }

        if (isset($_POST['role_user']) && $_POST['role_user'] != $user->role) {
            $user = Member::get_member_by_id($_POST['id_user']);
            $role = $_POST['role_user'];
            $user->role = $role;
            $user->edit_role($role, $user->id);
        }


        (new View("edit_user"))->show(array("logged" => $logged, "user" => $user, "errors" => $errors));
    }

    public function edit_name($user)
    {
        if ($this->user_logged()) {



            $errors = [];
            if (isset($_POST['name_user'])) {
                $user = Member::get_member_by_id($user);
                $name = trim($_POST['name_user']);
                $errors = $user->validate_fullname($name);
                if (empty($errors)) {
                    $user->edit_name($name, $user->id);
                    $this->redirect("member", "index");
                }
                return $errors;
            }
        };
    }

    public function edit_mail($user)
    {
        $errors = [];
        if (isset($_POST['mail_user'])) {
            $user = Member::get_member_by_id($user);
            $mail = trim($_POST['mail_user']);
            $errors = $user->validate_mail($mail);
            if (empty($errors)) {
                $user->edit_mail($mail, $user->id);
                $this->redirect("member", "index");
            }
            return $errors;
        }
    }

    public function edit_password($user)
    {
        $errors = [];
        if (isset($_POST['password_user'])) {
            $user = Member::get_member_by_id($user);
            $password = trim($_POST['password_user']);
            $password = Tools::my_hash($password);
            $errors = $user->validate_password($password);
            if (empty($errors)) {
                $user->edit_password($password, $user->id);
                $this->redirect("member", "index");
            }
            return $errors;
        }
    }

    public function delete_user_view()
    {
        $id_user = $_GET['param1'];
        $user = Member::get_member_by_id($id_user);
        (new View("delete_user"))->show(array("user" => $user));
    }

    public static function delete_user()
    {
        $id_user = $_POST['id_user'];
        $user = Member::get_member_by_id($id_user);
        $boards = Board::get_boards_by_owner($id_user);
        Member::delete_participation($id_user);
        Board::delete_collaboration($id_user);
        if ($boards != null) {
            foreach ($boards as $board) {
                $id_board = $board->ID;

                ControllerBoard::delete_board_($id_board);
            }
        }

        $user->delete($id_user);
    }


    public function add_user()
    {
        $user=$this->get_user_or_redirect();
        $mail = '';
        $fullname = '';
        $password = '';
        $errors = [];

        if (isset($_POST['mail_user']) && isset($_POST['name_user']) && isset($_POST['password_user'])) {
            $mail = trim($_POST['mail_user']);
            $fullname = trim($_POST['name_user']);
            $password = trim($_POST['password_user']);

            $member = new Member($mail, Tools::my_hash($password), $fullname);
            $errors += Member::validate_unicity($mail);
            $errors += Member::validate_mail($mail);
            $errors += Member::validate_fullname($fullname);
            $errors += Member::validate_password($password);
            $errors += array_merge($errors, $member->validate());

            if (count($errors) == 0) {
                $member->update(); //sauve l'utilisateur
                $this->redirect("member", "index");
            }
        }

        (new View("add_user"))->show(array("user"=>$user,"mail" => $mail, "fullname" => $fullname, "password" => $password, "errors" => $errors));
    }

    public static function diffDate()
    {

        $id_user = $_GET['param1'];
        $user = Member::get_member_by_id($id_user);

        $CreatedAt = $user->registered;

        $timestamp = strtotime($CreatedAt);
        $time = time() - $timestamp;

        $seconde = floor($time);
        $minute = floor($seconde / 60);
        $heure = floor($minute / 60);
        $jour = floor($heure / 24);
        $mois = floor($jour / 31);
        $annee = floor($jour / 365.25);



        //echo $annee . ' ans / ' . $mois . ' mois / ' . $jour . ' jours / ' . $heure . ' heures / ' . $minute . ' minutes / ' . $seconde . ' secondes <br />';

        if ($minute == 0) {
            $result = $seconde . " second ago";
        } elseif ($heure == 0) {
            $result = $minute . " minutes ago";
        } elseif ($jour == 0) {
            $result = $heure . " hours ago";
        } elseif ($mois == 0) {
            $result = $jour . " days ago";
        } elseif ($annee == 0) {
            $result = $mois . " months ago";
        } elseif ($annee != 0) {
            $result = $annee . " years ago";
        }


        return $result;
    }

    public static function modified($modified)
    {
        if ($modified == null) {
            $result = " Never modified";
        } else {
            $result = " Last Modified: " . self::diffDate($modified);
        }

        return $result;
    }


    public function mail_validate()
    {
        $res = "true";
        if (isset($_POST["mail"]) && $_POST["mail"] !== "") {
            $member = Member::get_member_by_mail($_POST["mail"]);
            if ($member) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function mail_validate_exist()
    {
        $res = "true";
        if (isset($_POST["mail"]) && $_POST["mail"] !== "") {
            $member = Member::get_member_by_mail($_POST["mail"]);
            if (!$member) {
                $res = "false";
            }
        }
        echo $res;
    }

    public static function diffDateUser($id_user)
    {

        $user = Member::get_member_by_id($id_user);

        $CreatedAt = $user->registered;

        $timestamp = strtotime($CreatedAt);
        $time = time() - $timestamp;

        $seconde = floor($time);
        $minute = floor($seconde / 60);
        $heure = floor($minute / 60);
        $jour = floor($heure / 24);
        $mois = floor($jour / 31);
        $annee = floor($jour / 365.25);



        //echo $annee . ' ans / ' . $mois . ' mois / ' . $jour . ' jours / ' . $heure . ' heures / ' . $minute . ' minutes / ' . $seconde . ' secondes <br />';

        if ($minute == 0) {
            $result = $seconde . " second ago";
        } elseif ($heure == 0) {
            $result = $minute . " minutes ago";
        } elseif ($jour == 0) {
            $result = $heure . " hours ago";
        } elseif ($mois == 0) {
            $result = $jour . " days ago";
        } elseif ($annee == 0) {
            $result = $mois . " months ago";
        } elseif ($annee != 0) {
            $result = $annee . " years ago";
        }


        return $result;
    }
    public static function is_admin($user)
    {
        $admins = Member::admin_members();
        return in_array($user, $admins, true);
    }
}

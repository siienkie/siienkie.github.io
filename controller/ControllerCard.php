<?php

require_once 'model/Card.php';
require_once 'model/Board.php';
require_once 'model/Column.php';
require_once 'model/Member.php';
require_once 'model/Profile.php';
require_once 'model/Comment.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/Participate.php';
require_once 'model/Collaborate.php';


class ControllerCard extends Controller
{

    //const UPLOAD_ERR_OK = 0;

    public function edit()
    {
        $user = $this->get_user_or_redirect();
        $card = Card::get_card($_GET['param1']);
        $id_card = $card->ID;
        $column = Column::get_column($card->Column);
        $id_board = $column->Board;
        $board = Board::get_board_by_id($column->Board);
        $author = Member::get_member_by_id($card->Author);
        $errors = [];
        $errorscom = [];
        $success = "";
        $date = date('y-m-j h:i:s');
        $participant = Participate::get_participant($card->ID);

        $collaborators = Collaborate::get_collaborate_by_id_board($id_board);
        $owner = Member::get_member_by_owner_board($board->Owner);
        $member_not_participant = [];
        $date_without_time = Card::get_date_without_time($id_card);
        if (isset($_POST['add_comment'])) {
            $errorscom = $this->add_comment($user, $id_card);
            Card::add_modified($date, 1);
            //$modified_by = $this->get_modifiedBy();
        }
        if (isset($_POST['Title_card']) && $_POST['Title_card'] != $card->Title) {
            $errors = $this->edit_title($id_card);
        }

        if (isset($_POST['Body']) && $_POST['Body'] != $card->Body) {
            $card = Card::get_card($id_card);
            $body = $_POST['Body'];
            $card->Body = $body;
            $card->edit_body($body, $card->ID);
        }
        if (isset($_POST['delete_comment'])) {
            $this->delete_com();
            //$this->redirect("card","edit",$card->ID);
        }

        if (isset($_POST['id_card_del_part'])) {
            $this->delete_participant();
            //$this->redirect("card","edit",$card->ID);
        }

        // if(isset($_POST['delete_due_card'])){
        //     $this->delete_due_date();
        //     $this->redirect("card","edit",$card->ID);
        // }

        if (isset($_POST['delete_due_date'])) {
            $id_card = $_POST['delete_due_date'];
            $this->delete_due_date($id_card);
            $this->redirect("card", "edit", $card->ID);
        }


        if (isset($_POST['due_date'])) {
            $id_card = $_GET['param1'];
            $errors = $this->add_date($id_card);
        }

        if (isset($_POST['participants'])) {
            $errors = $this->add_participant();
        }

        (new View("edit"))->show(array(
            "user" => $user, "card" => $card, "board" => $board, "column" => $column, "author" => $author, "errors" => $errors,
            "errorscom" => $errorscom, "success" => $success, "participant" => $participant, "member_not_participant" => $member_not_participant,
            "date_without_time" => $date_without_time, "owner" => $owner, "id_board" => $id_board, "collaborators" => $collaborators
        ));
    }

    public function add_date($card)
    {
        $errors = [];
        if (isset($_POST['due_date']) && $_POST['due_date'] != "") {
            $date = trim($_POST['due_date']);
            $card = Card::get_card($card);
            $column = Column::get_column($card->Column);
            $errors = Card::validate_date($date);
            if (empty($errors)) {
                Card::add_due_date($date, $card->ID);
                $this->redirect("board", "board", $column->Board);
            }
        }
        return $errors;
    }

    public function index()
    {
        $this->view();
    }

    public function card()
    {
        $card = Card::get_card($_POST['id_card']);
        $date = date('y-m-j h:i:s');
        $board = Board::get_board_by_id($card->Board);
        $errors = [];
    }

    public static function get_modifiedBy()
    {
        $id_card = $_GET['param1'];
        $card = Card::get_card($id_card);

        if ($card->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $card->ModifiedAt;
        }
    }

    public static function get_modified($id_card)
    {
        $card = Card::get_card($id_card);

        if ($card->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $card->ModifiedAt;
        }
    }



    public function view()
    {
        $user = $this->get_user_or_false();
        $card = Card::get_card($_GET['param1']);
        $column = Column::get_column($card->Column);
        $board = Board::get_board_by_id($column->Board);
        $author = Member::get_member_by_id($card->Author);
        $comments = Comment::get_comment_card($card->ID);
        $participant = Participate::get_participant($card->ID);
        $date = date('y-m-j h:i:s');
        $errorscom = [];
        $errors = [];
        if (isset($_POST['add_comment']) && $_POST['add_comment'] != "") {
            $errorscom = $this->add_comment($user, $_GET['param1']);
            Card::add_modified($date, 1);
            //$modified_by = $this->get_modifiedBy();
        }
        if (isset($_POST['delete_comment'])) {

            $this->delete_com();
            //$this->redirect("card","view_card",$card->ID);
        }


        (new View("card"))->show(array("user" => $user, "card" => $card, "column" => $column, "board" => $board, "author" => $author, "comments" => $comments, "participant" => $participant, "errorscom" => $errorscom, "errors" => $errors));
    }

    public function edit_title($card)
    {
        $errors = [];
        $board = $_POST['id_board'];
        if (isset($_POST['Title_card'])) {
            $title = trim($_POST['Title_card']);
            $card = Card::get_card($card);
            $card->Title = $title;
            $errors += Board::validate_title_caractere($title);

            $errors += Card::validate_title($title, $board);
            if (empty($errors)) {
                $card->edit_title($title, $card->ID);
                $this->redirect("board", "board", $board);
            }
        }
        return $errors;
    }


    public function delete_card()
    {
        $user= $this->get_user_or_redirect();
        $id_card = $_GET['param1'];
        $card = Card::get_card($id_card);
        $column = Column::get_column($card->Column);
        $board = $column->Board;
        (new View("delete_card"))->show(array("user"=>$user,"card" => $card, "board" => $board));
    }

    public function add_comment($user, $card)
    {
        $errors = [];
        if (isset($_POST['add_comment']) && $_POST['add_comment'] != "") {
            $comment = trim($_POST['add_comment']);
            $comment = new Comment($comment, $card, $user->fullName);
            $errors = $comment->validate();
            if (empty($errors)) {
                $comment->update($user);
                $this->redirect("card", "view", $card);
            }
        }
        return $errors;
    }

    public static function author_name($author)
    {
        $aut = Member::get_member_by_id($author);
        $result = $aut->fullName;
        return $result;
    }

    public function delete_com()
    {
        $post_id = $_GET['param1'];
        $comment = Comment::get_comment($post_id);
        if (isset($_GET['param1']) && $_GET['param1'] != "") {
            $comment->delete($comment->ID);
            $this->redirect("card", "view", $comment->Card);
        }
    }

    public function delete_participant()
    {
        $id_card = $_POST['id_card_del_part'];
        $id_part = $_POST['id_part_del_part'];


        Participate::delete($id_part, $id_card);

        $this->redirect("card", "edit", $id_card);
    }

    public function delete_due_date($id_card)
    {
        Card::delete_due_date($id_card);
        $this->redirect("card", "edit", $id_card);
    }


    public static function diffDate()
    {

        $id_card = $_GET['param1'];
        $card = Card::get_card($id_card);


        $CreatedAt = $card->CreatedAt;

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
            $result = $heure . " heures ago";
        } elseif ($mois == 0) {
            $result = $jour . " jours ago";
        } elseif ($annee == 0) {
            $result = $mois . " mois ago";
        } elseif ($annee != 0) {
            $result = $annee . " annee ago";
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

    public static function diffDatecom($id_com)
    {


        $com = Comment::get_comment($id_com);

        $CreatedAt = $com->CreatedAt;

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

    public static function modifiedcom($modified)
    {
        if ($modified == null) {
            $result = " Never modified";
        } else {
            $result = " Last Modified: " . self::diffDatecom($modified);
        }

        return $result;
    }

    public function add_participant()
    {
        $id_card = $_GET['param1'];
        $errors = [];
        if (isset($_POST['participants']) && $_POST['participants'] != "") {
            $add_participant = trim($_POST['participants']);
            Board::add_participant($add_participant, $id_card);
            $this->redirect("card", "edit", $id_card);
        } else {
            $this->redirect("card", "edit", $id_card);
        }
        return $errors;
    }

    public static function is_admin($user)
    {
        $admins = Member::admin_members();
        return in_array($user, $admins, true);
    }
}

<?php

require_once 'model/Card.php';
require_once 'model/Board.php';
require_once 'model/Column.php';
require_once 'model/Member.php';
require_once 'model/Profile.php';
require_once 'model/Comment.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerComment extends Controller
{

    //const UPLOAD_ERR_OK = 0;

    public function edit()
    {
        $user = $this->get_user_or_redirect();
        $errors = [];
        $comment = Comment::get_comment($_GET['param1']);
        if (isset($_POST['Comment']) && $_POST['Comment'] != "" && $_POST['Comment'] != $comment->Body) {
            $body = trim($_POST['Comment']);
            $comment->Body = $body;
            $errors += Board::validate_title_caractere($body);
            if (empty($errors)) {
                $comment->edit_comment($body, $comment->ID);
                $this->redirect("card", "view", $comment->Card);
            }
        }

        (new View("edit_comment"))->show(array("user" => $user, "comment" => $comment, "errors" => $errors));
    }

    public function index()
    {
        $this->comment();
    }

    public function comment()
    {

        $comment = Comment::get_comment($_POST['id_comment']);
        if (isset($_POST['Comment'])) {
            $this->edit_com();
        }
    }


    public function edit_com()
    {
        $comment = Comment::get_comment($_POST['id_comment']);
        $body = trim($_POST['Comment']);
        $comment->Body = $body;
        $comment->edit_comment($body, $comment->ID);
        $success = "Comment edited successfully";
        return $comment;
    }


    public static function get_modifiedBy()
    {
        $id_com = $_GET['param1'];
        $com = Comment::get_comment($id_com);

        if ($com->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $com->ModifiedAt;
        }
    }

    public static function get_modified($id_com)
    {
        $com = Comment::get_comment($id_com);

        if ($com->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $com->ModifiedAt;
        }
    }
    public static function is_admin($user)
    {
        $admins = Member::admin_members();
        return in_array($user, $admins, true);
    }
}

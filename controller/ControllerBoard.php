<?php


require_once 'model/Board.php';
require_once 'model/Column.php';
require_once 'model/Member.php';
require_once 'model/Profile.php';
require_once 'model/Comment.php';
require_once 'model/Participate.php';
require_once 'model/Collaborate.php';



class ControllerBoard extends Controller
{

    public function index()
    {


        if (!$this->user_logged()) {
            $this->redirect();
        } else {
            $user = $this->get_user_or_redirect();
            $my_board = Profile::get_title_board($user);
            $others = Profile::other_board($user);
            $boards = Board::get_boards();
            $errors = [];

            if (isset($_POST['add_board'])) {
                $errors = $this->add_board($user);
            }

            if (isset($_POST['delete_board'])) {
                //$board = $_POST['id_board'];
                $this->delete_board($_GET['param1']);
                $this->redirect("board", "index");
            }



            (new View("board"))->show(array("user" => $user, "my_board" => $my_board, "errors" => $errors, "others" => $others, "boards" => $boards));
        }
    }

    public static function column_name_validate_exist()
    {
        $res = "true";
        if (isset($_POST["add_column"]) && $_POST["add_column"] !== "") {

            $column = Column::get_column_by_Title_and_ID_board($_POST['add_column'], $_POST['id_board']);
            if ($column) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function board_name_validate_exist()
    {

        if (isset($_POST["title_board"]) && $_POST["title_board"] !== "") {
            $board = Board::get_board_by_Title($_POST['title_board']);
            if ($board) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }

    public static function card_name_validate_exist()
    {



        if (isset($_POST["add_card"]) && $_POST["add_card"] !== "") {
            $card = Card::get_card_by_Title_and_ID_board($_POST['add_card'], $_POST['id_board']);
            if ($card) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }



    // public static function is_collaborate($collaborate,$id){
    //     $boards = Member::is_collaborate($collaborate);
    //     $is_it = in_array($id,$boards,true);
    //    return $is_it;

    // }

    public static function is_participant($participant, $id_board)
    {
        $boards = Member::is_participant($participant);
        $is_it = in_array($id_board, $boards, true);
        return $is_it;
    }

    public function add_board($user)
    {
        $errors = [];
        if (isset($_POST['add_board']) && $_POST['add_board'] != "") {
            $title = trim($_POST['add_board']);
            $errors += Board::validate_title_caractere($title);
            $errors += Board::validate_title($title);

            if (empty($errors)) {
                $board = new Board($title, $user);
                $board->update($user);
                $this->redirect("board", "index");
            }
        }
        return $errors;
    }


    public function board()
    {
        $user = $this->get_user_or_redirect();
        $id_board = $_GET['param1'];
        $cards = [];
        $errors = [];
        $date = date('y-m-j h:i:s');

        $columns = Column::get_columns_by_board($id_board);
        $board = Board::get_board_by_id($id_board);
        $owner = Member::get_member_by_owner_board($board->Owner);
        $modified_by = $this->get_modifiedBy();


        if (isset($_POST['add_card'])) {
            $errors = $this->add_card();
            Board::add_modified($date, $id_board);
            $modified_by = $this->get_modifiedBy();
        }

        if (isset($_POST['add_column'])) {
            $errors = $this->add_column($id_board);
            Board::add_modified($date, $id_board);
            $modified_by = $this->get_modifiedBy();
        }

        if (isset($_POST['edit_title_board'])) {
            $this->edit_board();
        }

        if (isset($_POST['delete_card'])) {
            $this->delete_card();
            $this->redirect("board", "board", $board->ID);
        }

        if (isset($_POST['delete_column'])) {
            $this->delete_column();
            $this->redirect("board", "board", $board->ID);
        }



        (new View("board_1"))->show(array(
            "user" => $user,
            "columns" => $columns,
            "cards" => $cards,
            "board" => $board,
            "owner" => $owner,
            "modified_by" => $modified_by,
            "errors" => $errors,
            "id_board" => $id_board
        ));
    }
    public static function has_cards($column)
    {
        if (Column::get_number_card($column) > 0)
            return true;
        return false;
    }

    // public static function delete_board($id_board2)
    // {
    //     Board::delete_allcollaborator($id_board2);

    //     $columns_to_delete = Column::get_column_by_ID_board($id_board2);
    //     if ($columns_to_delete != null) {
    //         foreach ($columns_to_delete as $column) {
    //             $id_column = $column->ID;
    //             self::delete_column2_for_board($id_column);
    //         }
    //     }


    //     Board::delete_board($id_board2);

    // }


    public static function delete_board()
    {
        $id_board = $_POST['id_board'];

        Board::delete_allcollaborator($id_board);

        $columns_to_delete = Column::get_column_by_ID_board($id_board);
        if ($columns_to_delete != null) {
            foreach ($columns_to_delete as $column) {
                $id_column = $column->ID;
                self::delete_column2_for_board($id_column);
            }
        }


        Board::delete_board($id_board);
    }

    public static function delete_board_($id_board)
    {

        Board::delete_allcollaborator($id_board);

        $columns_to_delete = Column::get_column_by_ID_board($id_board);
        if ($columns_to_delete != null) {
            foreach ($columns_to_delete as $column) {
                $id_column = $column->ID;
                self::delete_column2_for_board($id_column);
            }
        }


        Board::delete_board($id_board);
    }


    public function select_collaborators()
    {
        $id_board = $_GET['param1'];
        $user = $this->get_user_or_redirect();
        $board = Board::get_board_by_id($id_board);
        $owner = Member::get_member_by_owner_board($board->Owner);
        $collaborators = Board::get_collaborators($id_board);
    }

    public function delete_board_view()
    {
        $user= $this->get_user_or_redirect();
        (new View("delete_board"))->show(array("user"=>$user));
    }

    public function delete_colla_view()
    {  $id_colla=$_GET['param1'];
        $board = Collaborate::get_board_from_collaborate($id_colla);
        $user = $this->get_user_or_redirect();
        (new View("delete_colla"))->show(array("user"=>$user,"board"=>$board));
    }

    public static function delete_card()
    {
        $id_card = $_POST['id_card'];
        $card = Card::get_card($id_card);
        $id_column = $card->Column;

        $pos1 = $card->Position;

        $cards_to_change_pos = Card::get_cards_by_column($id_column);

        foreach ($cards_to_change_pos as $card_pos) {
            if ($card_pos->Position > $pos1) {
                $new_pos = $card_pos->Position - 1;
                $pos1++;
                Card::update_change_pos($new_pos, $pos1);
            }
        }

        $comments_to_delete = Comment::get_comment_per_card($_POST['id_card']);
        foreach ($comments_to_delete as $comment_del) {
            $id_comment = $comment_del->ID;
            $comment = Comment::get_comment($id_comment);
            $comment->delete($id_comment);
        }

        Participate::delete_all_card($id_card);
        $card->delete($id_card);
    }

    public function delete_column()
    {
        $id_column = $_POST['id_column'];
        $column = Column::get_column($id_column);
        $id_board = $column->Board;

        $pos1 = $column->Position;

        $columns_to_change_pos = Column::get_column_by_ID_board($id_board);
        foreach ($columns_to_change_pos as $column_pos) {
            if ($column_pos->Position > $pos1) {

                $new_pos = $column_pos->Position - 1;
                $pos1++;
                Column::update_change_pos($new_pos, $pos1);
            }
        }



        $cards_to_delete = Card::get_cards_by_column($column->ID);
        foreach ($cards_to_delete as $card_del) {
            $id_card = $card_del->ID;
            Card::delete_card_participate($card_del->ID);
            $comments_to_delete = Comment::get_comment_per_card($id_card);
            foreach ($comments_to_delete as $comment_del) {
                $id_comment = $comment_del->ID;
                $comment = Comment::get_comment($id_comment);
                $comment->delete($id_comment);
            }

            $card = Card::get_card($id_card);
            $card->delete($id_card);
        }


        $column->delete($id_column);

        $this->redirect("board", "board", $id_board);
    }


    public function delete_column_view()
    {
        $user=$this->get_user_or_redirect();
        $id_column = $_POST['id_column'];
        $column = Column::get_column($id_column);
        $id_board = $column->Board;

        if (self::has_cards($id_column)) (new View("delete_column"))->show(array("user"=>$user,"column" => $column));
        else {
            $this->delete_column();
            $this->redirect("board", "board", $id_board);
        }
    }



    public static function delete_column2_for_board($id_column)
    {
        $cards_to_delete = Card::get_cards_by_column($id_column);
        foreach ($cards_to_delete as $card_del) {
            $id_card = $card_del->ID;
            Card::delete_card_participate($card_del->ID);

            $comments_to_delete = Comment::get_comment_per_card($id_card);
            foreach ($comments_to_delete as $comment_del) {
                $id_comment = $comment_del->ID;
                $comment = Comment::get_comment($id_comment);
                $comment->delete($id_comment);
            }

            $card = Card::get_card($id_card);
            $card->delete($id_card);
        }

        //$id_column = $_POST['id_column'];
        $column = Column::get_column($id_column);
        $column->delete($id_column);

        //(new View("delete_column"))->show();
    }



    public function get_visible_columns_service()
    {
        //$user = $this->get_user_or_redirect();
        //$recipient = $this->get_recipient($user);
        $id_board = $_POST['id_board'];
        $messages_json = Column::get_column_by_ID_board($id_board);
        echo $messages_json;
    }

    public function delete_column_service()
    {
        $column = $this->delete_column();
        echo $column ? "true" : "false";
    }





    public function edit_board()
    {
        $user=$this->get_user_or_redirect();
        $id_board = $_GET['param1'];
        $board = Board::get_board_by_id($id_board);
        $before_title = $board->Title;

        $errors = [];

        if (isset($_POST['edit_title_board'])) {
            $name = trim($_POST['edit_title_board']);

            $errors += Board::validate_title_caractere($name);

            if ($name != $board->Title)
                $errors += Board::validate_title($name);

            if ($errors == []) {
                Board::edit_title_board($name, $id_board);
                $this->redirect("board", "board", $id_board);
            }
        }
        (new View("edit_board"))->show(array("user"=>$user,"board" => $board, "before_title" => $before_title, "errors" => $errors));
    }



    private function add_column($board)
    {
        $errors = [];
        if (isset($_POST['add_column']) && $_POST['add_column'] != "") {
            $title = trim($_POST['add_column']);
            $errors += Column::validate_title($title, $board);
            $errors += Board::validate_title_caractere($title);

            if (empty($errors)) {
                $column = new Column($title, $board);
                $column->update();
                $this->redirect("board", "board", $board);
            }
        }
        return $errors;
    }


    public static function get_modifiedBy()
    {
        $id_board = $_GET['param1'];
        $board = Board::get_board_by_id($id_board);

        if ($board->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $board->ModifiedAt;
        }
    }

    public static function get_modified($id_board)
    {
        $board = Board::get_board_by_id($id_board);

        if ($board->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $board->ModifiedAt;
        }
    }



    public function move_column_up()
    {
        $pos1 = $_POST["position"];

        $id_board = $_POST["id_board"];
        $up = $_POST['up_col'];



        if ($up !== null && $pos1 != 0) {
            $pos2 = $pos1 - 1;
            Column::move_column($pos1, $pos2, $id_board);
            $this->redirect("board", "board", $id_board);
        }
    }

    public function move_column_down()
    {
        $pos1 = $_POST["position"];
        $id_board = $_POST["id_board"];
        $down = $_POST['down_col'];
        $columns = Column::get_columns_by_board($id_board);
        if ($down !== null && $pos1 < count($columns)) {
            $pos2 = $pos1 + 1;
            Column::move_column($pos1, $pos2, $id_board);
            $this->redirect("board", "board", $id_board);
        }
    }

    public function add_card()
    {
        $column = Column::get_column($_POST['id_column']);

        $errors = [];
        if (isset($_POST['add_card']) && $_POST['add_card'] != "") {
            $title = trim($_POST['add_card']);

            $user = $this->get_user_or_redirect();

            $errors += Board::validate_title_caractere($title);

            $errors += Card::validate_title($title, $column->Board);

            if ($errors == []) {
                $card = new Card($column->ID, $title);
                $card->update($user);
                $this->redirect("board", "board", $column->Board);
            }
        }
        return $errors;
    }

    public function move_card_inside_up()
    {
        $pos1 = $_POST["position_up"];
        $id_column = $_POST["id_column"];
        $id_board = $_POST["id_board"];

        $column = Column::get_column($id_column);
        $cards = Card::get_cards_by_column($id_column);
        if ($pos1 != 0) {
            $pos2 = $pos1 - 1;
            Card::card_inside($pos1, $pos2, $id_column);
        }

        $this->redirect("board", "board", $id_board);
    }

    public function move_card_inside_down()
    {
        $pos1 = $_POST["position_down"];
        $id_column = $_POST["id_column"];
        $id_board = $_POST["id_board"];

        $column = Column::get_column($id_column);
        $cards = Card::get_cards_by_column($id_column);

        $last_card = count($cards) - 1;
        if ($pos1 < $last_card) {
            $pos2 = $pos1 + 1;
            Card::card_inside($pos1, $pos2, $id_column);
        }

        $this->redirect("board", "board", $id_board);
    }


    public function move_card_outside_left()
    {
        $id_card = $_POST["id_card2"];
        $pos_card = $_POST["position_before_left"];
        $id_board = $_POST["id_board"];
        $id_column = $_POST["id_column"];
        $columns = Column::get_columns_by_board($id_board);
        $pos1 = $_POST["position_col"];

        if ($pos1 != 0) {
            $pos2 = $pos1 - 1;
            Card::card_outside($pos2, $id_card, $id_board);
        }

        $cards_to_change_pos = Card::get_cards_by_column($id_column);

        foreach ($cards_to_change_pos as $card_pos) {
            if ($card_pos->Position > $pos_card) {

                $new_pos = $card_pos->Position - 1;

                $pos_card++;
                Card::update_change_pos_card($new_pos, $card_pos->ID);
            }
        }
        $this->redirect("board", "board", $id_board);
    }
    public function move_card_outside_right()
    {
        $id_card = $_POST["id_card2"];
        $pos_card = $_POST["position_before_right"];
        $id_board = $_POST["id_board"];
        $id_column = $_POST["id_column"];
        $columns = Column::get_columns_by_board($id_board);
        $last_col = count(($columns)) - 1;
        $pos1 = $_POST["position_col"];

        if ($pos1 < $last_col) {
            $pos2 = $pos1 + 1;
            Card::card_outside($pos2, $id_card, $id_board);
        }

        $cards_to_change_pos = Card::get_cards_by_column($id_column);
        foreach ($cards_to_change_pos as $card_pos) {
            if ($card_pos->Position > $pos_card) {

                $new_pos = $card_pos->Position - 1;

                $pos_card++;
                Card::update_change_pos_card($new_pos, $card_pos->ID);
            }
        }
        $this->redirect("board", "board", $id_board);
    }

    public function edit_column()
    {
        $user= $this->get_user_or_redirect();
        $column = Column::get_column($_GET['param1']);
        $board = Board::get_board_by_id($column->Board);
        $errors = [];
        if (isset($_POST['Title_column'])) {
            $title = trim($_POST['Title_column']);
            if ($_POST['Title_column'] != $column->Title)
                $errors += Column::validate_title($title, $board->ID);
            $errors += Board::validate_title_caractere($title);
            if (empty($errors)) {
                $column->edit_title($title, $column->ID);
                $this->redirect("board", "board", $board->ID);
            }
        }
        (new View("edit_column"))->show(array("user"=>$user,"column" => $column, "board" => $board, "errors" => $errors));
    }

    public static function diffDate()
    {

        $id_board = $_GET['param1'];
        $board = Board::get_board_by_id($id_board);


        $CreatedAt = $board->CreatedAt;

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
    public function collaborators()
    {
        $id_board = $_GET['param1'];
        $user = $this->get_user_or_redirect();
        $board = Board::get_board_by_id($id_board);
        $owner = Member::get_member_by_owner_board($board->Owner);
        $collaborators = [];
        $collaborators = Board::get_collaborators($id_board);

        $users = [];
        $users = Member::members(); //membre ( collaborateur ou non )



        (new View("manage"))->show(array(
            "user" => $user,
            "board" => $board,
            "owner" => $owner,
            "collaborators" => $collaborators,
            "users" => $users,
            "id_board" => $id_board
        ));
    }

    public function get_collaborator()
    {
        $id_board = $_GET['param1'];
        $collaborators = [];
        $collaborators = Board::get_collaborators($id_board);
        echo json_encode($collaborators);
    }
    public function get_potential()
    {
        $id_board = $_GET['param1'];
        $collaborators = [];
        $users = Member::get_members();
        foreach ($users as $user) {
            if (Member::is_collaborate($user->id, $id_board)) {
            } else {
                $collaborators[] = $user;
            }
        }
        echo json_encode($collaborators);
    }

    public function add_collaborate_ajax()
    {
        $id_collaborator = $_POST['collaborator'];
        $id_board = $_POST['id_board'];
        Board::add_collaborator($id_board, $id_collaborator);
    }

    public function update_position_column_ajax()
    {
        if (isset($_POST['set-postion']) && $_POST['set-position'] !== "" && isset($_POST['col-drag']) && $_POST['col-drag'] !== "") {
            $position = $_POST['set-postion'];
            $id = $_POST['col-drag'];
            Board::set_position_column($position, $id);
        }
    }
    public function update_position_card_ajax()
    {
        if (isset($_POST['card-drag']) && $_POST['card-drag'] !== "" && isset($_POST['set-card']) && $_POST['set-card'] !== "" && isset($_POST['col']) && $_POST['col'] !== "") {
            $position = $_POST['set-card'];
            $idcard = $_POST['card-drag'];
            $idcol = $_POST['col'];
            Board::set_position_card($position, $idcard, $idcol);
        }
    }
    public function delete_collaborate_ajax()
    {
        $id_board = $_POST['id_board'];
        $id_collaborator = $_POST['id_user'];
        Board::delete_collaborator($id_board, $id_collaborator);
    }

    public static function is_admin($user)
    {

        $admins = Member::admin_members();
        return in_array($user, $admins, true);
    }
    public function add_collaborate()
    {
        $id_board = $_POST['id_board'];
        if (isset($_POST['collaborators']) && $_POST['collaborators'] !== "") {
            $id_collaborator = trim($_POST['collaborators']);
            Board::add_collaborator($id_board, $id_collaborator);
            $this->redirect("board", "collaborators", $id_board);
        } else {
            $this->redirect("board", "collaborators", $id_board);
        }
    }

    public function delete_collaborate()
    {
        $id_board = $_GET['param1'];
        $id_collaborator = $_POST['id_user'];
        Board::delete_collaborator($id_board, $id_collaborator);
        $this->redirect("board", "collaborators", $id_board);
    }


    public function calendar()
    {
        $user = $this->get_user_or_redirect();

        (new View("calendar"))->show(array("user" => $user));
    }
    public function all_board_getting()
    {
        $user = $this->get_user_or_redirect();
        $boards = [];
        $boards = Board::get_all_board($user->id);
        echo json_encode($boards);
    }
    public function all_card_from_board()
    {
        $id_board = $_POST['id_board'];
        $cards = [];
        $cards = Card::get_card_by_ID_board($id_board);
        echo json_encode($cards);
    }
    public static function has_collab($user)
    {
        if (Member::get_number_collab($user) > 0)
            return true;
        return false;
    }
}

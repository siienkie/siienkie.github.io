<?php

require_once "framework/Model.php";
require_once "model/Member.php";

class Board extends Model
{
    public $Title;
    public $Owner;
    public $ID;
    public $CreatedAt;
    public $ModifiedAt;

    public function __construct($Title, $Owner, $ID = null, $CreatedAt = null, $ModifiedAt = null)
    {
        $this->Title = $Title;
        $this->Owner = $Owner;
        $this->ID = $ID;
        $this->CreatedAt = $CreatedAt;
        $this->ModifiedAt = $ModifiedAt;
    }

    public static function get_id_board($owner)
    {
        $query = self::execute("SELECT * from board where owner = :owner", array("owner" => $owner));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Board($data["Title"], $data["Owner"], $data["ID"], $data["CreatedAt"], $data["ModifiedAt"]);
        }
    }

    public static function get_board_by_Title($title)
    {
        $query = self::execute("SELECT * from board where title = :title", array("title" => $title));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Board($data["Title"], $data["Owner"], $data["ID"], $data["CreatedAt"], $data["ModifiedAt"]);
        }
    }

    public static function get_board_by_owner($owner)
    {
        $query = self::execute("SELECT * from board where owner = :owner", array("owner" => $owner));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Board($data["Title"], $data["Owner"], $data["ID"], $data["CreatedAt"], $data["ModifiedAt"]);
        }
    }

    public static function get_board_by_id($id_board)
    {
        $query = self::execute("SELECT * from board where ID = :id_board", array("id_board" => $id_board));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Board($data["Title"], $data["Owner"], $data["ID"], $data["CreatedAt"], $data["ModifiedAt"]);
        }
    }



    public static function get_date_modified($id_board)
    {

        $board = Board::get_board_by_id($id_board);
        if ($board->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $board->ModifiedAt;
        }
    }

    public static function get_boards()
    {
        $query = self::execute("SELECT * from Board ORDER BY Title", array());
        $data = $query->fetchAll();
        $board = [];

        foreach ($data as $row) {
            $board[] = new Board($row["Title"], $row["Owner"], $row["ID"], $row["CreatedAt"], $row["ModifiedAt"]);
        }

        return $board;
    }

    public static function get_boards_by_owner($ID)
    {
        $query = self::execute("SELECT * FROM Board where Owner=:id", array("id" => $ID));
        $data = $query->fetchAll();
        $boards = [];

        foreach ($data as $row) {
            $boards[] = new Board($row["Title"], $row["Owner"], $row["ID"], $row["CreatedAt"], $row["ModifiedAt"]);
        }


        return $boards;
    }
    public static function get_collaborators($id_board)
    {
        $query = self::execute("SELECT user.Mail,user.Password, user.FullName,user.Role ,user.ID, user.RegisteredAt FROM `user`JOIN collaborate ON user.ID = collaborate.Collaborator WHERE board=:id_board", array("id_board" => $id_board));
        $data = $query->fetchAll();
        $collaborators = [];

        foreach ($data as $row) {
            $collaborators[] = new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }

        return $collaborators;
    }


    public static function validate_title_caractere($title)
    {
        $errors = [];
        if (strlen($title) < 3) {

            $errors[] = "Title lenght must minimum 3 caractere";
        }
        return $errors;
    }



    public static function validate_title($title)
    {
        $errors = [];
        $boards = self::get_board_by_Title($title);

        if ($boards) {
            $errors[] = "Ce titre existe deja";
        }

        return $errors;
    }

    public static function validate_title_js($title)
    {
        $errors = [];
        $boards = self::get_board_by_Title($title);

        if ($boards) {
            $errors[] = "Ce titre existe deja";
        }

        return $errors;
    }

    // public static function validate_unicity($mail)
    // {
    //     $errors = [];
    //     $member = self::get_member_by_mail($mail);
    //     if ($member) {
    //         $errors[] = "This user already exists.";
    //     }
    //     return $errors;

    // }

    public static function add_modified($date, $id_board)
    {
        return self::execute("UPDATE board SET ModifiedAt = :date WHERE ID=:id_board", array("date" => $date, "id_board" => $id_board));
        $board = Board::get_id_board($id_board);
        if ($board->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $board->ModifiedAt;
        }
    }


    public static function add_modified_column($date, $id_board, $new_name)
    {
        if ($new_name != "") {
            self::execute("UPDATE board SET ModifiedAt = :date WHERE ID=:id_board", array("date" => $date, "id_board" => $id_board));
            header("Refresh:0");
        }
    }

    public static function edit_title_board($name, $id_board)
    {
        self::execute("UPDATE board SET Title = :name , ModifiedAt=NOW() WHERE ID=:id_board", array("name" => $name, "id_board" => $id_board));
    }

    // public static function update($title,$id_board){
    //     self::execute("UPDATE `board` SET Title= :title , ModifiedAt=NOW() WHERE ID= :id", array("title"=>$title,"id"=>$id_board));
    // }

    public function update($user)
    {

        if ($this->ID == null) {
            self::execute('INSERT INTO board (Title,Owner) VALUES (:title,:owner)', array('title' => $this->Title, 'owner' => $user->id));
            $board = self::get_board_by_id(self::lastInsertId());
            $this->ID = $board->ID;
            $this->CreatedAt = $board->CreatedAt;

            return $this;
        } else {
            throw new Exception("Erreur implementation");
        }
    }

    public static function add_board($title, $user)
    {
        $fullname = $user->fullName;
        $member = Member::get_member_by_fullname($fullname);

        self::execute(
            "INSERT INTO board (title,owner) VALUES (:title,:owner)",
            array("title" => $title, "owner" => $member->id)
        );
    }

    // public static function get_collaborators($id_board){
    //     $query = self::execute("SELECT user.Mail from collaborate,board,user WHERE Board.ID=collaborate.Board AND user.ID=collaborate.Collaborator AND Board.ID= :id_board",array("id_board"=>$id_board));
    //     $data = $query->fetchAll();
    //     $collaborators = [];

    //     foreach ($data as $row){
    //         $collaborators[] = $row["Mail"];

    //     }

    //     return $collaborators;
    // } 

    public static function get_columns_exist_in_Board($id_board)
    {
        $query = self::execute("SELECT * FROM `column` where Board=:id_board", array("id_board" => $id_board));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function delete_board($id_board)
    {
        if (self::get_columns_exist_in_Board($id_board)) {
            return false;
        }
        return $query = self::execute("DELETE FROM board WHERE id = :id ", array("id" => $id_board));
    }

    public static function delete_boardd($id_board)
    {
        $query = self::execute("DELETE FROM board WHERE id = :id ", array("id" => $id_board));
    }
    public static function add_collaborator($id_board, $collaborator)
    {
        $query = self::execute("INSERT INTO `collaborate` (Board,Collaborator) VALUES (:id_board,:collaborator)", array("id_board" => $id_board, "collaborator" => $collaborator));
    }
    public static function delete_collaborator($id_board, $collaborator)
    {
        //suppression des collaborateurs ainsi que des cartes ou ils participent  
        $query_collaborate = self::execute("DELETE FROM collaborate WHERE Collaborator =:collaborator AND Board=:id_board", array("collaborator" => $collaborator, "id_board" => $id_board));
        $query_participate = self::execute("DELETE FROM participate WHERE Participant=:collaborator AND Card IN (SELECT card.id FROM `card` JOIN `column` ON card.Column= `column`.ID WHERE `column`.`Board` =:id_board)
        ", array("collaborator" => $collaborator, "id_board" => $id_board));
    }
    public static function delete_allcollaborator($id_board)
    {
        //suppression des collaborateurs ainsi que des cartes ou ils participent  
        $query_collaborate = self::execute("DELETE FROM collaborate WHERE  Board=:id_board", array("id_board" => $id_board));
        $query_participate = self::execute("DELETE FROM participate WHERE  Card IN (SELECT card.id FROM `card` JOIN `column` ON card.Column= `column`.ID WHERE `column`.`Board` =:id_board)
        ", array("id_board" => $id_board));
    }

    public static function delete_collaboration($id_user)
    {
        //suppression des collab d'un user
        $query = self::execute("DELETE FROM collaborate where Collaborator=:id_user", array("id_user" => $id_user));
    }


    public static function add_participant($collaborator, $id_card)
    {
        return self::execute("INSERT INTO `participate` (Participant,Card) VALUES (:collaborator,:id_card)", array("collaborator" => $collaborator, "id_card" => $id_card));
    }
    public static function get_all_board($idUser)
    {
        $query = self::execute("SELECT ID  FROM board WHERE Owner= :id_user", array("id_user" => $idUser));
        $data = $query->fetchAll();
        $id_board = [];
        $allboard = [];
        foreach ($data as $row) {
            $allboard[] = Board::get_board_by_id($row["ID"]);
        }

        $query = self::execute("SELECT  Board ID FROM collaborate WHERE Collaborator = :id_user", array("id_user" => $idUser));
        $data = $query->fetchAll();
        foreach ($data as $row) {
            $allboard[] = Board::get_board_by_id($row["ID"]);
        }
        return $allboard;
    }


    public static function get_number_column($id_board)
    {
        $query = self::execute("SELECT count(*) as number from `column` where Board = :id_board", array("id_board" => $id_board));
        $row = $query->fetch();
        $count = (int) $row['number'];
        return $count;
    }
    public static function set_position_column($position, $column)
    {
        return self::execute("UPDATE `column` SET `position`=:pos WHERE `ID`=:id  ", array("pos" => $position, "id" => $column));
    }
    public static function set_position_card($position, $card, $column)
    {
        return self::execute("UPDATE `card` SET `position`=:pos,`column`=:column WHERE `ID`=:id  ", array("pos" => $position, "column" => $column, "id" => $card));
    }
}

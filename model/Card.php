
<?php

require_once "framework/Model.php";
require_once "framework/Controller.php";
require_once "Board.php";
require_once "model/Member.php";


class Card extends Model
{
    public $ID;
    public $Title;
    public $Body;
    public $Position;
    public $CreatedAt;
    public $ModifiedAt;
    public $Author;
    public $Column;
    public $DueDate;

    public function __construct($Column, $Title, $ID = null, $Body = null, $Position = null, $CreatedAt = null, $ModifiedAt = null, $Author = null, $DueDate = null)
    {
        $this->Column = $Column;
        $this->Title = $Title;
        $this->ID = $ID;
        $this->Body = $Body;
        $this->Position = $Position;
        $this->CreatedAt = $CreatedAt;
        $this->ModifiedAt = $ModifiedAt;
        $this->Author = $Author;
        $this->DueDate = $DueDate;
    }

    public function get_other_card($id)
    {
        $query = self::execute(
            "SELECT Title,
                                (SELECT count(*) FROM card where Title=:titles) as Title
                 FROM card WHERE Title:titles",
            array("titles" => $this->title)
        );
        return $query->fetchAll();
    }

    public static function get_card($ID)
    {
        $query = self::execute("SELECT * FROM card where id =:id", array("id" => $ID));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();

            return new Card($row["Column"], $row["Title"], $row["ID"], $row["Body"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"], $row["Author"], $row["DueDate"]);
        }
    }

    public static function get_id_column_card($id_card)
    {
        $query = self::execute("SELECT `column` FROM `card` where ID=:id_card", array("id_card" => $id_card));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();

            return $row;
        }
    }

    public static function get_date_without_time($id_card)
    {
        $query = self::execute("select DATE_FORMAT(CreatedAt,'%Y-%m-%d') as date from card where ID=:id_card", array("id_card" => $id_card));
        if ($query->rowCount() == 0)
            return false;
        $row = $query->fetch();
        $date = $row['date'];
        return $date;
    }

    public static function check_due_date($due_date)
    {
        $date_current = date("Y-m-d");
        if ($due_date > $date_current)
            return false;
        return true;
    }


    public static function get_title_card($ID)
    {
        $query = self::execute("SELECT Title FROM card where id = :id", array("id" => $ID));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();

            return new Card($row["ID"], $row["Title"]);
        }
    }


    public static function get_cards_by_column($id_column)
    {
        $query = self::execute("SELECT * from `Card` where `Column` = :id_column ORDER BY `card`.`Position` ASC", array("id_column" => $id_column));
        $data = $query->fetchAll();
        $cards = [];

        foreach ($data as $row) {
            $cards[] = new Card($row["Column"], $row["Title"], $row["ID"], $row["Body"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"], $row["Author"], $row["DueDate"]);
        }


        return $cards;
    }


    public static function get_cards_by_column2($id_column)
    {
        $query = self::execute("SELECT * from `Card` where `Column` = :id_column", array("id_column" => $id_column));
        $row = $query->fetch();


        if ($query->rowCount() == 0) {
            return false;
        } else {

            return new Card($row["Column"], $row["Title"], $row["ID"], $row["Body"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"], $row["Author"], $row["DueDate"]);
        }
    }


    public function update($user)
    {

        if ($this->ID == null) {
            $member = Member::get_member_by_fullname($user->fullName);

            $lastpos = count(self::get_cards_by_column($this->Column));



            self::execute('INSERT INTO card (Title,Position,Author,`Column`) VALUES (:title,:position,:author,:column)', array('title' => $this->Title, 'position' => $lastpos, 'author' => $member->id, 'column' => $this->Column));
            $cardd = self::get_card(self::lastInsertId());
            $this->ID = $cardd->ID;
            $this->CreatedAt = $cardd->CreatedAt;

            return $this;
        } else {
            throw new Exception("Erreur implementation");
        }
    }

    public static function update_change_pos($new_pos, $pos)
    {
        return self::execute("UPDATE `card` SET Position =:new_pos where Position=:pos", array("new_pos" => $new_pos, "pos" => $pos));
    }
    public static function update_change_pos_card($new_pos, $id_card)
    {
        return self::execute("UPDATE `card` SET Position =:new_pos where ID=:id_card", array("new_pos" => $new_pos, "id_card" => $id_card));
    }

    public static function edit_title($title, $id_card)
    {
        self::execute(
            "UPDATE card SET Title= :title WHERE ID= :id",
            array("title" => $title, "id" => $id_card)
        );
    }

    public static function edit_body($body, $id_card)
    {
        self::execute(
            "UPDATE card SET Body= :body WHERE ID= :id",
            array("body" => $body, "id" => $id_card)
        );
    }

    public static function get_date_modified($id_card)
    {
        $card = Card::get_card($id_card);
        if ($card->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $card->ModifiedAt;
        }
    }

    public function validate()
    {
        $errors = array();
        if (!(isset($this->Title) && is_string($this->Title) && strlen($this->Title) > 0)) {
            $errors[] = "Title is required.";
        }
        if (!(isset($this->Title) && is_string($this->Title) && strlen($this->Title) >= 3)) {
            $errors[] = "Title length must be higher than 3 .";
        }
        return $errors;
    }

    public static function validate_date($date)
    {
        $errors = array();
        if (!(isset($date) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date) && strlen($date) > 0)) {
            $errors[] = "Date is not valid";
        }
        return $errors;
    }


    public static function validate_title($title, $id_board)
    {
        $errors = [];

        $card = self::get_card_by_Title_and_ID_board($title, $id_board);

        if ($card) {
            $errors[] = "Ce titre existe deja";
        }

        return $errors;
    }

    public static function get_card_by_Title_and_ID_board($title, $id_board)
    {
        $query = self::execute("SELECT * FROM `card` JOIN `column` ON card.Column= `column`.ID 
                                WHERE `column`.`Board` = :id_board AND card.Title=:title", array("id_board" => $id_board, "title" => $title));
        $row = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {

            return false;
        } else {
            return new Card($row["Column"], $row["Title"], $row["ID"], $row["Body"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"], $row["Author"], $row["DueDate"]);
        }
    }


    public static function card_inside($pos1, $pos2, $id_column)
    {
        $data_r1 = self::execute("SELECT * FROM `card` WHERE `position` = :pos1 AND `column`= :id_column", array("pos1" => $pos1, "id_column" => $id_column));
        $row1 = $data_r1->fetch();
        $data_r2 = self::execute("SELECT * FROM `card` WHERE `position` = :pos1 AND `column`= :id_column", array("pos1" => $pos2, "id_column" => $id_column));
        $row2 = $data_r2->fetch();
        $id_t1 = $row1["ID"];
        $id_t2 = $row2["ID"];
        $query1 = self::execute(
            "UPDATE `card` SET `position` = :pos2 where `id` = :id",
            array("id" => $id_t1, "pos2" => $pos2)
        );
        $query2 = self::execute(
            "UPDATE `card` SET `position` = :pos1 where `id` = :id",
            array("id" => $id_t2, "pos1" => $pos1)
        );
    }
    public static function card_outside($pos2, $id_card, $id_board)
    {
        $dat_col = self::execute("SELECT * from `column` where POSITION = :pos2 AND BOARD = :id_board", array("pos2" => $pos2, "id_board" => $id_board));
        $row1 = $dat_col->fetch();
        $cards = Card::get_cards_by_column($row1["ID"]);
        $pos_c = count($cards);
        $query = self::execute("UPDATE `card`SET `column`= :column,Position = :pos_c WHERE ID= :id", array("column" => $row1["ID"], "pos_c" => $pos_c, "id" => $id_card));
    }


    public function delete($id_card)
    {
        if ($id_card != null) {


            self::execute('DELETE FROM Card WHERE id=:id_card', array('id_card' => $id_card));
        }
    
    }

    public function switch_position($id_card)
    {
        $id_column = Card::get_id_column_card($id_card);
    }

    public static function add_modified($date, $id_card)
    {
        return self::execute("UPDATE card SET ModifiedAt = :date WHERE ID=:id_card", array("date" => $date, "id_card" => $id_card));
        $card = Card::get_card($id_card);
        if ($card->ModifiedAt == NULL) {
            return "Never Modified";
        } else {
            return $card->ModifiedAt;
        }
    }



    public static function add_due_date($date, $id_card)
    {
        return self::execute("UPDATE card SET DueDate=:date WHERE ID=:id_card", array("date" => $date, "id_card" => $id_card));
    }

    public static function delete_card_participate($card)
    {
        $query = self::execute("DELETE FROM participate WHERE Card=:id_card", array("id_card" => $card));
    }

    public static function is_participate($id_user)
    {
        $query = self::execute("SELECT Card FROM `participate` WHERE Participant=:id_user", array("id_user" => $id_user));
        $data = $query->fetchAll();
        $cards = [];
        foreach ($data as $row) {
            $cards[] = $row["Card"];
        }
        return $cards;
    }

    public static function due_date_is_null($id_card)
    {
        $query = self::execute("SELECT DueDate FROM `card` WHERE ID:id_card", array("id_card" => $id_card));
        $data = $query->fetchAll();
    }

    public static function delete_due_date($id_card)
    {
        return self::execute("UPDATE card SET DueDate=NULL WHERE ID=:id_card", array("id_card" => $id_card));
    }

    public static function get_card_by_ID_board($id_board)
    {
        $query = self::execute("SELECT * FROM `card` WHERE card.Column IN ( SELECT ID FROM `column` WHERE column.Board=:id_board)", array("id_board" => $id_board));
        $data = $query->fetchAll();
        $cards = [];
        foreach ($data as $row) {
            $cards[] = new Card($row["Column"], $row["Title"], $row["ID"], $row["Body"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"], $row["Author"], $row["DueDate"]);
        }
        return $cards;
    }
}

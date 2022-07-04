<?php

require_once "framework/Model.php";
require_once "model/Card.php";

class Column extends Model
{
    public $ID;
    public $Title;
    public $Position;
    public $CreatedAt;
    public $ModifiedAt;
    public $Board;

    public function __construct($Title, $Board, $ID = null, $Position = null, $CreatedAt = null, $ModifiedAt = null)
    {
        $this->Title = $Title;
        $this->Board = $Board;
        $this->ID = $ID;
        $this->Position = $Position;
        $this->CreatedAt = $CreatedAt;
        $this->ModifiedAt = $ModifiedAt;
    }

    public static function edit_title($title, $id_column)
    {
        self::execute(
            "UPDATE `column` SET Title= :title WHERE ID= :id",
            array("title" => $title, "id" => $id_column)
        );
    }

    public static function get_title($id)
    {
        $query = self::execute("SELECT title FROM column where id = :id", array("id" => $id));
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $query;
        }
    }

    public static function get_other_column($id_board)
    {
        $query = self::execute(
            "
                                    SELECT count(*) FROM `column` where Board=:id_board 
                                    ",
            array("titles" => $id_board)
        );
        return $query->fetch();
    }



    public static function validate_title($title, $id_board)
    {
        $errors = [];
        $column = self::get_column_by_Title_and_ID_board($title, $id_board);
        if ($column) {
            $errors[] = "Ce titre existe deja";
        }

        return $errors;
    }

    public static function get_column_by_Title_and_ID_board($title, $id_board)
    {
        $query = self::execute("SELECT * from `column` where board=:id_board AND title=:title", array("id_board" => $id_board, "title" => $title));
        $row = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Column($row["Title"], $row["Board"], $row["ID"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"]);
        }
    }

    public static function get_columns_by_board($id_board)
    {
        $query = self::execute("SELECT * from `column` where Board = :id_board ORDER BY `column`.`Position` ASC", array("id_board" => $id_board));
        $data = $query->fetchAll();

        $columns = [];
        foreach ($data as $row) {
            $columns[] = new Column($row["Title"], $row["Board"], $row["ID"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"]);
        }
        return $columns;
    }
    public static function move_column($pos1, $pos2, $id_board)
    {
        $dat_r1 = self::execute("SELECT * from `column` where POSITION = :pos1 AND BOARD = :id_board", array("pos1" => $pos1, "id_board" => $id_board));
        $row1 = $dat_r1->fetch();
        $data_r2 = self::execute("SELECT * from `column` where POSITION = :pos2 AND BOARD = :id_board", array("pos2" => $pos2, "id_board" => $id_board));
        $row2 = $data_r2->fetch();
        $query1 = self::execute(
            "UPDATE `column` SET Position = :pos2 where id = :id",
            array("id" => $row1["ID"], "pos2" => $pos2)
        );
        $query2 = self::execute(
            "UPDATE `column` SET Position = :pos1 where id = :id",
            array("id" => $row2["ID"], "pos1" => $pos1)
        );
    }

    public function get_cards()
    {
        return Card::get_cards_by_column($this);
    }



    public static function get_column($ID)
    {
        $query = self::execute("SELECT * FROM `column` where id =:id", array("id" => $ID));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Column($row["Title"], $row["Board"], $row["ID"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"]);
        }
    }

    // public static function get_column_by_ID_board($ID){
    //     $query = self::execute("SELECT * FROM `column` where Board =:id", array("id" => $ID));
    //     if ($query->rowCount() == 0) {
    //         return false;
    //     } else {
    //         $row = $query->fetch();
    //         return new Column( $row["Title"],$row["Board"], $row["ID"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"]);
    //     }
    // }

    public static function get_column_by_ID_board($ID)
    {
        $query = self::execute("SELECT * FROM `column` where Board =:id", array("id" => $ID));
        $data = $query->fetchAll();
        $columns = [];

        foreach ($data as $row) {
            $columns[] = new Column($row["Title"], $row["Board"], $row["ID"], $row["Position"], $row["CreatedAt"], $row["ModifiedAt"]);
        }


        return $columns;
    }

    public static function update_change_pos($new_pos, $pos)
    {
        return self::execute("UPDATE `column` SET Position =:new_pos where Position=:pos", array("new_pos" => $new_pos, "pos" => $pos));
    }

    public function update()
    {
        $columns = Column::get_columns_by_board($_GET['param1']);
        $pos1 = count($columns);

        if ($this->ID == null) {
            self::execute('INSERT INTO `column` (Title,Board,Position) VALUES (:title,:board,:pos)', array('title' => $this->Title, 'board' => $this->Board, 'pos' => $pos1));
            $columnn = self::get_column(self::lastInsertId());
            $this->ID = $columnn->ID;
            $this->CreatedAt = $columnn->CreatedAt;
            $this->Position = $columnn->Position;
            return $this;
        } else {
            throw new Exception("Erreur implementation");
        }
    }

    public function delete($id_column)
    {
        if ($id_column != null) {
            self::execute('DELETE FROM `Column` WHERE ID=:id_column', array('id_column' => $id_column));
        }
    }

    public static function get_number_card($id_column)
    {
        $query = self::execute("SELECT count(*) as number from Card where `column` = :id_column", array("id_column" => $id_column));
        $row = $query->fetch();
        $count = (int) $row['number'];
        return $count;
    }


}

<?php

require_once "framework/Model.php";

class Member extends Model
{
    public $mail;
    public $hashed_password;
    public $fullName;
    public $role;
    public $id;
    public $registered;


    public function __construct($mail, $hashed_password, $fullName, $role = null,  $id = null, $registered = null)
    {
        $this->mail = $mail;
        $this->hashed_password = $hashed_password;
        $this->fullName = $fullName;
        $this->role = $role;
        $this->id = $id;
        $this->registered = $registered;
    }


    public static function get_member_by_mail($mail)
    {
        $query = self::execute("SELECT * FROM user where mail = :mail", array("mail" => $mail));
        $row = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
    }

    public static function is_collaborate($collaborate, $id)
    {
        $query = self::execute("SELECT Board from collaborate where Collaborator= :id_user", array("id_user" => $collaborate));
        $data = $query->fetchAll();
        $board = [];
        foreach ($data as $row) {
            $board[] = $row["Board"];
        }

        return in_array($id, $board, true);
    }

    public static function is_participant($participant)
    {
        $query = self::execute("SELECT Board from participate where Participant= :participant", array("participant" => $participant));
        $data = $query->fetchAll();
        $board = [];
        foreach ($data as $row) {
            $board[] = $row["Board"];
        }
        return $board;
    }



    public static function get_member_by_id($id)
    {
        $query = self::execute("SELECT * FROM user where id = :id", array("id" => $id));
        $row = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
    }

    public static function get_members()
    {
        $query = self::execute("SELECT * from User ORDER BY fullName ASC", array());
        $data = $query->fetchAll();
        $user = [];

        foreach ($data as $row) {
            $user[] = new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }

        return $user;
    }

    private static function check_password($clear_password, $hash)
    {
        return $hash === Tools::my_hash($clear_password);
    }

    public static function validate_login($mail, $password)
    {
        $errors = [];
        $member = Member::get_member_by_mail($mail);
        if ($member) {
            if (!self::check_password($password, $member->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Not exist mail '$mail'. Please sign up.";
        }
        return $errors;
    }

    public static function validate_signup_mail($mail)
    {
        $errors = [];
    }


    public function validate()
    {
        $errors = array();
        if (!(isset($this->mail) && is_string($this->mail) && strlen($this->mail) > 0)) {
            $errors[] = "mail is required.";
            // } if (!(isset($this->mail) && is_string($this->mail) && strlen($this->mail) >= 3 && strlen($this->mail) <= 16)) {
            //     $errors[] = "mail length must be between 3 and 16.";
            // } if (!(isset($this->mail) && is_string($this->mail) && preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->mail))) {
            //     $errors[] = "mail must contain @";
        }
        return $errors;
    }


    public static function validate_unicity($mail)
    {
        $errors = [];
        $member = self::get_member_by_mail($mail);
        if ($member) {
            $errors[] = "This user already exists.";
        }
        return $errors;
    }

    public static function validate_fullname($fullname)
    {
        $errors = [];
        if (strlen($fullname) < 3) {

            $errors[] = "Full Name lenght must minimum 3 caractere";
        }
        return $errors;
    }


    public static function validate_password($password)
    {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        }
        if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }

    public static function validate_mail($mail)
    {
        $errors = [];

        if (!(preg_match("[@]", $mail))) {
            $errors[] = "Mail must contain @";
        }
        return $errors;
    }

    public static function validate_passwords($password, $password_confirm)
    {
        $errors = Member::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }


    public function update()
    {
        if (self::get_member_by_mail($this->mail))
            self::execute(
                "UPDATE user SET password=:password WHERE mail=:mail ",
                array("mail" => $this->mail, "fullname" => $this->fullName, "password" => $this->hashed_password, "registered" => $this->registered)
            );
        else
            self::execute(
                "INSERT INTO user(mail,fullname,password) VALUES(:mail,:fullname,:password)",
                array("mail" => $this->mail, "fullname" => $this->fullName, "password" => $this->hashed_password)
            );
        return $this;
    }

    public static function get_member_by_owner_board($owner)
    {
        //$query = self::execute("SELECT * from `user` JOIN board on user.ID = board.ID where board.Owner= :owner", array("owner" => $owner));
        $query = self::execute("SELECT * from `user` where id= :owner", array("owner" => $owner));
        $row = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {

            return new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
    }

    public static function get_member_by_fullname($fullname)
    {

        $query = self::execute("SELECT * from `user` where FullName=:fullname", array("fullname" => $fullname));
        $row = $query->fetch(); // un seul résultat au maximum

        if ($query->rowCount() == 0) {
            return false;
        } else {

            return new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
    }


    public static function get_member_author_by_board($id_board)
    {

        $query = self::execute("SELECT * from `user` JOIN board on user.ID = board.ID where board.id= :id_board", array("id_board" => $id_board));
        $row = $query->fetch(); // un seul résultat au maximum

        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
    }


    // public static function is_collaborate($collaborate){
    //     $query=self::execute("SELECT Board from collaborate where Collaborator= :id_user",array("id_user"=>$collaborate));
    //     $data =$query->fetchAll();
    //     $board = [];
    //     foreach($data as $row){
    //         $board[]= $row["Board"];
    //     }
    //     return $board;
    // }

    public static function members()
    {
        $query = self::execute("SELECT * from user", array());
        $data = $query->fetchAll();
        $users = [];
        foreach ($data as $row) {
            $users[] = new Member($row["Mail"], $row["Password"], $row["FullName"], $row["Role"], $row["ID"], $row["RegisteredAt"]);
        }
        return $users;
    }
    public static function admin_members()
    {
        $query = self::execute('SELECT * FROM user where `Role`= "admin"', array());
        $data = $query->fetchAll();
        $admins = [];
        foreach ($data as $row) {
            $admins[] = $row["ID"];
        }
        return $admins;
    }
    public static function is_admin_members($id)
    {
        $query = self::execute('SELECT * FROM user where `Role`= "admin"', array());
        $data = $query->fetchAll();
        $admins = [];
        foreach ($data as $row) {
            $admins[] = $row["ID"];
        }
        return  in_array($id, $admins, true);
    }





    public static function edit_name($name, $id_user)
    {
        self::execute(
            "UPDATE user SET FullName = :fullname WHERE ID= :id",
            array("fullname" => $name, "id" => $id_user)
        );
    }
    public static function edit_mail($mail, $id_user)
    {
        self::execute(
            "UPDATE user SET Mail = :mail WHERE ID= :id",
            array("mail" => $mail, "id" => $id_user)
        );
    }
    public static function edit_password($password, $id_user)
    {
        self::execute(
            "UPDATE user SET Password = :password WHERE ID= :id",
            array("password" => $password, "id" => $id_user)
        );
    }
    public static function edit_role($role, $id_user)
    {
        self::execute(
            "UPDATE user SET Role = :role WHERE ID= :id",
            array("role" => $role, "id" => $id_user)
        );
    }

    // public static function get_collaborate_by_id_board($id_board){
    //     $query = self::execute("SELECT user.Mail,user.Password, user.FullName, user.ID, user.RegisteredAt FROM `user`JOIN collaborate ON user.ID = collaborate.Collaborator WHERE board=:id_board", array("id_board" => $id_board));
    //     $data = $query->fetchAll(); 
    //     $collaborate = [];

    //     foreach ($data as $row) {
    //         $collaborate[] = new Member($row["Mail"], $row["Password"], $row["FullName"], $row["ID"], $row["RegisteredAt"]);
    //     }

    //     return $collaborate;
    // }

    public function delete($id_user)
    {
        if ($id_user != null) {
            self::execute('DELETE FROM User WHERE id=:id_user', array('id_user' => $id_user));
        }
    }


    public static function get_number_collab($id_user)
    {
        $query = self::execute("SELECT count(Board) as number from collaborate where Collaborator = :id_user", array("id_user" => $id_user));
        $row = $query->fetch();
        $count = (int) $row['number'];
        return $count;
    }

    public static function delete_participation($id_user)
    {
        self::execute('DELETE FROM PARTICIPATE WHERE Participant=:id_user', array("id_user" => $id_user));
    }
}

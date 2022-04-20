<?php
class Users {
    private $table = "users_table";
    private $connection;

    private $id;
    private $name;
    private $email;
    private $password;
    private $contact;

    private $user_type;
    private $status;
    private $company_id;
    private $reports_to;
    private $created_at;
    private $updated_at;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function authenticate_user($email) {
        $fetch_user = $this->connection->prepare("SELECT id,name,email,password 
                                                FROM " . $this->table . " where email=:email and user_type=1");
        $fetch_user->bindValue(':email', $email);
        $fetch_user->execute();
        return $fetch_user->fetch(PDO::FETCH_OBJ);
    }

    public function save($user_data){

        $check_email = $this->connection->prepare("SELECT id, email  FROM " . $this->table . " where email='".$user_data['email']."'");

        $check_email->execute();
        $results = $check_email->fetchAll(PDO::FETCH_ASSOC);

        if($results){
            return 'EMAIL_DUPLICATE';
        }

        $consultation = $this->connection->prepare("INSERT INTO " . $this->table . " (name,email,password,contact,user_type,company_id,reports_to,status_id,created_at,updated_at)
                                        VALUES (:name,:email,:password,:contact,:user_type,:company_id,:reports_to,:status_id,:created_at,:updated_at)");
        $result = $consultation->execute(array(
            "name" => $user_data['name'],
            "email" => $user_data['email'],
            "password" => password_hash($user_data['password'], PASSWORD_DEFAULT),
            "contact" => $user_data['contact'],
            "user_type" => $user_data['user_type'],
            "company_id" => $user_data['company_id'],
            "reports_to" => $user_data['reports_to'],
            "status_id" => $user_data['status_id'],
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ));
        $this->Connection = null;

        return 'USER_CREATED';
    }

    public function update($user_data){

        $check_email = $this->connection->prepare("SELECT id, email  FROM " . $this->table . " where id!=".$user_data['id']." and email='".$user_data['email']."'");

        $check_email->execute();
        $results = $check_email->fetchAll(PDO::FETCH_ASSOC);

        if($results){
            return 'EMAIL_DUPLICATE';
        }

        $consultation = $this->connection->prepare("
            UPDATE " . $this->table . " 
            SET 
                name = :name, 
                email = :email,
                contact = :contact,
                company_id = :company_id,
                reports_to = :reports_to,
                status_id = :status_id,
                updated_at = :updated_at
            WHERE id = :id 
        ");


        $consultation->execute(array(
            "id" => $user_data['id'],
            "name" => $user_data['name'],
            "email" => $user_data['email'],
            "contact" => $user_data['contact'],
            "company_id" => $user_data['company_id'],
            "reports_to" => $user_data['reports_to'],
            "status_id" => $user_data['status_id'],
            "updated_at" => date('Y-m-d H:i:s')
        ));
        $this->Connection = null;

        return 'USER_UPDATED';

    }

    public function getAll(){

        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, " . $this->table . ".name as name,company_name, email, contact, user_type, status_table.name as status_name, company_id,reports_to, created_at, updated_at FROM " . $this->table . "
            LEFT JOIN status_table 
            ON (status_table.id=".$this->table.".status_id)
             LEFT JOIN companies_table 
            ON (companies_table.id=".$this->table.".company_id)
             where user_type=2");

        $consultation->execute();
        $results = $consultation->fetchAll(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $results;
    }


    public function getUsersByReportee($reporteeID){
        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, " . $this->table . ".name as name,company_name, email, contact, user_type, status_table.name as status_name, company_id,reports_to, " . $this->table . ".created_at, " . $this->table . ".updated_at FROM " . $this->table . "
            LEFT JOIN status_table 
            ON (status_table.id=".$this->table.".status_id)
             LEFT JOIN companies_table 
            ON (companies_table.id=".$this->table.".company_id)
             where user_type=2 and reports_to=".$reporteeID);

        $consultation->execute();
        $results = $consultation->fetchAll(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $results;
    }

    public function getAllUsersByCompany($company_id){

        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, " . $this->table . ".name as name,company_name, email, contact, user_type, status_table.name as status_name, company_id,reports_to, " . $this->table . ".created_at, " . $this->table . ".updated_at FROM " . $this->table . "
            LEFT JOIN status_table
            ON (status_table.id=".$this->table.".status_id)
             LEFT JOIN companies_table
            ON (companies_table.id=".$this->table.".company_id)
             where user_type=2 and company_id=".$company_id);

        $consultation->execute();
        $results = $consultation->fetchAll(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $results;
    }

    public function getAllUsersByCompanySearch($company_id,$search){

        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, " . $this->table . ".name as name,company_name, email, contact, user_type, status_table.name as status_name, company_id,reports_to, " . $this->table . ".created_at, " . $this->table . ".updated_at FROM " . $this->table . "
            LEFT JOIN status_table
            ON (status_table.id=".$this->table.".status_id)
             LEFT JOIN companies_table
            ON (companies_table.id=".$this->table.".company_id)
             where user_type=2 and company_id=".$company_id." and (" . $this->table . ".name like '%".$search."%' or " . $this->table . ".id like '%".$search."%' or " . $this->table . ".contact like '%".$search."%')");

        $consultation->execute();
        $results = $consultation->fetchAll(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $results;
    }



    public function searchUsers($search){

        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, " . $this->table . ".name as name, company_name,email, contact, user_type, status_table.name as status_name, company_id,reports_to, ".$this->table.".created_at, ".$this->table.".updated_at FROM " . $this->table . "
            LEFT JOIN status_table 
            ON (status_table.id=".$this->table.".status_id)
             LEFT JOIN companies_table
            ON (companies_table.id=".$this->table.".company_id)
             where user_type=2 and (" . $this->table . ".name like '%".$search."%' or " . $this->table . ".id like '%".$search."%' or " . $this->table . ".contact like '%".$search."%')");


        $consultation->execute();
        $results = $consultation->fetchAll(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $results;
    }

    public function getUserByID($id){
        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, " . $this->table . ".name as name, email, contact, user_type, status_table.name as status_name,status_id, company_id,reports_to, created_at, updated_at FROM " . $this->table . "
            LEFT JOIN status_table
            ON (status_table.id=".$this->table.".status_id) where " . $this->table . ".id =".$id);


        $consultation->execute();
        $result = $consultation->fetch(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $result;
    }


    public function deleteByID($id){
        try {
            $consultation = $this->connection->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
            $consultation->execute(array(
                "id" => $id
            ));
            $Connection = null;
            return 'USER_DELETED';
        } catch (Exception $e) {
            echo 'Failed DELETE (deleteById): ' . $e->getMessage();
            return -1;
        }
    }

}
?>

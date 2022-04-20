<?php
class Companies {
    private $table = "companies_table";
    private $connection;

    public function __construct($connection) {
		$this->connection = $connection;
    }

    public function getAll(){

        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, company_name,company_code,status_table.name as status_name, created_at, updated_at FROM " . $this->table . "
            LEFT JOIN status_table 
            ON (status_table.id=".$this->table.".status_id) where status_id=1");

        $consultation->execute();
        $results = $consultation->fetchAll(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $results;
    }

    public function searchCompanies($search){
        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, company_name,company_code,status_table.name as status_name, created_at, updated_at FROM " . $this->table . "
            LEFT JOIN status_table 
            ON (status_table.id=".$this->table.".status_id) where status_id=1 and (" . $this->table . ".company_code like '%".$search."%')");

        $consultation->execute();
        $results = $consultation->fetchAll(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $results;
    }

    public function getCompanyByID($id){
        $consultation = $this->connection->prepare("SELECT " . $this->table . ".id, company_name,company_code,status_table.name as status_name,status_id, created_at, updated_at FROM " . $this->table . "
            LEFT JOIN status_table 
            ON (status_table.id=".$this->table.".status_id) where " . $this->table . ".id=".$id);

        $consultation->execute();
        $result = $consultation->fetch(PDO::FETCH_ASSOC);
        $this->connection = null;
        return $result;
    }

    public function save($company_data){

        $check_email = $this->connection->prepare("SELECT id, company_code  FROM " . $this->table . " where company_code='".$company_data['company_code']."'");

        $check_email->execute();
        $results = $check_email->fetchAll(PDO::FETCH_ASSOC);

        if($results){
            return 'CODE_DUPLICATE';
        }

        $consultation = $this->connection->prepare("INSERT INTO " . $this->table . " (company_name,company_code,status_id,created_at,updated_at)
                                        VALUES (:company_name,:company_code,:status_id,:created_at,:updated_at)");
        $result = $consultation->execute(array(
            "company_name" => $company_data['company_name'],
            "company_code" => $company_data['company_code'],

            "status_id" => $company_data['status_id'],
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ));
        $this->Connection = null;
        if($result){
            return 'COMPANY_CREATED';
        }
    }

    public function update($company_data){

        $check_email = $this->connection->prepare("SELECT id, company_code  FROM " . $this->table . " where  id!=".$company_data['id']." and company_code='".$company_data['company_code']."'");

        $check_email->execute();
        $results = $check_email->fetchAll(PDO::FETCH_ASSOC);

        if($results){
            return 'CODE_DUPLICATE';
        }

        $consultation = $this->connection->prepare("
            UPDATE " . $this->table . " 
            SET 
                company_name = :company_name, 
                company_code = :company_code,
                status_id = :status_id,
                updated_at = :updated_at
            WHERE id = :id 
        ");

        $consultation->execute(array(
            "id" => $company_data['id'],
            "company_name" => $company_data['company_name'],
            "company_code" => $company_data['company_code'],
            "status_id" => $company_data['status_id'],
            "updated_at" => date('Y-m-d H:i:s')
        ));
        $this->Connection = null;

        return 'COMPANY_UPDATED';

    }

    public function deleteByID($id){
        try {
            $consultation = $this->connection->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
            $consultation->execute(array(
                "id" => $id
            ));
            $Connection = null;
            return 'COMPANY_DELETED';
        } catch (Exception $e) {
            echo 'Failed DELETE (deleteById): ' . $e->getMessage();
            return -1;
        }
    }
}
?>

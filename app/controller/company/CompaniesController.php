<?php
require_once  __DIR__ . "/../BaseController.php";
require_once  __DIR__ . "/../../model/Companies.php";
require_once  __DIR__ . "/../../model/Users.php";

class CompaniesController extends BaseController{

    public function __construct() {
        parent::__construct();
        $this->check_user_session();
    }

    public function get_company_list(){
        $companyModel = new Companies($this->connectionObject);
        if(isset($_REQUEST['search'])){
            $companies = $companyModel->searchCompanies($_REQUEST['search']);
        }else{
            $companies = $companyModel->getAll();
        }
        print(json_encode($companies));
    }

    public function create(){
        $this->view("companies/create");
    }

    public function create_post(){
        $companyModel = new Companies($this->connectionObject);
        $companyCreated = $companyModel->save($_POST);
        if($companyCreated=='COMPANY_CREATED'){
            $this->set_session_message([null,'Company created successfully','success']);
            $this->response(200,"Company created successfully",null);
        }else if($companyCreated == 'CODE_DUPLICATE'){
            $this->response(500,"Company code already exist!",null);
        }
    }

    public function edit(){
        if($companyID = $_REQUEST['company_id']){
            $companyModel = new Companies($this->connectionObject);
            $company = $companyModel->getCompanyByID($companyID);
            $this->view("companies/update",['company'=>$company]);
        }
    }

    public function update_post(){
        if($_POST) {
            $companyModel = new Companies($this->connectionObject);
            $companyUpdated = $companyModel->update($_POST);
            if ($companyUpdated == 'COMPANY_UPDATED') {
                $this->set_session_message([null, 'Company updated successfully', 'success']);
                $this->response(200, "Company updated successfully", null);
            } else if ($companyUpdated == 'CODE_DUPLICATE') {
                $this->response(500, "Company code already exist!", null);
            }
        }
    }

    public function delete_post(){
        if($_REQUEST['id']) {
            $companyModel = new Companies($this->connectionObject);
            $companyDeleted = $companyModel->deleteByID($_REQUEST['id']);
            if ($companyDeleted == 'COMPANY_DELETED') {
                $this->set_session_message([null, 'Company deleted successfully', 'success']);
                $this->redirect('/admin/companies',200);
            }
        }
    }

    public function employees(){
        $this->view('/companies/user_index', ['company_id'=>$_REQUEST['company_id']]);
    }

    public function get_users_by_company_id(){
        $usersModel = new Users($this->connectionObject);
        if(isset($_REQUEST['company_id'])){
            $users = $usersModel->getAllUsersByCompanySearch($_REQUEST['company_id'],$_REQUEST['search']);
            print(json_encode($users));
        }
    }

    public function reportees(){
        $this->view('/companies/reportees_index', ['id'=>$_REQUEST['id']]);
    }

    public function sub_ordinates(){
        $usersModel = new Users($this->connectionObject);
        if(isset($_REQUEST['id'])){
            $users = $usersModel->getUsersByReportee($_REQUEST['id']);
            print(json_encode($users));
        }
    }
}
?>

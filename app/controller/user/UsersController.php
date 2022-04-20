<?php
require_once  __DIR__ . "/../BaseController.php";
require_once  __DIR__ . "/../../model/Users.php";
require_once  __DIR__ . "/../../model/Companies.php";


class UsersController extends BaseController{

    public function __construct() {
        parent::__construct();
        $this->check_user_session();
    }

    public function get_user_list(){
        $userModel = new Users($this->connectionObject);
        if(isset($_REQUEST['search'])){
            $users = $userModel->searchUsers($_REQUEST['search']);
        }else{
            $users = $userModel->getAll();
        }
        print(json_encode($users));
    }

    public function create(){
        $companiesModel = new Companies($this->connectionObject);
        $companies = $companiesModel->getAll();
        $this->view("users/create",['companies'=>$companies]);
    }

    public function create_post(){
        $userModel = new Users($this->connectionObject);
        $userCreated = $userModel->save($_POST);
        if($userCreated=='USER_CREATED'){
            $this->set_session_message([null,'User created successfully','success']);
            $this->response(200,"User created successfully",null);
        }else if($userCreated == 'EMAIL_DUPLICATE'){
            $this->response(500,"Email already exist!",null);
        }
    }

    public function get_users_by_company_id(){
        $usersModel = new Users($this->connectionObject);
        if(isset($_REQUEST['id'])){
            $users = $usersModel->getAllUsersByCompany($_REQUEST['id']);
            print(json_encode($users));
        }
    }

    public function edit(){
        if($userID = $_REQUEST['employee_id']){
            $userModel = new Users($this->connectionObject);
            $user = $userModel->getUserByID($userID);

            $companiesModel = new Companies($this->connectionObject);
            $companies = $companiesModel->getAll();
            $this->view("users/update",['user'=>$user,'companies'=>$companies]);
        }
    }


    public function update_post(){
        if($_POST) {
            $userModel = new Users($this->connectionObject);
            $userUpdated = $userModel->update($_POST);
            if ($userUpdated == 'USER_UPDATED') {
                $this->set_session_message([null, 'User updated successfully', 'success']);
                $this->response(200, "User updated successfully", null);
            } else if ($userUpdated == 'EMAIL_DUPLICATE') {
                $this->response(500, "Email already exist!", null);
            }
        }
    }

    public function delete_post(){
        if($_REQUEST['id']) {
            $userModel = new Users($this->connectionObject);
            $userCreated = $userModel->deleteByID($_REQUEST['id']);
            if ($userCreated == 'USER_DELETED') {
                $this->set_session_message([null, 'User deleted successfully', 'success']);
                $this->redirect('/admin/employees',200);
            }
        }
    }
}
?>

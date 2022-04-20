<?php
require_once  __DIR__ . "/../BaseController.php";

class AdminController extends BaseController{

    public function __construct() {
        parent::__construct();
        $this->check_user_session();
    }

    public function dashboard(){
        echo '<h1>This is your dashboard</h1>';
        echo '<a href="/admin/employees">Employees</a><br><br>';
        echo '<a href="/admin/companies">Companies</a><br><br>';
        echo '<a href="/auth/logout">Logout</a>';
    }

    public function employees(){
        $this->view('/users/index', 200);
    }

    public function companies(){
        $this->view('/companies/index', 200);
    }

    public function error404(){
        $this->view('/errors/404', 200);
    }

    public function error500(){
        $this->view('/errors/500', 200);
    }



}
?>

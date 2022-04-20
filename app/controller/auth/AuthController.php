<?php
require_once  __DIR__ . "/../BaseController.php";
require_once  __DIR__ . "/../../model/Users.php";

class AuthController extends BaseController{

    public function __construct() {
        parent::__construct();
    }

    public function login(){
        if(isset($_SESSION["auth_id"])){
            header('Location: ' . '/admin/dashboard', true, 200);
        }
        $this->view("auth/login");
    }

    public function login_post()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['uname'];
            $password = $_POST['psw'];

            $userModel = new Users($this->connectionObject);
            $user_exists = $userModel->authenticate_user($email);
            if (isset($user_exists->id)) {
                if (password_verify($password, $user_exists->password)) {
                    $_SESSION["auth_id"] = $user_exists->id;
                    $_SESSION["email"] = $user_exists->email;
                    $_SESSION["name"] = $user_exists->name;
                    $this->set_session_message([$_POST['uname'], 'Successfully logged in!!', 'success']);
                    $this->redirect('/admin/dashboard', 200);
                } else {
                    $this->unset_user_session();
                    $this->set_session_message([$_POST['uname'], 'Invalid password', 'danger']);
                    $this->redirect('/auth/login', 200);
                }
            } else {
                $this->unset_user_session();
                $this->set_session_message([$_POST['uname'], "Couldn't find your account", 'danger']);
                $this->redirect('/auth/login', 200);
            }
        }
        $this->view('/errors/404', 200);
    }

    public function logout(){
        $this->unset_user_session();
        $this->set_session_message([null,'Logged out successfully','success']);
        $this->redirect('/auth/login',200);
    }
}
?>

<?php
class BaseController{
   /**
    * Create the view that we pass to it with the indicated data.
    *
    */
    public $connectorClass;
    public $connectionObject;

    public function __construct() {
        require_once  __DIR__ . "/../core/Connector.php";
        $this->connectorClass=new Connector();
        $this->connectionObject=$this->connectorClass->connection();
    }

    public function view($viewPath,$parameters=null){
        $data = $parameters;
        require_once  __DIR__ . "/../views/" . $viewPath . ".php";
    }

    public function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    public function unset_user_session(){
        unset($_SESSION['auth_id']);
        unset($_SESSION['email']);
        unset($_SESSION['name']);
        return true;
    }

    public function check_user_session(){
        if(!isset($_SESSION["auth_id"])){
            $this->set_session_message([null,'Please login to continue!','danger']);
            header('Location: ' . '/auth/login', true, 200);
        }
    }

    public function set_session_message($messageArray){
        $_SESSION['message_array']=$messageArray;
    }

    public function response($status,$status_message,$data)
    {
        header("HTTP/1.1 ".$status);
        $response['status']=$status;
        $response['status_message']=$status_message;
        $response['data']=$data;

        $json_response = json_encode($response);
        echo $json_response;
    }
}
?>

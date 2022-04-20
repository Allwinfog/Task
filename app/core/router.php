<?php
error_reporting(E_ERROR | E_PARSE);
session_start();


/* Notification message block */
if(isset($_SESSION['message_array']) && isset($_SESSION['message_array'][1])){
    $message_color = 'black';
    if($_SESSION['message_array'][2]=='danger'){
        $message_color='red';
    }else if($_SESSION['message_array'][2]=='success'){
        $message_color='green';
    }
    echo '<div id="alert-message"  style="color:'.$message_color.'">';
    echo '<h3>'.$_SESSION['message_array'][1].'</h3>';
    echo '</div>';
    unset($_SESSION['message_array']);
    echo "<script>setTimeout(() => { var alert_message = document.getElementById('alert-message');alert_message.style.display = 'none';
}, 4000);
</script>";
}



/* URL Parsing block */

/* Handling requests similar to Yii2 framework */
/* http://localhost/admin/dashboard */
/*     Domain Name / Controller Name / Method Name */


$requestUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$requestUri = explode("?",$requestUri);
$requestUri = explode("/",$requestUri[0]);

$path = [];
if(isset($requestUri[3])){
    $path['controllerName'] = $requestUri[3];
}

if(isset($requestUri[4])){
    $path['action'] = $requestUri[4];
}


if(isset($path['controllerName'])){
    // We load the instance of the corresponding controller
    $controllerObj=loadController($path['controllerName']);

    //We launch the action
    launchAction($controllerObj,$path);
}

/* Controller assignments based on the request */

function loadController($controller){
    $controllerObj = null;
    switch ($controller) {
        case 'admin':
            $strFileController='controller/admin/AdminController.php';
            require_once $strFileController;
            $controllerObj=new AdminController();
            break;
        case 'auth':
            $strFileController='controller/auth/AuthController.php';
            require_once $strFileController;
            $controllerObj=new AuthController();
            break;
        case 'user':
            $strFileController='controller/user/UsersController.php';
            require_once $strFileController;
            $controllerObj=new UsersController();
            break;
        case 'company':
            $strFileController='controller/company/CompaniesController.php';
            require_once $strFileController;
            $controllerObj=new CompaniesController();
            break;
        default:
            /*For handling 404*/
            $strFileController='controller/admin/AdminController.php';
            require_once $strFileController;
            $controllerObj=new AdminController();
            break;
    }
    return $controllerObj;
}


/* Assigning Action/Method based on the request */

function launchAction($controllerObj,$path){
    try{

        if(isset($path['action'])){

            $methodName = $path['action'];
//            var_dump($controllerObj);die;

            $controllerObj->$methodName();
        }else{
            $controllerObj->error404();
        }
    }catch (Throwable $e){
        $strFileController='controller/admin/AdminController.php';
        require_once $strFileController;
        $controllerObj=new AdminController();
        $controllerObj->error500();
    }
}

/* Used Password hashing algo */
/*
echo $hash = password_hash("test1234", PASSWORD_DEFAULT);

if (password_verify('allwin', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}*/

?>


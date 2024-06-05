<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

switch($action) {
    case 'login':
        echo $crud->login();
        break;
    case 'login2':
        echo $crud->login2();
        break;
    case 'logout':
        echo $crud->logout();
        break;
    case 'logout2':
        echo $crud->logout2();
        break;
    case 'save_user':
        echo $crud->save_user();
        break;
    case 'delete_user':
        echo $crud->delete_user();
        break;
    case 'signup':
        echo $crud->signup();
        break;
    case 'update_account':
        echo $crud->update_account();
        break;
    case 'save_settings':
        echo $crud->save_settings();
        break;
    case 'save_category':
        echo $crud->save_category();
        break;
    case 'delete_category':
        echo $crud->delete_category();
        break;
    case 'save_house':
        echo $crud->save_house();
        break;
    case 'delete_house':
        echo $crud->delete_house();
        break;
    case 'save_tenant':
        echo $crud->save_tenant();
        break;
    case 'delete_tenant':
        echo $crud->delete_tenant();
        break;
    case 'get_tdetails':
        echo $crud->get_tdetails();
        break;
    case 'save_payment':
        echo $crud->save_payment();
        break;
    case 'delete_payment':
        echo $crud->delete_payment();
        break;
    default:
        echo "Invalid action";
}

ob_end_flush();
?>

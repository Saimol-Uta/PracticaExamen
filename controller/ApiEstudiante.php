<?php
include_once "./model/crudStudent.php";

$opc = $_SERVER["REQUEST_METHOD"];

switch ($opc) {
    case 'GET':
        if (isset($_GET["cedula"])) {
            CrudStudent::select();
        } else {
            CrudStudent::select();
        }
        break;
    
    default:
        # code...
        break;
}
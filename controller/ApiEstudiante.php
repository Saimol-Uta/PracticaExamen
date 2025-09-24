<?php
include_once "../model/crudStudent.php";

$opc = $_SERVER["REQUEST_METHOD"];

switch ($opc) {
    case 'GET':
        if (isset($_GET["cedula"])) {
            CrudStudent::buscar();
        } else {
            CrudStudent::select();
        }
        break;
    case 'POST':
        CrudStudent::insert();
        break;
    case 'PUT':
        CrudStudent::update();
        break;
    case 'DELETE':
        CrudStudent::delete();
        break;
    default:
        # code...
        break;
}
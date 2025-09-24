<?php
include_once "../model/crudProduct.php";

$opc = $_SERVER["REQUEST_METHOD"];

switch ($opc) {
    case 'GET':
        if (isset($_GET["id"])) {
            CrudProduct::busqueda();
        } else {
            CrudProduct::select();
        }
        break;
    case 'POST':
        CrudProduct::insert();
        break;
    case 'PUT':
        CrudProduct::update();
        break;
    case 'DELETE':
        CrudProduct::delete();
        break;
    default:
        # code...
        break;
}
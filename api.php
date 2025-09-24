<?php

include_once "crud.php";

$opt = $_SERVER['REQUEST_METHOD'];

switch ($opt) {
    case 'GET':
        crud::select();
        break;
    
    default:
        
        break;
}
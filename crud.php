<?php
include_once "conexion.php";

class crud
{

public static function select () {
        $conexion = new conexion();
        $connection = $conexion->conn();

        $sqlQuery = "SELECT * FROM estudiantes";

        $resultado = $connection->prepare($sqlQuery);

        $resultado->execute();

        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
}




}
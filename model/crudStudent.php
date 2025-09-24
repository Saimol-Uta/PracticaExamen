<?php
include_once "conexion.php";
class CrudStudent {

    public static function insert()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $cedula = $_POST["cedula"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];

        $query = "INSERT INTO estudiantes VALUES('$cedula','$nombre','$apellido','$direccion','$telefono');";

        $resultado = $conn->prepare($query);

        $resultado->execute();

        $data = "exitoso";

        echo json_encode($data);

    }

    public static function buscar(){
        $conexionstudent = new conexionBase();
        $conn = $conexionstudent->conexionBase();

        $cedula = $_GET["cedula"];

        $query = "SELECT * FROM estudiantes WHERE cedula = '$cedula'";

        $resultado = $conn->prepare($query);
        $resultado->execute();


        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);

    }


    public static function update()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $cedula = $_GET["cedula"];
        $nombre = $_GET["nombre"];
        $apellido = $_GET["apellido"];
        $direccion = $_GET["direccion"];
        $telefono = $_GET["telefono"];

    $sqlUpdate = "UPDATE ESTUDIANTES SET  NOMBRE='$nombre', APELLIDO='$apellido', DIRECCION='$direccion', TELEFONO='$telefono' WHERE CEDULA='$cedula'";

        $resultado = $conn->prepare($sqlUpdate);

        $resultado->execute();

        $data = "exitoso";

        echo json_encode($data);

    }

    public static function select()
    {
            $conexionStudent = new conexionBase();
        $connect = $conexionStudent->conexionBase();

        $query = "select * from estudiantes";

        $resultado = $connect->prepare($query);
        $resultado->execute();

        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);

    }

    public static function delete(){
        $conex = new conexionBase();
        $conn = $conex->conexionBase();

        $cedula = $_GET["cedula"];

        $quey = "DELETE FROM estudiante WHERE cedula = '$cedula'";

        $res = $conn->prepare($quey);
        $res->execute();

        $data = "se elimino exitosamente";

        echo json_encode($data);
    }

    

}
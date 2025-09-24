<?php
include_once "conexion.php";
class CrudProduct {

    //si el producto existe este se suma la cantidad a la existente
    public static function insert()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $precio = $_POST["precio"];
        $cantidad = $_POST["cantidad"];

        // Comprobar si el producto ya existe: si existe sumar cantidad y actualizar precio
        try {
            $checkQuery = "SELECT cantidad FROM productos WHERE id = :id";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id);
            $checkStmt->execute();
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing && isset($existing['cantidad'])) {
                $newCantidad = (int)$existing['cantidad'] + (int)$cantidad;
                $updateQuery = "UPDATE productos SET nombre = :nombre, cantidad = :cantidad, precio = :precio WHERE id = :id";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':nombre', $nombre);
                $updateStmt->bindParam(':cantidad', $newCantidad);
                $updateStmt->bindParam(':precio', $precio);
                $updateStmt->bindParam(':id', $id);
                $updateStmt->execute();

                $data = "exitoso";
                echo json_encode($data);
                return;
            }
        } catch (Exception $e) {
            // Si hay error de comprobación, continuar con el insert original (comportamiento previo)
        }

        $query = "INSERT INTO productos VALUES('$id','$nombre','$cantidad','$precio');";

        $resultado = $conn->prepare($query);

        $resultado->execute();

        $data = "exitoso";

        echo json_encode($data);
    
    }

    public static function update()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $precio = $_POST["precio"];
        $cantidad = $_POST["cantidad"];

        $query = "UPDATE productos SET nombre='$nombre', cantidad='$cantidad', precio='$precio' WHERE id='$id';";

        $resultado = $conn->prepare($query);

        $resultado->execute();

        $data = "exitoso";

        echo json_encode($data);
    }

    public static function select()
    {
        $conexionstudent = new conexionBase();
        $conn = $conexionstudent->conexionBase();

        $query = "SELECT * FROM productos";

        $resultado = $conn->prepare($query);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    }

    public static function delete(){
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $id = $_GET["id"];

        $query = "DELETE FROM productos WHERE id = '$id'";

        $resultado = $conn->prepare($query);

        $resultado->execute();

        $data = "exitoso";

        echo json_encode($data);
    }

    public static function busqueda()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $id = $_GET["id"];

        $query = "SELECT * FROM productos WHERE id = '$id'";

        $resultado = $conn->prepare($query);
        $resultado->execute();

        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
    }

}
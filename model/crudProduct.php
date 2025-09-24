<?php
include_once "conexion.php";
class CrudProduct {

    //si el producto existe este se suma la cantidad a la existente
    public static function insert()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $codigo = $_POST["codigo"];
        $nombre = $_POST["nombre"];
        $precio = $_POST["precio"];
        $cantidad = $_POST["cantidad"];

        // Comprobar si el producto ya existe: si existe sumar cantidad y actualizar precio
        try {
            $checkQuery = "SELECT cantidad FROM productos WHERE codigo = :codigo";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bindParam(':codigo', $codigo);
            $checkStmt->execute();
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing && isset($existing['cantidad'])) {
                $newCantidad = (int)$existing['cantidad'] + (int)$cantidad;
                $updateQuery = "UPDATE productos SET nombre = :nombre, cantidad = :cantidad, precio = :precio WHERE codigo = :codigo";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':nombre', $nombre);
                $updateStmt->bindParam(':cantidad', $newCantidad);
                $updateStmt->bindParam(':precio', $precio);
                $updateStmt->bindParam(':codigo', $codigo);
                $updateStmt->execute();

                $data = "exitoso";
                echo json_encode($data);
                return;
            }
        } catch (Exception $e) {
            // Si hay error de comprobación, continuar con el insert original (comportamiento previo)
        }

        $query = "INSERT INTO productos VALUES('$codigo','$nombre','$cantidad','$precio');";

        $resultado = $conn->prepare($query);

        $resultado->execute();

        $data = "exitoso";

        echo json_encode($data);
    
    }

    public static function update()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $codigo = $_POST["codigo"];
        $nombre = $_POST["nombre"];
        $precio = $_POST["precio"];
        $cantidad = $_POST["cantidad"];

        $query = "UPDATE productos SET nombre='$nombre', cantidad='$cantidad', precio='$precio' WHERE codigo='$codigo';";

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

        $codigo = $_GET["codigo"];

        $query = "DELETE FROM productos WHERE codigo = '$codigo'";

        $resultado = $conn->prepare($query);

        $resultado->execute();

        $data = "exitoso";

        echo json_encode($data);
    }

    public static function busqueda()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $codigo = $_GET["codigo"];

        $query = "SELECT * FROM productos WHERE codigo = '$codigo'";

        $resultado = $conn->prepare($query);
        $resultado->execute();

        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
    }

}
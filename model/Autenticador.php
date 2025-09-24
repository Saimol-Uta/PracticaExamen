<?php
include_once "conexion.php";
class Autentificador
{

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

    public static function buscar()
    {
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

        $sqlUpdate = "UPDATE estudiantes SET  NOMBRE='$nombre', APELLIDO='$apellido', DIRECCION='$direccion', TELEFONO='$telefono' WHERE CEDULA='$cedula'";

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

    public static function delete()
    {
        $conex = new conexionBase();
        $conn = $conex->conexionBase();

        $cedula = $_GET["cedula"];

        $quey = "DELETE FROM estudiantes WHERE cedula = '$cedula'";

        $res = $conn->prepare($quey);
        $res->execute();

        $data = "se elimino exitosamente";

        echo json_encode($data);
    }

    /*
     * Método para iniciar sesión.
     * Espera `POST` con `cedula` y `password`.
     * Busca al estudiante por cédula y verifica la contraseña.
     * Si tiene éxito, inicia sesión y devuelve los datos del usuario.
     * Nota: Asumo que la tabla `estudiantes` contiene una columna `password`.
     */
    public static function login()
    {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        $cedula = isset($_POST['cedula']) ? $_POST['cedula'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;

        if (!$cedula || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'cedula y password requeridos']);
            return;
        }

        // Buscar usuario por cédula
        $query = "SELECT * FROM estudiantes WHERE cedula = :cedula LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
            return;
        }

        // Verificar password: si la columna 'password' parece hasheada, usar password_verify,
        // en caso contrario comparar en texto plano (asunción de compatibilidad con DB existente).
        $stored = isset($user['password']) ? $user['password'] : null;
        $passwordOk = false;

        if ($stored !== null) {
            // detectar hash bcrypt/simple
            if (password_get_info($stored)['algo'] !== 0) {
                $passwordOk = password_verify($password, $stored);
            } else {
                // comparación en claro (no recomendado pero posible según esquema existente)
                $passwordOk = ($password === $stored);
            }
        } else {
            // Si no existe columna password, denegar acceso (seguridad)
            echo json_encode(['status' => 'error', 'message' => 'No hay contraseña registrada para este usuario']);
            return;
        }

        if ($passwordOk) {
            // Guardar información mínima en sesión
            unset($user['password']);
            $_SESSION['user'] = $user;

            echo json_encode(['status' => 'success', 'data' => $user]);
            return;
        }

        echo json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
    }

    /*
     * Método para cerrar sesión.
     * Puede ser llamado por GET o POST.
     */
    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Destruir la sesión del usuario
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        // Destruir sesión completamente
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        echo json_encode(['status' => 'success', 'message' => 'Sesión cerrada']);
    }

    /*
     * Método para verificar si hay sesión activa.
     * Devuelve los datos del usuario si hay sesión, o status error si no.
     */
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user'])) {
            echo json_encode(['status' => 'success', 'data' => $_SESSION['user']]);
            return;
        }

        echo json_encode(['status' => 'error', 'message' => 'No hay sesión activa']);
    }

    /**
     * Registrar un nuevo estudiante.
     * Espera POST con: cedula, nombre, apellido, direccion, telefono, password
     * Hashea la contraseña con password_hash() antes de guardar.
     */
    public static function register()
    {
        $conexion = new conexionBase();
        $conn = $conexion->conexionBase();

        // Recoger datos desde POST
        $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : null;
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
        $apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : null;
        $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : null;
        $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;

        if (!$cedula || !$nombre || !$apellido || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'cedula, nombre, apellido y password son requeridos']);
            return;
        }

        // Verificar si ya existe el usuario
        $checkSql = "SELECT cedula FROM estudiantes WHERE cedula = :cedula LIMIT 1";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':cedula', $cedula);
        $checkStmt->execute();

        if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode(['status' => 'error', 'message' => 'Usuario ya existe']);
            return;
        }

        // Hashear la contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usando consulta preparada con parámetros enlazados
        $insertSql = "INSERT INTO estudiantes (cedula, nombre, apellido, direccion, telefono, password) VALUES (:cedula, :nombre, :apellido, :direccion, :telefono, :password)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bindParam(':cedula', $cedula);
        $insertStmt->bindParam(':nombre', $nombre);
        $insertStmt->bindParam(':apellido', $apellido);
        $insertStmt->bindParam(':direccion', $direccion);
        $insertStmt->bindParam(':telefono', $telefono);
        $insertStmt->bindParam(':password', $hash);

        try {
            $insertStmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Registro exitoso']);
        } catch (Exception $e) {
            // En caso de error, devolver mensaje genérico (no exponer detalles DB en producción)
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar usuario']);
        }
    }
}

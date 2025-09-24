<?php
class conexion
{

    public function conn()
    {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'SOA';

        try {
            $connect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        } catch (\Throwable $th) {
            die("fallo conexion");
        }

        return $connect;
    }
}

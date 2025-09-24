<?php

class conexionBase
{

    function conexionBase() {
        $servername = "localhost";
        $snd = "mysql:host=$servername;dbname=SOA";
        $username = "root";
        $pasword = "";

        try {
            $conexionBase = new PDO($snd, $username, $pasword);

        } catch (\Throwable $th) {
            die("fallo");
        }

        return $conexionBase;

    }

}

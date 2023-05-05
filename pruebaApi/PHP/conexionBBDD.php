<?php
    include 'constantes.php';

    //function para conectar con la base de datos
    function conectarBBDD() {
        $baseDatos = new SQLite3(RUTA_BBDD);

        if(!$baseDatos) {
            die(SIN_CONEXION);
        } else {
            return $baseDatos;
        }
    }

    //function para comprobar que el dni es verdadero
    function validarDNI($dniSinValidar) {
        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        $dni = strtoupper($dniSinValidar);
        if (!preg_match('/^[XYZ\d]{1}\d{7}[A-Z]$/', $dni)) {
            return false;
        }
        $numero = substr($dni, 0, -1);
        $letra = substr($dni, -1);
        $resto = $numero % 23;
        return $letras[$resto] == $letra;
    }
?>
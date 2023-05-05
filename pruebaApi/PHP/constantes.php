<?php
//------------------------------ BaseDeDatos ------------------------------
    define("RUTA_BBDD", "../BaseDeDatos/BBDD.db");
//------------------------------ ERRORES ------------------------------
    define("SIN_CONEXION", "NO SE PUDO REALIZAR LA CONEXION CON LA BASE DE DATOS");

    define("DNI_NO_ENCONTRADO", "EL DNI QUE HAS INTRODUCIDO NO SE ENCUENTRA EN LA BASE DE DATOS");
    define("DNI_FORMATO_ERRONEO", "EL FORMATO DE DNI QUE HAS INTRODUCIDO NO ES VALIDO");
    define("DNI_NO_INTRODUCIDO", "DEBES INTRUDUCIR UN DNI PARA CONSULTAR TUS DATOS");

    define("NOMBRE_NO_INTRODUCIDO", "FALTA DE INTRODUCIR UN NOMBRE DE USUARIO");

    define("EMAIL_NO_INTRODUCIDO", "FALTA DE INTRODUCIR UN EMAIL");

    define("CAPITAL_NO_INTRODUCIDO", "FALTA DE INTRODUCIR EL CAPITAL SOLICITADO");

    define("TAE_NO_INTRODUCIDO", "FALTA DE INTRODUCIR EL TAE");

    define("PLAZO_AMORTIZACION_NO_INTRODUCIDO", "FALTA DE INTRODUCIR EL PLAZO DE AMORTIZACION");

    define("ACTULIZAR_HIPOTECA", "ERROR AL ACTUALIZAR LOS DATOS DE HIPOTECA");

    define("ACCION_NO_ESPECIFICADA", "NO HAS ESPECIFICADO QUE ACCION QUIERES REALIZAR PARA MODIFICAR UN USUARIO INTRODUCE 'modificarUsuario' Y PARA LA SIMULACION DE HIPOTECA 'simulacionHipoteca'");

    define("SIN_PARAMETROS", "NECESITA INTRODUCIR PARAMETROS");
//------------------------------ ACIERTOS ------------------------------
    define("DATOS_INSERTADOS", "DATOS INSERTADOS CORRECTAMENTE");
//------------------------------ BORRADO ------------------------------
    define("DATOS_ELIMINADOS", "SE HA BORRADO EL REGISTRO EXITOSAMENTE");
?>
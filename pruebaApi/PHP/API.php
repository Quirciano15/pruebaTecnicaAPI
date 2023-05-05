<?php
    include "conexionBBDD.php";
    require_once 'constantes.php';

    $conectarBBDD = conectarBBDD();

//------------------------------ GET ------------------------------
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['dni'])){
            try {
                $dni = $_GET['dni'];
                
                if(validarDNI($dni)) {
                    //select para obtener los datos del usuario por dni
                    $sql = $conectarBBDD->prepare(
                        "SELECT * FROM infoUsuario where dni = ?"
                    );
                    $sql->bindParam(1, $dni);
                    $resultado = $sql->execute();
                    $fila = $resultado->fetchArray(SQLITE3_ASSOC);

                    if(!$fila) {
                        throw new Exception(DNI_NO_ENCONTRADO);
                    } else {
                        echo json_encode($fila);
                    }
                } else {
                    throw new Exception(DNI_FORMATO_ERRONEO);
                }
            } catch(Exception $e) {
                throw $e;
            }
        } else {
            throw new Exception(DNI_NO_INTRODUCIDO);
        }
        $conectarBBDD->close();
        exit();
    }

//------------------------------ POST ------------------------------
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            //Excepciones si no estan introducidos los campos correctos
            if(isset($_POST['nombre'])) { $nombre = $_POST['nombre']; } else { throw new Exception(NOMBRE_NO_INTRODUCIDO); }
            if(isset($_POST['dni'])) { $dni = $_POST['dni']; } else { throw new Exception(DNI_NO_INTRODUCIDO); }
            if(isset($_POST['email'])) { $email = $_POST['email']; } else { throw new Exception(EMAIL_NO_INTRODUCIDO); }
            if(isset($_POST['capital_solicitado'])) { $capital_solicitado = $_POST['capital_solicitado']; } else { throw new Exception(CAPITAL_NO_INTRODUCIDO); }

            if(validarDNI($dni)) {
                //insert un nuevo usuario
                $sql = $conectarBBDD->prepare(
                    "INSERT INTO infoUsuario ('nombre', 'dni', 'email', 'capital_solicitado') VALUES (?, ?, ?, ?)"
                );
                $sql->bindParam(1, $nombre);
                $sql->bindParam(2, $dni);
                $sql->bindParam(3, $email);
                $sql->bindParam(4, $capital_solicitado);

                $sql->execute();    
                echo DATOS_INSERTADOS;
            } else {
                throw new Exception(DNI_FORMATO_ERRONEO);
            }
        } catch (Exception $e) {
            throw $e;
        }
        $conectarBBDD->close();
        exit();
    }

//------------------------------ DELETE ------------------------------
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        try {
            $id = $_GET['dni'];

            //borrar el usuario por DNI
            $sql = $conectarBBDD->prepare(
                "DELETE FROM infoUsuario where dni = ?"
            );
            $sql->bindParam(1, $id);
            $sql->execute();

            echo DATOS_ELIMINADOS;
        } catch (Exception $e) {
            throw $e;
        }
        $conectarBBDD->close();
        exit();
    }

//------------------------------ PUT ------------------------------
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        if (isset($_GET['dni'])){
            try {
                $dni = $_GET['dni'];
                if(validarDNI($dni)) {
//simular hipoteca
                    if($_GET['accion'] == 'simulacionHipoteca') {
                    //te salta una exception si no introduces un TAE o un Plazo de amortizacion
                    if(isset($_GET['TAE'])) { $TAE = $_GET['TAE']; } else { throw new Exception(TAE_NO_INTRODUCIDO); }
                    if(isset($_GET['plazoAmortizacion'])) { $plazoAmortizacion = $_GET['plazoAmortizacion']; } else { throw new Exception(PLAZO_AMORTIZACION_NO_INTRODUCIDO); }

                    //select para obtener el capital solicitado
                    $sql = $conectarBBDD->prepare(
                        "SELECT * FROM infoUsuario WHERE dni = ?"
                    );
                    $sql->bindParam(1, $dni);
                    $resultado = $sql->execute();
                    $fila = $resultado->fetchArray(SQLITE3_ASSOC);
                    $capital_solicitado = $fila['capital_solicitado'];

                    //calculo cuota mensual
                    $i = $TAE / 100 / 12;
                    $n = $plazoAmortizacion * 12;
                    $cuota = $capital_solicitado * $i / (1 - (1 + $i) ** (-$n));
                    $cuotaFormateada = number_format($cuota, 2);

                    //calculo importe total
                    $importeTotal = $cuota * $n;
                    $importeTotalFormateado = number_format($importeTotal, 2);

                    //update con cuota mensual e importe total
                    $sql = $conectarBBDD->prepare(
                        "UPDATE infoUsuario SET cuota_mensual = ?, importe_total = ? WHERE dni = ?"
                    );
                    $sql->bindParam(1, $cuotaFormateada);
                    $sql->bindParam(2, $importeTotalFormateado);
                    $sql->bindParam(3, $dni);

                    //muestro los datos
                    if ($sql->execute()) {
                        $fila['cuota_mensual'] = $cuotaFormateada;
                        $fila['importe_total'] = $importeTotalFormateado;

                        echo json_encode($fila);
                    } else {
                        throw new Exception(ACTULIZAR_HIPOTECA);
                    }
                } 
//modificar usuario
                    else if($_GET['accion'] == 'modificarUsuario') {
                        $sql = "UPDATE infoUsuario SET ";
                        $parametros = array();

                        if(isset($_GET['nombre'])) {
                            $nombre = $_GET['nombre'];
                            $sql .= "nombre = ?, ";
                            $parametros[] = $nombre;
                        }
                        if(isset($_GET['email'])) {
                            $email = $_GET['email'];
                            $sql .= "email = ?, ";
                            $parametros[] = $email;
                        }
                        if(isset($_GET['capital_solicitado'])) {
                            $capital_solicitado = $_GET['capital_solicitado'];
                            $sql .= "capital_solicitado = ?, ";
                            $parametros[] = $capital_solicitado;
                        }

                        if (empty($parametros)) {
                            throw new Exception(SIN_PARAMETROS);
                        }

                        // Eliminar la coma final y añadir la condición del DNI
                        $sql = rtrim($sql, ", ");
                        $sql .= " WHERE dni = ?";
                        $parametros[] = $dni;

                        // Ejecutar la consulta
                        $stmt = $conectarBBDD->prepare($sql);

                        for ($i = 0; $i < count($parametros); $i++) {
                            $stmt->bindParam($i+1, $parametros[$i]);
                        }

                        $resultado = $stmt->execute();

                        // Obtener los datos actualizados del usuario
                        $sql = $conectarBBDD->prepare("SELECT * FROM infoUsuario WHERE dni = ?");
                        $sql->bindParam(1, $dni);
                        $resultado = $sql->execute();
                        $fila = $resultado->fetchArray(SQLITE3_ASSOC);
                        echo json_encode($fila);
                    } else {
                        throw new Exception(ACCION_NO_ESPECIFICADA);
                    }
                } else {
                throw new Exception(DNI_FORMATO_ERRONEO);
                }
            } catch(Exception $e) {
                throw $e;
            }
        } else {
            throw new Exception(DNI_NO_INTRODUCIDO);
        }
        $conectarBBDD->close();
        exit();
	}
?>
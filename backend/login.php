<?php
    require_once( './headers.php');
    require_once( './db.php' );
    
    $query = "SELECT * FROM usuarios WHERE correo = ?";
    $values = [ Data::get('correo') ];
    $sqlUsuarios = DB::runQuery( $query, $values );

    if ( $dbUsuarios = $sqlUsuarios->fetch_assoc() ) {
        $correo_existente = true;
        $usuario_clave_db = $dbUsuarios[ 'usuario_clave' ];
        $clave_correcta = password_verify( Data::get('usuario_clave'), $usuario_clave_db);
        if ( $clave_correcta ) {
            $clave_correcta = true;
            if ( $dbUsuarios['bloqueo'] == 0) {
                $usuario_bloqueado = false;
                $hora_ingreso = intval($dbUsuarios['hora_unix']);
                $query = "SELECT generales.duracion_sesion FROM generales";
                $sqlGenerales = DB::runQuery( $query );
                $dbGenerales = $sqlGenerales->fetch_assoc();
                $duracion_sesion = $dbGenerales['duracion_sesion'];
            } else {
                $usuario_bloqueado = true;
                $hora_ingreso = 0;
            }
        } else {
            $clave_correcta = false;
            $usuario_bloqueado = false;
        }
    } else {
        $clave_correcta = false;
        $usuario_bloqueado = false;
    }
    
    echo json_encode(array("correo_existente"=>$correo_existente,"clave_correcta"=>$clave_correcta,"usuario_bloqueado"=>$usuario_bloqueado));
    DB::dbDisconnect();
?>
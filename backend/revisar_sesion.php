<?php
    require_once( './headers.php');
    require_once( './db.php' );
    
    $query = "SELECT * FROM usuarios WHERE token = ?";
    $values = [ Data::get('token') ];
    $sqlUsuarios = DB::runQuery( $query, $values );

    if ( $dbUsuarios = $sqlUsuarios->fetch_assoc() ) {
        $ultimo_ingreso = intval($dbUsuarios['hora_unix']);
        $hora_actual = time();
        $tiempo_transcurrido = $hora_actual - $ultimo_ingreso;
        $query = "SELECT generales.duracion_sesion FROM generales";
        $sqlGenerales = DB::runQuery( $query );
        $dbGenerales = $sqlGenerales->fetch_assoc();
        $duracion_sesion_minutos = $dbGenerales['duracion_sesion'];
        $duracion_sesion_segundos = $duracion_sesion_minutos * 60;
        if ( $tiempo_transcurrido > $duracion_sesion_segundos ) {
            $en_sesion_previa = false;
        } else {
            $en_sesion_previa = true;
        }
    } else {
        $en_sesion_previa = false;
    }
    
    echo json_encode(array("en_sesion_previa"=>$en_sesion_previa));
    DB::dbDisconnect();
?>
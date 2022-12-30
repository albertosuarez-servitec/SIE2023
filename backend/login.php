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
                $id_usuario = $dbUsuarios['id_usuario'];
                $nombre_completo = $dbUsuarios['nombres'].' '.$dbUsuarios['primer_apellido'].' '.$dbUsuarios['segundo_apellido'];
                if ( $dbUsuarios['usuario_foto'] != '' ) {
                    $foto = $dbUsuarios['usuario_foto'];
                } else {
                    if ( $dbUsuarios['fk_id_sexo'] == 0 ) {
                        $foto = 'sindefinir.png';
                    } else if ( $dbUsuarios['fk_id_sexo'] == 1 ) {
                        $foto = 'mujer.png';
                    } else if ( $dbUsuarios['fk_id_sexo'] == 2 ) {
                        $foto = 'hombre.png';
                    } 
                }
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
                    $token = sha1(rand(0000,9999));
                    $query = "UPDATE `u107735516_siencrym`.`usuarios` SET `token` = ?, `hora_unix` = ? WHERE `id_usuario` = ?";
                    $values = [$token, $hora_actual, $dbUsuarios['id_usuario']];
                    DB::runQuery($query, $values);
                } else {
                    $en_sesion_previa = true;
                    $token = $dbUsuarios['token'];
                }
            } else {
                $usuario_bloqueado = true;
                $ultimo_ingreso = 0;
            }
        } else {
            $clave_correcta = false;
            $usuario_bloqueado = false;
        }
    } else {
        $clave_correcta = false;
        $usuario_bloqueado = false;
    }
    
    echo json_encode(array("correo_existente"=>$correo_existente,"clave_correcta"=>$clave_correcta,"usuario_bloqueado"=>$usuario_bloqueado,"en_sesion_previa"=>$en_sesion_previa,"token"=>$token,"hora_actual"=>$hora_actual,"ultimo_ingreso"=>$ultimo_ingreso,"id_usuario"=>$id_usuario,"nombre_completo"=>$nombre_completo,"foto"=>$foto));
    DB::dbDisconnect();
?>
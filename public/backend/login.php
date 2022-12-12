<?php
    require_once( './headers.php');
    require_once( './db.php' );
    
    $query = "SELECT * FROM usuarios WHERE usuario_correo = ?";
    $values = [ Data::get('usuario_correo') ];
    $sql = DB::runQuery( $query, $values );

    if ( $db = $sql->fetch_assoc() ) {
        $correo_existente = true;
        $usuario_clave_db = $db[ 'usuario_clave' ];
        $clave_correcta = password_verify( Data::get('usuario_clave'), $usuario_clave_db);
        if ( $clave_correcta ) {
            $password_correcto = true;
            if ( $db['usuario_bloqueado'] == 0) {
                $usuario_bloqueado = false;
                $hora_ingreso = intval($db['hora_ingreso']);
            } else {
                $usuario_bloqueado = true;
                $hora_ingreso = 0;
            }
        } else {
            $password_correcto = false;
            $usuario_bloqueado = false;
        }
    } else {
        $password_correcto = false;
        $usuario_bloqueado = false;
    }
    
    echo json_encode(array("correo_existente"=>$correo_existente,"password_correcto"=>$password_correcto,"usuario_bloqueado"=>$usuario_bloqueado));
    DB::dbDisconnect();
?>
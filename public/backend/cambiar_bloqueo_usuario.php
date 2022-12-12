<?php
    require_once( './headers.php');
    require_once( './db.php' );

    if ( !is_null(Data::get("id_usuario")) ) {
        $faltan_datos = false;
        $query = "SELECT
            usuarios.usuario_bloqueado
        FROM
            usuarios
        WHERE
            usuarios.id_usuario = ?";
        $id_usuario = Data::get("id_usuario");
        $values = array($id_usuario);
        $sql = DB::runQuery( $query, $values );
        if ( $db = $sql->fetch_assoc() ) {
            $usuario_bloqueado_db = $db['usuario_bloqueado'];
            if ( $usuario_bloqueado_db == 1 ) {
                $usuario_bloqueado = 0;
            } else {
                $usuario_bloqueado = 1;
            }
        }

        $query = "UPDATE `usuarios` SET `usuario_bloqueado` = ? WHERE `id_usuario` = ?";
        $values = array($usuario_bloqueado, $id_usuario);
        $sql = DB::runQuery( $query, $values );
        if ( $sql ) {
            if ( $usuario_bloqueado == 1 ) {
                $usuario_bloqueado = true;
            } else {
                $usuario_bloqueado = false;
            }
        } else {
            $usuario_bloqueado = null;
        }
    } else {
        $faltan_datos = true;
        $usuario_bloqueado = null;
    }
    
    echo json_encode(array("faltan_datos"=>$faltan_datos,"usuario_bloqueado"=>$usuario_bloqueado));
    DB::dbDisconnect();
?>
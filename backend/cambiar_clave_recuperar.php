<?php
    require_once( './headers.php');
    require_once( './db.php' );

    if ( 
        !is_null(Data::get("correo")) && 
        !is_null(Data::get("usuario_clave"))
    ) {
        $faltan_datos = false;
        $query = "UPDATE `usuarios` SET `usuario_clave` = ? WHERE `correo` = ?";
        $values = array(password_hash(Data::get("usuario_clave"), PASSWORD_DEFAULT),Data::get("correo"));
        $sql = DB::runQuery( $query, $values );

        if ( $sql ) {
            $clave_cambiada = true;
        } else {
            $clave_cambiada = false;
        }

    } else {
        $faltan_datos = true;
        $clave_cambiada = false;
    }
    
    echo json_encode(array("faltan_datos"=>$faltan_datos,"clave_cambiada"=>$clave_cambiada));
    DB::dbDisconnect();
?>
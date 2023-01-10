<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $id_usuario = Data::get("id_usuario");
    if ( Data::get("estudiante_activo") == 1 ) {
        $query = "DELETE FROM `usuarios_roles` WHERE `fk_id_usuario` = ? AND `fk_id_rol` = ?";
        $values = [$id_usuario, 3];
    } else {
        $query = "INSERT INTO `usuarios_roles`(`fk_id_usuario`, `fk_id_rol`) VALUES (?, ?)";
        $values = [$id_usuario, 3];
    }
    $sql = DB::runQuery( $query, $values );
    DB::dbDisconnect();
?>
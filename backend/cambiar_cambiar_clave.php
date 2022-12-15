<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_usuario = Data::get('id_usuario');
    $cambiar_clave = Data::get('cambiar_clave');
    $query = 
    "UPDATE `usuarios` 
    SET `cambiar_clave` = ? 
    WHERE `id_usuario` = ?";
    $values = [$cambiar_clave,$id_usuario];
    $resultado = DB::runQuery( $query, $values );
?>
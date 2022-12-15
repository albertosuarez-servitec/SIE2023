<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_usuario = Data::get('id_usuario');
    $bloqueo = Data::get('bloqueo');
    $query = 
    "UPDATE `usuarios` 
    SET `bloqueo` = ? 
    WHERE `id_usuario` = ?";
    $values = [$bloqueo,$id_usuario];
    $resultado = DB::runQuery( $query, $values );
?>
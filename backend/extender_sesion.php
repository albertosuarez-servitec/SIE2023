<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $id_usuario = Data::get('id_usuario');
    $query = 
    "UPDATE `usuarios` 
     SET `hora_unix` = ? 
     WHERE `id_usuario` = ?";
    $values = [time(),$id_usuario];
    $resultado = DB::runQuery( $query, $values );
?>
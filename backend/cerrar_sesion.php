<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_usuario = Data::get('id_usuario');
    $query = 
    "UPDATE `usuarios` 
    SET `token` = ?, hora_unix = ? 
    WHERE `id_usuario` = ?";
    $values = ['',0,$id_usuario];
    $resultado = DB::runQuery( $query, $values );
?>
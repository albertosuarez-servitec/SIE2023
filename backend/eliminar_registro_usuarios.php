<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_usuario = Data::get('id_usuario');
    $query = 
    "DELETE FROM `usuarios` 
    WHERE `id_usuario` = ?";
    $values = [$id_usuario];
    $resultado = DB::runQuery( $query, $values );
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_perfil = Data::get('id_perfil');
    $query = 
    "DELETE FROM `perfiles` 
    WHERE `id_perfil` = ?";
    $values = [$id_perfil];
    $resultado = DB::runQuery( $query, $values );
?>
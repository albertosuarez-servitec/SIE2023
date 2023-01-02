<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_perfil = Data::get('id_perfil');
    $perfil_activo = Data::get('menu_mostrar');
    $query = 
    "UPDATE `perfiles` 
    SET `perfil_activo` = ? 
    WHERE `id_perfil` = ?";
    $values = [$perfil_activo,$id_perfil];
    $resultado = DB::runQuery( $query, $values );
?>
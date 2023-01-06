<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $id_perfil = Data::get('id_perfil');
    $perfil_nombre = Data::get('perfil_nombre');
    $perfil_descripcion = Data::get('perfil_descripcion');
    $query = 
    "UPDATE `perfiles` 
     SET `perfil_nombre` = ? , `perfil_descripcion` = ?
     WHERE `id_perfil` = ?";
    $values = [$perfil_nombre, $perfil_descripcion, $id_perfil];
    $resultado = DB::runQuery( $query, $values );
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $fk_id_usuario = Data::get('id_usuario');
    $fk_id_perfil = Data::get('id_perfil');
    $query = 
    "DELETE FROM `usuarios_perfiles` 
    WHERE `fk_id_usuario` = ? AND `fk_id_perfil` = ?";
    $values = [ $fk_id_usuario, $fk_id_perfil ];
    $resultado = DB::runQuery( $query, $values );
?>
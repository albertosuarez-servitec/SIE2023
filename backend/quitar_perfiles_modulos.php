<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $fk_id_perfil = Data::get('fk_id_perfil');
    $fk_id_modulo = Data::get('fk_id_modulo');
    $query = 
    "DELETE FROM `perfiles_modulos` WHERE `fk_id_perfil` = ? AND `fk_id_modulo` = ?";
    $values = [ $fk_id_perfil, $fk_id_modulo ];
    $resultado = DB::runQuery( $query, $values );
?>
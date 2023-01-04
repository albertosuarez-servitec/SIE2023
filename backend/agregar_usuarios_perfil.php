<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $fk_id_usuario = Data::get('fk_id_usuario');
    $fk_id_perfil = Data::get('fk_id_perfil');
    $query = 
    "INSERT INTO `usuarios_perfiles`(`fk_id_usuario`, `fk_id_perfil`) 
    VALUES (?, ?)";
    $values = [$fk_id_usuario,$fk_id_perfil];
    $resultado = DB::runQuery( $query, $values );
?>
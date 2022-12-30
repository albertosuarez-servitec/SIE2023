<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    
    $query="SELECT MAX(perfiles.perfil_orden)+1 as siguiente_orden FROM perfiles";
    $resultado = DB::runQuery( $query );
    $db = $resultado->fetch_assoc();
    $siguiente_orden = $db['siguiente_orden'];
    $perfil_nombre = Data::get('perfil_nombre');
    $query = 
    "INSERT INTO `perfiles`(`perfil_nombre`,`perfil_orden`,`perfil_activo`) 
    VALUES (?, ?, ?)";
    $values = [$perfil_nombre,$siguiente_orden,1];
    $resultado = DB::runQuery( $query, $values );
?>
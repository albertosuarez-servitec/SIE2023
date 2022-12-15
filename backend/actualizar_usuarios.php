<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $id_usuario = Data::get('id_usuario');
    $nombres = Data::get('nombres');
    $primer_apellido = Data::get('primer_apellido');
    $segundo_apellido = Data::get('segundo_apellido');
    $correo = Data::get('correo');
    $query = 
    "UPDATE `usuarios` 
     SET `nombres` = ?, `primer_apellido` = ?, `segundo_apellido` = ?, `correo` = ?
     WHERE `id_usuario` = ?";
    $values = [$nombres, $primer_apellido, $segundo_apellido, $correo, $id_usuario];
    $resultado = DB::runQuery( $query, $values );
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $id_usuario = Data::get('id_usuario');
    $nombres = Data::get('nombres');
    $primer_apellido = Data::get('primer_apellido');
    $segundo_apellido = Data::get('segundo_apellido');
    $correo = Data::get('correo');
    $codigo = Data::get('codigo');
    $query = 
    "UPDATE `usuarios` 
     SET `nombres` = ?, `primer_apellido` = ?, `segundo_apellido` = ?, `correo` = ?, `codigo`= ?
     WHERE `id_usuario` = ?";
    $values = [$nombres, $primer_apellido, $segundo_apellido, $correo, $id_usuario, $codigo];
    $resultado = DB::runQuery( $query, $values );
?>
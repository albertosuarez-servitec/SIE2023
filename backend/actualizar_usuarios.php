<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $codigo = Data::get('codigo');
    $correo = Data::get('correo');
    $id_usuario = Data::get('id_usuario');
    $nombres = Data::get('nombres');
    $primer_apellido = Data::get('primer_apellido');
    $segundo_apellido = Data::get('segundo_apellido');
    $query = 
    "UPDATE `usuarios` 
     SET `nombres` = ?, `primer_apellido` = ?, `segundo_apellido` = ?, `correo` = ?, `codigo`= ?
     WHERE `id_usuario` = ?";
    $values = [$nombres, $primer_apellido, $segundo_apellido, $correo, $codigo, $id_usuario];
    $resultado = DB::runQuery( $query, $values );
?>
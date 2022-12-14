<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $id_menu = Data::get('id_menu');
    $menu_nombre = Data::get('menu_nombre');
    $query = 
    "UPDATE `menu` 
     SET `menu_nombre` = ? 
     WHERE `id_menu` = ?";
    $values = [$menu_nombre, $id_menu];
    $resultado = DB::runQuery( $query, $values );
?>
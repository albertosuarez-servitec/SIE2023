<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_menu = Data::get('id_menu');
    $menu_activo = Data::get('menu_activo');
    $query = 
    "UPDATE `menu` 
    SET `menu_activo` = ? 
    WHERE `id_menu` = ?";
    $values = [$menu_activo,$id_menu];
    $resultado = DB::runQuery( $query, $values );
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_menu = Data::get('id_test');
    $menu_mostrar = Data::get('menu_mostrar');
    $query = 
    "UPDATE `menu` 
    SET `menu_mostrar` = ? 
    WHERE `id_test` = ?";
    $values = [$menu_mostrar,$id_menu];
    $resultado = DB::runQuery( $query, $values );
?>
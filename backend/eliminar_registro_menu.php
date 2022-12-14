<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_menu = Data::get('id_menu');
    $query = 
    "DELETE FROM `menu` 
    WHERE `id_menu` = ?";
    $values = [$id_menu];
    $resultado = DB::runQuery( $query, $values );
?>
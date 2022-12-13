<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_test = Data::get('id_test');
    $query = 
    "DELETE FROM `tests` 
    WHERE `id_test` = ?";
    $values = [$id_test];
    $resultado = DB::runQuery( $query, $values );
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_test = Data::get('id_test');
    $buleano = Data::get('buleano');
    $query = 
    "UPDATE `tests` 
    SET `buleano` = ? 
    WHERE `id_test` = ?";
    $values = [$buleano,$id_test];
    $resultado = DB::runQuery( $query, $values );
?>
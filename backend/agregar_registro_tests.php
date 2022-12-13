<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_test = Data::get('id_test');
    $texto = Data::get('texto');
    $entero = intval(Data::get('entero'));
    $query = 
    "INSERT INTO `tests`(`texto`, `entero`) 
    VALUES (?, ?)";
    $values = [$texto,$entero];
    $resultado = DB::runQuery( $query, $values );
?>
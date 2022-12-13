<?php
    require_once( './headers.php');
    require_once( './db.php' );
    $id_test = Data::get('id_test');
    $texto = Data::get('texto');
    $entero = intval(Data::get('entero'));
    $query = 
    "UPDATE `tests` 
     SET `texto` = ?, `entero` = ? 
     WHERE `id_test` = ?";
    $values = [$texto,$entero,$id_test];
    $resultado = DB::runQuery( $query, $values );
?>
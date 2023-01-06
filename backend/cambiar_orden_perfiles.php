<?php
    require_once( './headers.php');
    require_once( './db.php' );
    
    $id_perfil = Data::get('id_perfil');
    $perfil_orden = Data::get('perfil_orden');
    $direccion = Data::get('direccion');
    if ( $direccion == 'b' ) {
        $nuevo_orden = $perfil_orden + 1;
    } else {
        $nuevo_orden = $perfil_orden - 1;
    }
    
    $query = "SELECT perfiles.id_perfil FROM perfiles WHERE perfiles.perfil_orden = ?";
    $values = [ $nuevo_orden ];
    $resultado = DB::runQuery( $query, $values );
    $fila = $resultado->fetch_assoc();
    $id_nuevo = $fila['id_perfil'];
    $query = "UPDATE `perfiles` SET `perfil_orden` = ? WHERE `id_perfil` = ?";
    $values = [ $nuevo_orden, $id_perfil ];
    $resultado = DB::runQuery( $query, $values );
    $query = "UPDATE `perfiles` SET `perfil_orden` = ? WHERE `id_perfil` = ?";
    $values = [ $perfil_orden, $id_nuevo ];
    $resultado = DB::runQuery( $query, $values );
    ?>
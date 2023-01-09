<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $id_perfil = Data::get('id_perfil');
    $tieneModulos = false;
    $rsModulos = [];
    $query = 
    "SELECT
        perfiles_modulos.fk_id_perfil, 
        modulos.id_modulo, 
        modulos.modulo_nombre, 
        modulos.modulo_orden, 
        modulos.modulo_descripcion, 
        modulos.modulo_pagina,
        modulos.modulo_activo
    FROM
        perfiles_modulos
        INNER JOIN
        modulos
        ON 
            perfiles_modulos.fk_id_modulo = modulos.id_modulo
    WHERE
        perfiles_modulos.fk_id_perfil = ? AND
        modulos.modulo_activo = 1
    ORDER BY
        modulos.modulo_orden ASC";
    $values = [ $id_perfil ];
    $resultado = DB::runQuery( $query, $values );
    if ( $resultado->num_rows !== 0 ) {
        $tieneModulos = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsModulos[$i]['numero']              = $i;
            $rsModulos[$i]['fk_id_perfil']           = $fila['fk_id_perfil'];
            $rsModulos[$i]['id_modulo']           = $fila['id_modulo'];
            $rsModulos[$i]['modulo_nombre']       = utf8_decode(utf8_encode( $fila['modulo_nombre'] ));
            $rsModulos[$i]['modulo_descripcion']  = utf8_decode(utf8_encode( $fila['modulo_descripcion'] ));
            $rsModulos[$i]['modulo_orden']        = intval( $fila['modulo_orden'] );
            $i++;
        }
    }
    $tieneModulosDisponibles = false;
    $rsModulosDisponibles =  [];
    $query = 
    "SELECT
        modulos.id_modulo, 
        modulos.modulo_nombre, 
        modulos.modulo_orden, 
        modulos.modulo_descripcion, 
        modulos.modulo_pagina, 
        modulos.modulo_activo
    FROM
        modulos
    WHERE
        modulos.modulo_activo = 1 AND
        modulos.id_modulo NOT IN ((SELECT
        modulos.id_modulo
    FROM
        perfiles_modulos
        INNER JOIN
        modulos
        ON 
            perfiles_modulos.fk_id_modulo = modulos.id_modulo
    WHERE
        perfiles_modulos.fk_id_perfil = ? AND
        modulos.modulo_activo = 1))
    ORDER BY
        modulos.modulo_orden ASC";
    $values = [ $id_perfil ];
    $resultado = DB::runQuery( $query, $values );
    if ( $resultado->num_rows !== 0 ) {
        $tieneModulosDisponibles = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsModulosDisponibles[$i]['numero']              = $i;
            $rsModulosDisponibles[$i]['id_modulo']           = $fila['id_modulo'];
            $rsModulosDisponibles[$i]['modulo_nombre']       = utf8_decode(utf8_encode( $fila['modulo_nombre'] ));
            $rsModulosDisponibles[$i]['modulo_descripcion']  = utf8_decode(utf8_encode( $fila['modulo_descripcion'] ));
            $rsModulosDisponibles[$i]['modulo_orden']        = intval( $fila['modulo_orden'] );
            $i++;
        }
    }
    echo json_encode(array("tieneModulos"=>$tieneModulos,"rsModulos"=>$rsModulos,"tieneModulosDisponibles"=>$tieneModulosDisponibles,"rsModulosDisponibles"=>$rsModulosDisponibles))
?>
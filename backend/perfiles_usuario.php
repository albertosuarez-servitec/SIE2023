<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $filtro = Data::get('filtro');
    $id_usuario = Data::get('id_usuario');
    
    if ( $filtro != '' ) { 
        $query = 
        "SELECT
            perfiles.id_perfil, 
            perfiles.perfil_nombre, 
            perfiles.perfil_descripcion,
            perfiles.perfil_activo,
            perfiles.perfil_orden
        FROM
            usuarios
            INNER JOIN
            usuarios_perfiles
            ON 
                usuarios.id_usuario = usuarios_perfiles.fk_id_usuario
            INNER JOIN
            perfiles
            ON 
                usuarios_perfiles.fk_id_perfil = perfiles.id_perfil
        WHERE
            perfiles.perfil_activo = 0 AND
            usuarios.id_usuario = ? AND
            perfiles.perfil_nombre LIKE '%".$filtro."%'
        ORDER BY
            perfiles.perfil_orden ASC";
        $values = [ $id_usuario ];
        $resultado = DB::runQuery( $query, $values );
        
    } else {
        $query = 
        "SELECT
            perfiles.id_perfil, 
            perfiles.perfil_nombre, 
            perfiles.perfil_descripcion, 
            perfiles.perfil_orden
        FROM
            usuarios
            INNER JOIN
            usuarios_perfiles
            ON 
                usuarios.id_usuario = usuarios_perfiles.fk_id_usuario
            INNER JOIN
            perfiles
            ON 
                usuarios_perfiles.fk_id_perfil = perfiles.id_perfil
        WHERE
            perfiles.perfil_activo = 0 AND
            usuarios.id_usuario = ?
        ORDER BY
            perfiles.perfil_orden ASC";
        $values = [ $id_usuario ];
        $resultado = DB::runQuery( $query, $values );
    }

    $tienePerfiles = false;
    $rsPerfiles = [];
    $tieneModulos = false;
    $rsModulos = [];
    if ( $resultado->num_rows !== 0 ) {
        $tienePerfiles = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsPerfiles[$i]['numero']              = $i;
            $rsPerfiles[$i]['id_perfil']           = $fila['id_perfil'];
            $rsPerfiles[$i]['perfil_nombre']       = utf8_decode(utf8_encode( $fila['perfil_nombre'] ));
            $rsPerfiles[$i]['perfil_descripcion']  = utf8_decode(utf8_encode( $fila['perfil_descripcion'] ));
            $rsPerfiles[$i]['perfil_orden']              = intval( $fila['perfil_orden'] );
            $i++;
        }
        $query = 
        "SELECT
            perfiles.id_perfil, 
            modulos.id_modulo, 
            modulos.modulo_nombre, 
            modulos.modulo_orden, 
            modulos.modulo_titulo, 
            modulos.modulo_descripcion
        FROM
            usuarios
            INNER JOIN
            usuarios_perfiles
            ON 
                usuarios.id_usuario = usuarios_perfiles.fk_id_usuario
            INNER JOIN
            perfiles
            ON 
                usuarios_perfiles.fk_id_perfil = perfiles.id_perfil
            INNER JOIN
            perfiles_modulos
            ON 
                perfiles.id_perfil = perfiles_modulos.fk_id_perfil
            INNER JOIN
            modulos
            ON 
                perfiles_modulos.fk_id_modulo = modulos.id_modulo
        WHERE
            perfiles.perfil_activo = 0 AND
            usuarios.id_usuario = ? AND
            modulos.modulo_activo = 1
        ORDER BY
            perfiles.perfil_orden ASC, 
            modulos.modulo_orden ASC";
        $values = [ $id_usuario ];
        $resultado = DB::runQuery( $query, $values );
        if ( $resultado->num_rows !== 0 ) {
            $tieneModulos = true;
            $i=1;
            while ($fila = $resultado->fetch_assoc()) {
                $rsModulos[$i]['numero']              = $i;
                $rsModulos[$i]['id_perfil']           = $fila['id_perfil'];
                $rsModulos[$i]['id_modulo']           = $fila['id_modulo'];
                $rsModulos[$i]['modulo_nombre']       = utf8_decode(utf8_encode( $fila['modulo_nombre'] ));
                $rsModulos[$i]['modulo_descripcion']  = utf8_decode(utf8_encode( $fila['modulo_descripcion'] ));
                $rsModulos[$i]['modulo_orden']        = intval( $fila['modulo_orden'] );
                $i++;
            }
        }
    }
    echo json_encode(array("tienePerfiles"=>$tienePerfiles,"rsPerfiles"=>$rsPerfiles,"tieneModulos"=>$tieneModulos,"rsModulos"=>$rsModulos))
?>
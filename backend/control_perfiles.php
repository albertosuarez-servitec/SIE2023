<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $filtro = Data::get('filtro');
    
    if ( $filtro != '' ) { 
        $query = 
        "SELECT
            perfiles.id_perfil, 
            perfiles.perfil_nombre, 
            perfiles.perfil_descripcion, 
            perfiles.perfil_activo,
            perfiles.perfil_orden
        FROM
            perfiles WHERE perfiles.perfil_nombre LIKE '%".$filtro."%'
        ORDER BY 
            perfil_orden ASC";
        $resultado = DB::runQuery( $query );
    } else {
        $query = 
        "SELECT
        perfiles.id_perfil, 
        perfiles.perfil_nombre, 
        perfiles.perfil_descripcion, 
        perfiles.perfil_activo,
        perfiles.perfil_orden
    FROM
        perfiles
    ORDER BY 
        perfil_orden ASC";
        $resultado = DB::runQuery( $query );
    }

    $tienePerfiles = false;
    $rsPerfiles = [];
    if ( $resultado->num_rows !== 0 ) {
        $tienePerfiles = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsPerfiles[$i]['numero']              = $i;
            $rsPerfiles[$i]['id_perfil']           = $fila['id_perfil'];
            $rsPerfiles[$i]['perfil_nombre']       = utf8_decode(utf8_encode( $fila['perfil_nombre'] ));
            $rsPerfiles[$i]['perfil_descripcion']  = utf8_decode(utf8_encode( $fila['perfil_descripcion'] ));
            $rsPerfiles[$i]['perfil_activo']      = entero2buleano( $fila['perfil_activo'] );
            $rsPerfiles[$i]['perfil_orden']          = intval( $fila['perfil_orden'] );
            $i++;
        }
        $query = "SELECT MIN(perfiles.perfil_orden) as minimo_orden, MAX(perfiles.perfil_orden) as maximo_orden FROM perfiles";
        $resultado = DB::runQuery( $query );
        $db = $resultado->fetch_assoc();
        $minimo_orden = intval($db['minimo_orden']);
        $maximo_orden = intval($db['maximo_orden']);
    }
    echo json_encode(array("tienePerfiles"=>$tienePerfiles,"rsPerfiles"=>$rsPerfiles,"minimo_orden"=>$minimo_orden,"maximo_orden"=>$maximo_orden))
?>
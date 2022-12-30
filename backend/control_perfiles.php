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
            perfiles.perfil_bloqueo,
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
        perfiles.perfil_bloqueo,
        perfiles.perfil_orden
    FROM
        perfiles
    ORDER BY 
        perfil_orden ASC";
        $resultado = DB::runQuery( $query );
    }

    $tieneRegistros = false;
    $rsRegistros = [];
    if ( $resultado->num_rows !== 0 ) {
        $tieneRegistros = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsRegistros[$i]['numero']              = $i;
            $rsRegistros[$i]['id_perfil']           = $fila['id_perfil'];
            $rsRegistros[$i]['perfil_nombre']       = utf8_decode(utf8_encode( $fila['perfil_nombre'] ));
            $rsRegistros[$i]['perfil_descripcion']  = utf8_decode(utf8_encode( $fila['perfil_descripcion'] ));
            $rsRegistros[$i]['perfil_bloqueo']      = entero2buleano( $fila['perfil_bloqueo'] );
            $rsRegistros[$i]['menu_orden']          = intval( $fila['menu_orden'] );
            $i++;
        }
        $query = "SELECT MIN(perfil.perfil_orden) as minimo_orden, MAX(perfil.perfil_orden) as maximo_orden FROM perfil";
        $resultado = DB::runQuery( $query );
        $db = $resultado->fetch_assoc();
        $minimo_orden = intval($db['minimo_orden']);
        $maximo_orden = intval($db['maximo_orden']);
    }
    echo json_encode(array("tieneRegistros"=>$tieneRegistros,"rsRegistros"=>$rsRegistros,"minimo_orden"=>$minimo_orden,"maximo_orden"=>$maximo_orden))
?>
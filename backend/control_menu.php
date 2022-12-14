<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $filtro = Data::get('filtro');
    
    if ( $filtro != '' ) { 
        $query = "SELECT
            menu.id_menu, 
            menu.menu_destino, 
            menu.menu_nombre, 
            menu.menu_tiene_submenu, 
            menu.menu_orden, 
            menu.menu_activo, 
            menu.menu_ruta, 
            menu.menu_mostrar
        FROM
            menu WHERE menu.menu_nombre LIKE '%".$filtro."%'
        ORDER BY 
            menu_orden ASC";
        $resultado = DB::runQuery( $query );
    } else {
        $query = "SELECT
            menu.id_menu, 
            menu.menu_destino, 
            menu.menu_nombre, 
            menu.menu_tiene_submenu, 
            menu.menu_orden, 
            menu.menu_activo, 
            menu.menu_ruta, 
            menu.menu_mostrar
        FROM
            menu
        ORDER BY 
            menu_orden ASC";
        $resultado = DB::runQuery( $query );
    }

    $tieneRegistros = false;
    $rsRegistros = [];
    if ( $resultado->num_rows !== 0 ) {
        $tieneRegistros = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsRegistros[$i]['numero']              = $i;
            $rsRegistros[$i]['id_menu']             = $fila['id_menu'];
            $rsRegistros[$i]['menu_destino']        = utf8_decode(utf8_encode( $fila['menu_destino'] ));
            $rsRegistros[$i]['menu_nombre']         = utf8_decode(utf8_encode( $fila['menu_nombre'] ));
            $rsRegistros[$i]['menu_tiene_submenu']  = entero2buleano( $fila['menu_tiene_submenu'] );
            $rsRegistros[$i]['menu_orden']          = intval( $fila['menu_orden'] );
            $rsRegistros[$i]['menu_activo']         = entero2buleano( $fila['menu_activo'] );
            $rsRegistros[$i]['menu_ruta']           = $fila['menu_ruta'];
            $rsRegistros[$i]['menu_mostrar']        = entero2buleano( $fila['menu_mostrar'] );
            $i++;
        }
        $query = "SELECT MIN(menu.menu_orden) as minimo_orden, MAX(menu.menu_orden) as maximo_orden FROM menu";
        $resultado = DB::runQuery( $query );
        $db = $resultado->fetch_assoc();
        $minimo_orden = $db['minimo_orden'];
        $maximo_orden = $db['maximo_orden'];
    }
    echo json_encode(array("tieneRegistros"=>$tieneRegistros,"rsRegistros"=>$rsRegistros,"minimo_orden"=>$minimo_orden,"maximo_orden"=>$maximo_orden))
?>
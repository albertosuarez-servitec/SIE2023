<?php
    require_once( './headers.php');
    require_once( './db.php' );
    
    $id_menu = Data::get('id_menu');
    $menu_orden = Data::get('menu_orden');
    $direccion = Data::get('direccion');
    if ( $direccion == 'b' ) {
        $nuevo_orden = $menu_orden + 1;
    } else {
        $nuevo_orden = $menu_orden - 1;
    }
    
    $query = "SELECT menu.id_menu FROM menu WHERE menu.menu_orden = ?";
    $values = [ $nuevo_orden ];
    $resultado = DB::runQuery( $query, $values );
    $fila = $resultado->fetch_assoc();
    $id_nuevo = $fila['id_menu'];
    $query = "UPDATE `menu` SET `menu_orden` = ? WHERE `id_menu` = ?";
    $values = [ $nuevo_orden, $id_menu ];
    $resultado = DB::runQuery( $query, $values );
    $query = "UPDATE `menu` SET `menu_orden` = ? WHERE `id_menu` = ?";
    $values = [ $menu_orden, $id_nuevo ];
    $resultado = DB::runQuery( $query, $values );
    ?>
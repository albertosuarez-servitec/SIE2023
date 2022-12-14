<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    
    $query="SELECT MAX(menu.menu_orden)+1 as siguiente_orden FROM menu";
    $resultado = DB::runQuery( $query );
    $db = $resultado->fetch_assoc();
    $siguiente_orden = $db['siguiente_orden'];
    $menu_nombre = Data::get('menu_nombre');
    $menu_destino = str_replace(' ','',$menu_nombre);
    $menu_ruta = '/'.$menu_destino;
    $query = 
    "INSERT INTO `menu`(`menu_nombre`, `menu_destino`, `menu_tiene_submenu`,`menu_orden`,`menu_activo`,`menu_ruta`,`menu_mostrar`) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $values = [$menu_nombre,$menu_destino,0,$siguiente_orden,1,$menu_ruta,1];
    $resultado = DB::runQuery( $query, $values );
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $query = 
    "SELECT
        banners.id_banner, 
        banners.banner_nombre, 
        banners.banner_titulo, 
        banners.banner_descripcion, 
        banners.banner_imagen, 
        banners.banner_orden, 
        banners.banner_activo
    FROM
        banners
    WHERE
        banners.banner_activo = 1
    ORDER BY
        banners.banner_orden ASC";
    $resultado = DB::runQuery( $query );
    
    $tieneRegistros = false;
    $rsRegistros = [];
    if ( $resultado->num_rows !== 0 ) {
        $tieneRegistros = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsRegistros[$i]['numero']              = $i;
            $rsRegistros[$i]['id_banner']           = $fila['id_banner'];
            $rsRegistros[$i]['banner_nombre']       = utf8_decode(utf8_encode( $fila['banner_nombre'] ));
            $rsRegistros[$i]['banner_titulo']       = utf8_decode(utf8_encode( $fila['banner_titulo'] ));
            $rsRegistros[$i]['banner_descripcion']  = utf8_decode(utf8_encode( $fila['banner_descripcion'] ));
            $rsRegistros[$i]['banner_imagen']       = $fila['banner_imagen'];
            $rsRegistros[$i]['banner_orden']        = intval( $fila['banner_orden'] );
            $rsRegistros[$i]['banner_activo']         = entero2buleano( $fila['banner_activo'] );
            $i++;
        }
    }
    echo json_encode(array("tieneRegistros"=>$tieneRegistros,"rsRegistros"=>$rsRegistros))
?>
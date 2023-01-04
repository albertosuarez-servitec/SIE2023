<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    // $filtro = Data::get('filtro');
    
    $query = 
        "SELECT
        usuarios_perfiles.fk_id_usuario, 
        perfiles.id_perfil, 
        perfiles.perfil_nombre, 
        perfiles.perfil_descripcion, 
        perfiles.perfil_activo, 
        perfiles.perfil_orden
    FROM
        usuarios_perfiles
        INNER JOIN
        perfiles
        ON 
            usuarios_perfiles.fk_id_perfil = perfiles.id_perfil
    WHERE
        perfiles.perfil_activo = 1 AND
        usuarios_perfiles.fk_id_usuario = 1
    ORDER BY
        perfiles.perfil_orden ASC";
        $resultado = DB::runQuery( $query );

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
    }
    echo json_encode(array("tienePerfiles"=>$tienePerfiles,"rsPerfiles"=>$rsPerfiles))
?>
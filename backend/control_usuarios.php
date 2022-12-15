<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $filtro = Data::get('filtro');
    
    if ( $filtro != '' ) { 
        $query = "SELECT
            usuarios.id_usuario, 
            usuarios.nombres, 
            usuarios.primer_apellido, 
            usuarios.segundo_apellido, 
            usuarios.usuario, 
            usuarios.correo, 
            usuarios.cambiar_clave, 
            usuarios.codigo, 
            usuarios.hora_unix, 
            usuarios.bloqueo
        FROM
            usuarios 
        WHERE usuarios.nombres LIKE '%".$filtro."%' OR
            usuarios.primer_apellido LIKE '%".$filtro."%' OR
            usuarios.segundo_apellido LIKE '%".$filtro."%' 
        ORDER BY
            usuarios.nombres ASC, 
            usuarios.primer_apellido ASC, 
            usuarios.segundo_apellido ASC";
        $resultado = DB::runQuery( $query );
        
    } else {
        $query = "SELECT
            usuarios.id_usuario, 
            usuarios.nombres, 
            usuarios.primer_apellido, 
            usuarios.segundo_apellido, 
            usuarios.usuario, 
            usuarios.correo, 
            usuarios.cambiar_clave, 
            usuarios.codigo, 
            usuarios.hora_unix, 
            usuarios.bloqueo
        FROM
            usuarios
        ORDER BY
            usuarios.nombres ASC, 
            usuarios.primer_apellido ASC, 
            usuarios.segundo_apellido ASC";
        $resultado = DB::runQuery( $query );
    }

    $tieneRegistros = false;
    $rsRegistros = [];
    if ( $resultado->num_rows !== 0 ) {
        $tieneRegistros = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsRegistros[$i]['numero']              = $i;
            $rsRegistros[$i]['id_usuario']          = $fila['id_usuario'];
            $rsRegistros[$i]['nombres']             = utf8_decode(utf8_encode( $fila['nombres'] ));
            $rsRegistros[$i]['primer_apellido']     = utf8_decode(utf8_encode( $fila['primer_apellido'] ));
            $rsRegistros[$i]['segundo_apellido']    = utf8_decode(utf8_encode( $fila['segundo_apellido'] ));
            $rsRegistros[$i]['usuario']             = $fila['usuario'];
            $rsRegistros[$i]['correo']              = $fila['correo'];
            $rsRegistros[$i]['cambiar_clave']       = entero2buleano( $fila['cambiar_clave'] );
            $rsRegistros[$i]['codigo']              = intval( $fila['codigo'] );
            $rsRegistros[$i]['hora_unix']           = intval( $fila['hora_unix'] );
            $rsRegistros[$i]['bloqueo']             = entero2buleano( $fila['bloqueo'] );
            $i++;
        }
    }
    echo json_encode(array("tieneRegistros"=>$tieneRegistros,"rsRegistros"=>$rsRegistros))
?>
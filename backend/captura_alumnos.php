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

    $query = 
    "SELECT
	usuarios.id_usuario, 
	IF ( a.fk_id_rol = 1, (SELECT roles.rol_imagen_si FROM roles WHERE roles.id_rol = 1), (SELECT roles.rol_imagen_no FROM roles WHERE roles.id_rol = 1)) AS administrativo, 
	IF ( d.fk_id_rol = 2, (SELECT roles.rol_imagen_si FROM roles WHERE roles.id_rol = 2), (SELECT roles.rol_imagen_no FROM roles WHERE roles.id_rol = 2)) AS docente, 
	IF ( e.fk_id_rol = 3, (SELECT roles.rol_imagen_si FROM roles WHERE roles.id_rol = 3), (SELECT roles.rol_imagen_no FROM roles WHERE roles.id_rol = 3)) AS estudiante, 
	IF ( ISNULL(a.fk_id_rol), 0, 1) AS administrativo_activo, 
	IF ( ISNULL(d.fk_id_rol), 0, 1) AS docente_activo, 
	IF ( ISNULL(e.fk_id_rol), 0, 1) AS estudiante_activo
FROM
	usuarios
	LEFT JOIN
	usuarios_roles AS a
	ON 
		usuarios.id_usuario = a.fk_id_usuario AND
		a.fk_id_rol = 1
	LEFT JOIN
	usuarios_roles AS d
	ON 
		usuarios.id_usuario = d.fk_id_usuario AND
		d.fk_id_rol = 2
	LEFT JOIN
	usuarios_roles AS e
	ON 
		usuarios.id_usuario = e.fk_id_usuario AND
		e.fk_id_rol = 3
	LEFT JOIN
	roles
	ON 
		a.fk_id_rol = roles.id_rol AND
		d.fk_id_rol = roles.id_rol AND
		e.fk_id_rol = roles.id_rol";
        $resultado = DB::runQuery( $query );
    $tieneRoles = false;
    $rsRoles = [];
    if ( $resultado->num_rows !== 0 ) {
        $tieneRoles = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsRoles[$i]['numero']                  = $i;
            $rsRoles[$i]['id_usuario']              = $fila['id_usuario'];
            $rsRoles[$i]['administrativo']          = $fila['administrativo'];
            $rsRoles[$i]['docente']                 = $fila['docente'];
            $rsRoles[$i]['estudiante']              = $fila['estudiante'];
            if ( $fila['administrativo_activo'] == 1 ) { $rsRoles[$i]['administrativo_activo']   = true; } else { $rsRoles[$i]['administrativo_activo']   = false; }
            if ( $fila['docente_activo'] == 1 ) { $rsRoles[$i]['docente_activo']   = true; } else { $rsRoles[$i]['docente_activo']   = false; }
            if ( $fila['estudiante_activo'] == 1 ) { $rsRoles[$i]['estudiante_activo']   = true; } else { $rsRoles[$i]['estudiante_activo']   = false; }
            $i++;
        }
    }
    
    echo json_encode(array("tieneRegistros"=>$tieneRegistros,"rsRegistros"=>$rsRegistros,"tieneRoles"=>$tieneRoles,"rsRoles"=>$rsRoles))
?>
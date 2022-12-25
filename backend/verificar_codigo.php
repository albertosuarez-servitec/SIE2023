<?php
    require_once( './headers.php');
    require_once( './db.php' );

    $codigo_correcto = false;
    if ( !is_null(Data::get("correo")) && !is_null(Data::get("codigo")) ) {
        $faltan_datos = false;
        $correo = Data::get("correo");
        $codigo = Data::get("codigo");
        $query = 
        "SELECT
            usuarios.id_usuario
        FROM
            usuarios
        WHERE
            usuarios.correo = ? AND
            usuarios.codigo = ?";
        
        $values = [$correo, $codigo];
        $sql = DB::runQuery( $query, $values );
        if ( $db = $sql->fetch_assoc() ) {
            $codigo_correcto = true;
        }
    } else {
        $faltan_datos = true;
    }
    
    echo json_encode(array("faltan_datos"=>$faltan_datos,"codigo_correcto"=>$codigo_correcto));
    DB::dbDisconnect();
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    function entero2buleano( $dato ) {
        if ( $dato == 0 ) {
            return false;
        } else {
            return true;
        }
    }
    $valor = Data::get('valor');
    $query = 
    "SELECT
        tests.id_test, 
        tests.texto, 
        tests.entero, 
        tests.buleano
    FROM
        tests";
    $tieneRegistros = false;
    $rsRegistros = [];
    $resultado = DB::runQuery( $query );
    if ( $resultado->num_rows !== 0 ) {
        $tieneRegistros = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsRegistros[$i]['numero']=$i;
            $rsRegistros[$i]['id_test']=$fila['id_test'];
            $rsRegistros[$i]['texto']=utf8_decode($fila['texto']);
            $rsRegistros[$i]['entero']=intval($fila['entero']);
            $rsRegistros[$i]['buleano']=entero2buleano($fila['buleano']);
            $i++;
        }
    }
    echo json_encode(array("valor"=>$valor,"tieneRegistros"=>$tieneRegistros,"rsRegistros"=>$rsRegistros))
?>
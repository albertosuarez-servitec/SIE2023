<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './utiles.php');
    $filtro = Data::get('filtro');
    
    if ( $filtro != '' ) {
        $query = "SELECT tests.id_test, tests.texto, tests.entero, tests.buleano FROM tests WHERE tests.texto LIKE '%".$filtro."%'";
        $resultado = DB::runQuery( $query );
    } else {
        $query = "SELECT tests.id_test, tests.texto, tests.entero, tests.buleano FROM tests";
        $resultado = DB::runQuery( $query );
    }

    $tieneRegistros = false;
    $rsRegistros = [];
    if ( $resultado->num_rows !== 0 ) {
        $tieneRegistros = true;
        $i=1;
        while ($fila = $resultado->fetch_assoc()) {
            $rsRegistros[$i]['numero']=$i;
            $rsRegistros[$i]['id_test']=$fila['id_test'];
            $rsRegistros[$i]['texto']=utf8_decode(utf8_encode($fila['texto']));
            $rsRegistros[$i]['entero']=intval($fila['entero']);
            $rsRegistros[$i]['buleano']=entero2buleano($fila['buleano']);
            $i++;
        }
    }
    echo json_encode(array("tieneRegistros"=>$tieneRegistros,"rsRegistros"=>$rsRegistros))
?>
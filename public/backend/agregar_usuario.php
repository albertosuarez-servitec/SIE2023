<?php
    require_once( './headers.php');
    require_once( './db.php' );

    if ( 
        !is_null(Data::get("usuario_correo")) && 
        !is_null(Data::get("usuario_clave")) &&
        !is_null(Data::get("usuario_nombre")) &&
        !is_null(Data::get("usuario_primer_apellido"))
    ) {
        $faltan_datos = false;
        $question_times = 4;
        $query = "INSERT INTO ";
        $table = "`usuarios`";
        $fields = "`usuario_nombre`, `usuario_primer_apellido`, `usuario_correo`, `usuario_clave`";
        $values = array(Data::get("usuario_nombre"),Data::get("usuario_primer_apellido"),Data::get("usuario_correo"),password_hash(Data::get("usuario_clave"), PASSWORD_DEFAULT));

        if ( !is_null(Data::get("usuario_segundo_apellido")) ) { $question_times += 1; $fields = $fields.",`usuario_segundo_apellido`"; array_push($values,Data::get("usuario_segundo_apellido"));}
        if ( !is_null(Data::get("fk_id_perfil")) ) { $question_times += 1; $fields = $fields.",`fk_id_perfil`"; array_push($values,Data::get("fk_id_perfil"));}
        if ( !is_null(Data::get("fk_id_area")) ) { $question_times += 1; $fields = $fields.",`fk_id_area`"; array_push($values,Data::get("fk_id_area"));}
        if ( !is_null(Data::get("fk_id_tipo_contratacion")) ) { $question_times += 1; $fields = $fields.",`fk_id_tipo_contratacion`"; array_push($values,Data::get("fk_id_tipo_contratacion"));}
        if ( !is_null(Data::get("fk_id_tipo_cargo")) ) { $question_times += 1; $fields = $fields.",`fk_id_tipo_cargo`"; array_push($values,Data::get("fk_id_tipo_cargo"));}
        if ( !is_null(Data::get("fk_id_clave_puesto")) ) { $question_times += 1; $fields = $fields.",`fk_id_clave_puesto`"; array_push($values,Data::get("fk_id_clave_puesto"));}
        if ( !is_null(Data::get("usuario_bloqueado")) ) { $question_times += 1; $fields = $fields.",`usuario_bloqueado`"; array_push($values,Data::get("usuario_bloqueado"));}
        if ( !is_null(Data::get("fk_id_tipo_persona")) ) { $question_times += 1; $fields = $fields.",`fk_id_tipo_persona`"; array_push($values,Data::get("fk_id_tipo_persona"));}
        if ( !is_null(Data::get("curp")) ) { $question_times += 1; $fields = $fields.",`curp`"; array_push($values,Data::get("curp"));}
        if ( !is_null(Data::get("rfc")) ) { $question_times += 1; $fields = $fields.",`rfc`"; array_push($values,Data::get("rfc"));}
        if ( !is_null(Data::get("fk_id_estado_civil")) ) { $question_times += 1; $fields = $fields.",`fk_id_estado_civil`"; array_push($values,Data::get("fk_id_estado_civil"));}
        if ( !is_null(Data::get("calle")) ) { $question_times += 1; $fields = $fields.",`calle`"; array_push($values,Data::get("calle"));}
        if ( !is_null(Data::get("numero_exterior")) ) { $question_times += 1; $fields = $fields.",`numero_exterior`"; array_push($values,Data::get("numero_exterior"));}
        if ( !is_null(Data::get("numero_interior")) ) { $question_times += 1; $fields = $fields.",`numero_interior`"; array_push($values,Data::get("numero_interior"));}
        if ( !is_null(Data::get("colonia")) ) { $question_times += 1; $fields = $fields.",`colonia`"; array_push($values,Data::get("colonia"));}
        if ( !is_null(Data::get("municipio")) ) { $question_times += 1; $fields = $fields.",`municipio`"; array_push($values,Data::get("municipio"));}
        if ( !is_null(Data::get("codigo_postal")) ) { $question_times += 1; $fields = $fields.",`codigo_postal`"; array_push($values,Data::get("codigo_postal"));}
        if ( !is_null(Data::get("fk_id_entidad_federativa")) ) { $question_times += 1; $fields = $fields.",`fk_id_entidad_federativa`"; array_push($values,Data::get("fk_id_entidad_federativa"));}
        if ( !is_null(Data::get("fk_id_genero")) ) { $question_times += 1; $fields = $fields.",`fk_id_genero`"; array_push($values,Data::get("fk_id_genero"));}
        if ( !is_null(Data::get("fk_id_nacionalidad")) ) { $question_times += 1; $fields = $fields.",`fk_id_nacionalidad`"; array_push($values,Data::get("fk_id_nacionalidad"));}
        $questions = str_repeat('?,',$question_times);
        $questions = substr($questions, 0, -1);
        $query = $query.$table."(".$fields.") VALUES (".$questions.")";

        $sql = DB::runQuery( $query, $values );

        if ( $sql ) {
            $usuario_agregado = true;
        } else {
            $usuario_agregado = false;
        }

    } else {
        $faltan_datos = true;
        $usuario_agregado = false;
    }
    
    echo json_encode(array("faltan_datos"=>$faltan_datos,"usuario_agregado"=>$usuario_agregado));
    DB::dbDisconnect();
?>
<?php
    require_once( './headers.php');
    require_once( './db.php' );
    require_once( './mail.php' );
    require_once( './utiles.php' );
    $correo = Data::get('correo');
    $query = "SELECT usuarios.id_usuario, usuarios.nombres, usuarios.primer_apellido, usuarios.segundo_apellido FROM usuarios WHERE usuarios.correo = ?";
    $values = [$correo];
    $resultado = DB::runQuery( $query, $values );
    $nuevoCodigo = generarCodigo(6);
    if ( $db = $resultado->fetch_assoc() ) {
        $nombreCompleto = $db['nombres']." ".$db['primer_apellido']." ".$db['segundo_apellido'];
        // $mail->addAddress($correo, utf8_decode($nombreCompleto));
        // $mail->Subject = utf8_decode('Código temporal');
        // $mail->Body = utf8_decode('<!DOCTYPE html>
        // <html lang="en">
        // <head>
        //     <meta charset="UTF-8">
        //     <meta http-equiv="X-UA-Compatible" content="IE=edge">
        //     <meta name="viewport" content="width=device-width, initial-scale=1.0">
        //     <title>Código temporal</title>
        // </head>
        // <body>
        //     <table style="border: solid 1px black;max-width:600px;">
        //         <thead>
        //             <tr style="background-color: #007bff; color: #f8f9fa;">
        //                 <th>
        //                     <h2 style="padding: 10px !important;margin:0px;">ENCRyM</h2>
        //                 </th>
        //             </tr>
        //         </thead>
        //         <tbody style="background-color:#f7ede0;color: #343a40;">
        //             <tr>
        //                 <td style="text-align:center;background-color: #6c757d;color:#f8f9fa;">
        //                     <h4><strong>CÓDIGO TEMPORAL</strong></h4>
        //                 </td>
        //             </tr>
        //             <tr>
        //                 <td style="text-align:left;padding: 10px;">
        //                     <p>A petición suya o de alguien más se ha generado un código temporal para su cuenta en el sistema SIENCRyM, si cree que esto es un error ignore este correo, nadie tiene acceso a su cuenta.</p>
        //                     <p>El código temporal es el siguiente:</p>
        //                     <p style="font-size:2rem;text-align:center;"><strong><span>'.$nuevoCodigo.'</span></strong></p>
        //                     <p>Le recomendamos no compartirlo.</p>
        //                     <p><strong>Atentamente,</strong></p>
        //                     <p><strong>Departamento de Informática de la ENCRyM</strong></p>
        //                     <p><strong>Ext. 412109</strong></p>
        //                 </td>
        //             </tr>
        //             <tr style="background-color: #343a40; color: #f8f9fa;">
        //                 <th>
        //                     <h5 style="padding: 10px !important;margin:0px;">Para cualquier aclaración, comentario o duda puede escribirnos a <span style="color:#f8f9fa !important;">mesadeayuda@encrym.edu.mx</span></h5>
        //                 </th>
        //             </tr>
        //         </tbody>
        //     </table>
        // </body>
        // </html>');
        // if (!$mail->send()) {
        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
        // } else {
        //     // Todo bien
        // }
    }

    $query = 
    "UPDATE `usuarios` 
     SET `codigo` = ?
     WHERE `correo` = ?";
    $values = [$nuevoCodigo,$correo];
    $resultado = DB::runQuery( $query, $values );
?>
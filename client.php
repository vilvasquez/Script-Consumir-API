<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $argv[1]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

//poner los errorses http , podemos ponerlos todos los que existen  
switch ( $httpCode ) {
    case 200:
        echo 'Todo Bien!';
        break;
    case 400:
        echo 'Tienes un error en la peticion';
        break;
    case 404:
        echo 'Recurso no encontrado';
        break;
    case 500:
        echo 'Falló el servidor';
        break;
}
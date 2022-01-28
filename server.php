<?php

// AUTENTIFICACION DE USUARIOPOR HTTP (Poco segura ya que los datos viajan por en enlace)

//$user = array_key_exists('PHP_AUTH_USER',$_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '' ;
//$pwd = array_key_exists('PHP_AUTH_PW',$_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '' ;
//if ($user !== 'walther' || $pwd !== '1234') {
//    die;
//}


// AUTENTIFICACION VIA HMAC
/*if (
    !array_key_exists('HTTP_X_HASH', $_SERVER) ||
    !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ||
    !array_key_exists('HTTP_X_UID', $_SERVER) 
 ) {
    die;
   }

   // AQUI LO QUE HACEMOS ES COMPARAR QUE COOINCIDAN LAS CABECERAS HASH ,UID, TIMESTAMP
list( $hash,$uid,$timestamp)= [
    $_SERVER['HTTP_X_HASH'],
    $_SERVER['HTTP_X_UID'],
    $_SERVER['HTTP_X_TIMESTAMP'],
];

$secret = '12345estaeslaclave';
$newHash = sha1($uid.$timestamp.$secret);

if ($newHash !== $hash) {
    die;
}*/


// AUTENTIFICACION VIA TOKEN  ES MAS SEGURA

/*if ( !array_key_exists('HTTP_X_TOKEN',$SERVER)){
    die;
}

$url='http://localhost:8001';

$ch =curl_init($url);
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER, [
        "X-Token: {$_SERVER['HTTP_X_TOKEN']}"
    ]
    );
curl_setopt(
     $ch,
     CURLOPT_RETURNTRANSFER ,
      true
    );

    $ret =curl_exec($ch);

    if ($ret !== 'true'){

        die;
    }  */

//Definimos los Recursos disponibles
header( 'Content-Type: application/json' );
$allowedResourceTypes = [

    'books',
    'authors',
    'genres',
];

//Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];
if ( !in_array( $resourceType, $allowedResourceTypes ) ) {
	header( 'Status-Code: 400' );
	echo json_encode(
		[
			'error' => "$resourceType is un unkown",
		]
	);
	
	die;
}

//Defino Recursos

$books = [

    1=>[
        'titulo' => 'Lo que el viento se llevo',
        'id_autor' => 2,
        'id_genero' => 2,
        
    ],
    2=>[
        'titulo' => 'Las 40 leyes del poder',
        'id_autor' => 2,
        'id_genero' => 2,
        
    ],
    3=>[
        'titulo' => 'La iliada',
        'id_autor' => 2,
        'id_genero' => 2,
        
    ],
];

header('Content-Type: application/json');

// Levantamos el ID del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] :'';

//Generamos la respuesta asumiendo que el pedido es correcto

switch (strtoupper( $_SERVER['REQUEST_METHOD'])) {

// OBTENER RECURSOS DE LA BASE DE DATOS 
    case 'GET':
        if (empty($resourceId)){
            echo json_encode($books);
        } else {
            if (array_key_exists($resourceId,$books)){
                echo json_encode($books[$resourceId]);
            }
            http_response_code(404);
        }

  // Permitir a otros a ingresar datos a nuestra aplicacion EN LA BASE DE DATOS (ingreso otro libro)
      break;
    case 'POST':
        $json = file_get_contents('php://input');

        //transformamos el json reciibido a un nuevo elementp del 
        $books[]= json_decode($json,true);

       echo array_keys($books)[count($books) -1 ];
       // echo json_encode($books);
      break;


      // permitir que otraS APLICACIONES  modifiquen recursos EN LA DB (hace un remplazo de los recursos) 
    case 'PUT':
        //Validamos que el recurso buscado exista
        if (!empty($resourceId)&& array_key_exists($resourceId,$books)){
            //tomamos la entrada cruda
            $json = file_get_contents('php://input');
            //transformamos el json recibido a un nuevo elemento  de la 
            $books[$resourceId]= json_decode($json, true);
            //Retornamos la coleccion modificada en formato json
            echo json_encode($books);
        }
        break;

       // BORRAMOS EL RECURSO DE LA DB
    case 'DELETE':
        // validamos que el recurso exista
        if (!empty($resourceId) && array_key_exists($resourceId,$books)) {
               unset( $books [ $resourceId ] );
        }
        echo json_encode($books);
        break;

}


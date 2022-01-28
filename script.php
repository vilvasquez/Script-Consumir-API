<?php
//aqui mostramos en pantalla el API ejecutando el archivo script CON TODOS LOS DATOS
//echo file_get_contents('https://xkcd.com/info.0.json').PHP_EOL;

//aqui no mostramos NO TODOS LOS DATOS , solo quedamos con la data de la imagen   ,util por si queremos solo actualizar 
//todas las imagenes de la api o app.

$json = file_get_contents('https://xkcd.com/info.0.json');

$data = json_decode($json,true);

echo $data['img'].PHP_EOL;


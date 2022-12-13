# Activities-generator IFP
## Entorno servidor Actividad UF4

## Instalaciones necesarias y básicas

- DESCARGAR [WAMP CON PHP 8.1.0 o mas Y MYSQL](https://wampserver.aviatechno.net/files/install/wampserver3.2.6_x64.exe)
- CONFIGURAR [PHP 8.1.0 EN WINDOWS](https://www.netveloper.com/php-variable-de-entorno-en-windows).

IMPORTANTE: REINICIAR EL ORDENADOR

## Si no existe la carpeta ./vendor, seguir indicaciones:
- [Descargar composer](https://getcomposer.org/)
- `composer update`

### Ejecutar el fichero ./database/database.sql en mysql para tener la base de datos y las tablas creadas

## LLAMADAS DE EJEMPLO AL API
### GET
`<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => 'localhost/activities-generator-api/index.php?user_id=1',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
'Accept: application/json'
),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
`

### POST
`<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => 'localhost/activities-generator-api/index.php',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS =>'{
"user_id": 1,
"title": "prueba",
"date": "2022-12-13",
"city": "Madrid",
"type": "cine",
"paymentMethod": "pago",
"description": "Prueba"
}',
CURLOPT_HTTPHEADER => array(
'Accept: application/json',
'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjYwNDk1OTE3LCJleHAiOjE2NjA0OTk1MTcsIm5iZiI6MTY2MDQ5NTkxNywianRpIjoiOUF4Ukczb0dvT0swenhGRiIsInN1YiI6IjMiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.4O2249AM3-fQsUAc4XVElvHLVRPOI2QYgI7WhHGBaaQ',
'Content-Type: application/json'
),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
`

### PUT
`<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => 'localhost/activities-generator-api/index.php?id=2',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'PUT',
CURLOPT_POSTFIELDS =>'{
"title": "aaaaaaaaaaaaa",
"date": "2022-12-13",
"city": "Madrid",
"type": "cine",
"paymentMethod": "pago",
"description": "Prueba" 
}',
CURLOPT_HTTPHEADER => array(
'Accept: application/json',
'Content-Type: application/json'
),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
`

### DELETE
`<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => 'localhost/activities-generator-api/index.php?id=8',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'DELETE',
CURLOPT_POSTFIELDS =>'{"query":"","variables":{}}',
CURLOPT_HTTPHEADER => array(
'Accept: application/json',
'Content-Type: application/json'
),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
`

<?php

use UF1\Controllers\ActivityController;

require_once realpath('vendor/autoload.php');

$request_method = $_SERVER['REQUEST_METHOD'];

$response = match($request_method) {
    'GET' => (new ActivityController())->index(),
    'POST' => (new ActivityController())->create(),
    'PUT' => (new ActivityController())->update(),
    'DELETE' => (new ActivityController())->delete(),
};

echo json_encode($response);
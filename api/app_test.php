<?php


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


http_response_code(200);


echo json_encode(array(
        "data" => 'Data',
        "message" => "Good Job"
    ),);


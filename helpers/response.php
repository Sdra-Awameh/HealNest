<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

header('Content-Type: application/json');

function sendSuccess($message, $data = [], $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "success",
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

function sendError($message, $statusCode = 500, $extra = [])
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(array_merge([
        "status" => "error",
        "message" => $message,
        "timestamp" => date("Y-m-d H:i:s")
    ], $extra));
    exit;
}

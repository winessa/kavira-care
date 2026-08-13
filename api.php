<?php
// Hostinger PHP Real-Time Data Storage API for KAVIRA Care
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$dataFile = __DIR__ . '/data_store.json';

// Initialize data file if not exists
if (!file_exists($dataFile)) {
    $initialData = [
        "complaints" => [],
        "students" => []
    ];
    file_put_contents($dataFile, json_encode($initialData, JSON_PRETTY_PRINT));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $content = file_get_contents($dataFile);
    echo $content ? $content : json_encode(["complaints" => [], "students" => []]);
    exit();
}

if ($method === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data && isset($data['complaints'])) {
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(["status" => "success", "message" => "Data berhasil disimpan di Hostinger"]);
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Format data tidak valid"]);
    }
    exit();
}

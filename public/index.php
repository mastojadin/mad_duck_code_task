<?php
    $body = file_get_contents('php://input') ?: '{}';

    $response = [];
    $response['data'] = [];
    $response['error'] = [];

    require_once __DIR__ . '/../vendor/autoload.php';

    try {
        $start = new App\Core\Start();
        $start->in($body);
        $start->check_request();
        $start->check_headers();
        $start->check_auth();
        $start->set_class_method_names();
        
        header("HTTP/1.1 200 OK");
        $response['data'] = $start->out();
    } catch (\Exception $e) {
        header("HTTP/1.1 400 BadRequest");
        $response['error']['code'] = '';
        $response['error']['message'] = $e->getMessage();
    }

    header("Content-Type: Application/json");
    echo json_encode($response);
    exit;
    
<?php

namespace Providers;

class ApiHeader
{
    public static function setHeaders()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    public static function checkMethod($method)
    {
       if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
    }

    public static function checkAuthorization()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Authorization header missing']);
            exit;
        }
    }

    public static function validateToken($token)
    {
        list($type, $token) = explode(' ', $token, 2);
        if (strcasecmp($type, 'Bearer') !== 0 || empty($token)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid authorization header']);
            exit;
        }
        return $token;
    }
    
}
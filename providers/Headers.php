<?php

namespace Providers;

use Response;

class ApiHeader
{
    public static function setHeaders()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization');
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
        file_put_contents('headers_debug.txt', json_encode(getallheaders(), JSON_PRETTY_PRINT));


        // Normalize header keys to lowercase
        $authHeader = '';
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'authorization') {
                $authHeader = $value;
                break;
            }
        }

        if (empty($authHeader)) {

            Response::json(['success' => false, 'message' => 'Unauthorized'], 401);
            exit;
        }

        // Optionally return token for use
        return $authHeader;
    }


    public static function validateToken($token)
    {
        list($type, $token) = explode(' ', $token, 2);
        if (strcasecmp($type, 'Bearer') !== 0 || empty($token)) {
            Response::json(['success' => false, 'message' => 'Invalid authorization header'], 401);
            exit;
        }
        return $token;
    }
}
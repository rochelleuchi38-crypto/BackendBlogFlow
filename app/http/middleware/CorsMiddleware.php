<?php

namespace App\Http\Middleware;

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CorsMiddleware
{
    /**
     * Handle CORS preflight requests and set CORS headers
     */
    public function __construct()
    {
        // Set CORS headers for all responses
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept, Origin, X-Auth-Token');
        header('Access-Control-Allow-Credentials: true');
        
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Max-Age: 86400');
            header('Content-Length: 0');
            header('Content-Type: text/plain');
            http_response_code(200);
            exit();
        }
    }
}

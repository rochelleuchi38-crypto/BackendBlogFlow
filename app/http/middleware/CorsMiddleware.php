<?php

namespace App\Http\Middleware;

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CorsMiddleware
{
    /**
     * List of allowed origins
     */
    private $allowedOrigins = [
        'http://localhost:5173',
        'https://frontend-blog-flow.vercel.app',
        'https://frontend-blog-flow-ipuz28ix5-rochelleuchis-projects.vercel.app',
        'https://frontend-blog-flow-*.vercel.app' // Wildcard for all preview deployments
    ];

    /**
     * Handle CORS headers
     */
    public function __construct()
    {
        $this->setCorsHeaders();
    }

    /**
     * Set CORS headers and handle preflight requests
     */
    private function setCorsHeaders()
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        
        // Check if origin is allowed or matches wildcard pattern
        if ($this->isOriginAllowed($origin) || $this->isLocalRequest()) {
            header("Access-Control-Allow-Origin: $origin");
            header('Access-Control-Allow-Credentials: true');
        } else {
            header('Access-Control-Allow-Origin: *');
        }
        
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept, Origin, X-Auth-Token');
        
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Max-Age: 86400');
            header('Content-Length: 0');
            header('Content-Type: text/plain');
            http_response_code(200);
            exit(0);
        }
    }

    /**
     * Check if the origin is in the allowed list or matches a wildcard pattern
     */
    private function isOriginAllowed($origin)
    {
        if (in_array($origin, $this->allowedOrigins)) {
            return true;
        }

        foreach ($this->allowedOrigins as $allowedOrigin) {
            if (strpos($allowedOrigin, '*') !== false) {
                $pattern = '/^' . str_replace('\*', '.*', preg_quote($allowedOrigin, '/')) . '$/';
                if (preg_match($pattern, $origin)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if the request is from localhost
     */
    private function isLocalRequest()
    {
        $whitelist = ['127.0.0.1', '::1'];
        return in_array($_SERVER['REMOTE_ADDR'] ?? '', $whitelist);
    }
}

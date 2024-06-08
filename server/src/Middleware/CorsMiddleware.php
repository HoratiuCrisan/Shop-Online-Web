<?php
// src/Middleware/CorsMiddleware.php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface as Middleware;

class CorsMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }
}


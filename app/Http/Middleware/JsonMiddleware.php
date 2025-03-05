<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isJson()) {
            $data = json_decode($request->getContent(), true);
            $request->merge($data);
        }

        return $next($request);
    }
}

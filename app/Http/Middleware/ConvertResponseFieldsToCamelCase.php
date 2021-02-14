<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ConvertResponseFieldsToCamelCase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $content = $response->getContent();

        try {
            $json = json_decode($content, true);
            $replaced = [];
            foreach ($json as $key => $value) {
                $replaced[Str::camel($key)] = $value;
            }
            $response->setContent(json_encode($replaced));
        } catch (Exception $e) {
            Log::debug($e);
        }

        return $response;
    }
}

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

        $decoded = json_decode($content, true);
        $result = static::toCamelCase($decoded);
        
        $response->setContent(json_encode($result));
        return $response;
    }

    /**
     * 配列のキー名をキャメルケースに変換する
     * 多次元配列にも対応可能
     */
    public static function toCamelCase($array)
    {
        $converted = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $converted[Str::camel($key)] = static::toCamelCase($value);    
            } else {
                $converted[Str::camel($key)] = $value;
            }
        }
        return $converted;
    }
}

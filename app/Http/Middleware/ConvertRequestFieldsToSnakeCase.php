<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class ConvertRequestFieldsToSnakeCase
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
        $result = $this->arrayToSnake($request->all());
        $request->replace($result);
        return $next($request);
    }

    /**
     * 配列のキー名をスネークケースに変換する
     * 多次元配列にも対応可能
     */
    private function arrayToSnake($array) {
        $converted = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $converted[Str::snake($key)] = $this->arrayToSnake($value);
            } else {
                $converted[Str::snake($key)] = $value;
            }
        }
        return $converted;
    }
}

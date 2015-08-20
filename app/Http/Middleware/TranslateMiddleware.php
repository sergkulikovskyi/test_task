<?php

namespace App\Http\Middleware;

use Closure;

class TranslateMiddleware
{
    private $errors = [];
    private $available_languages = ['ru', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $word = $request->word;
        $source = $request->source;
        $target = $request->target;

        if (!isset($word)) {
            $this->errors[] = "There is no 'word' param";
        }
        if (!isset($source)) {
            $this->errors[] = "There is no 'source' param";
        }
        if (!isset($target)) {
            $this->errors[] = "There is no 'target' param";
        }
        if (isset($source) && !in_array($source, $this->available_languages)) {
            $this->errors[] = "There is no such 'source' language";
        }
        if (isset($target) && !in_array($target, $this->available_languages)) {
            $this->errors[] = "There is no such 'target' language";
        }

        if (!empty($this->errors)) {
            return response()->json(['errors' => $this->errors], 400);
        }

        return $next($request);
    }
}

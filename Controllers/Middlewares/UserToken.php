<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( PersonalAccessToken::findToken($request->bearerToken())==null){
            return response()->json([
                'message'=>'Unauthorized :('
            ]);
        }
        $tokenname = PersonalAccessToken::findToken($request->bearerToken())->name;
        if($tokenname=='user-token')
        {
            return $next($request);
        }
        
       
    }
}

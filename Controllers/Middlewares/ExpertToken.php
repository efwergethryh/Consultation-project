<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ExpertToken
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
            $response = [
                'status' => 2,
                'message' => 'Unauthorized',
            ];
    
            return response()->json($response, 413);
        }
        $tokenname = PersonalAccessToken::findToken($request->bearerToken())->name;
        //$token = PersonalAccessToken::where('id','LIKE','%' . 16 . '%')->first();
        if($tokenname=='expert_token')
        {
            return $next($request);
        }
        else{
           return response()->json(['msg:'=>'could not proccess request :(']);  
        }
        
       
    }
}

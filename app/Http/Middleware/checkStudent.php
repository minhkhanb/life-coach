<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Closure;

class checkStudent
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
        if(Auth::user()->type == User::TYPE_STUDENT){
            return $next($request);
        }else {
            return redirect()->back()->with('error', 'Bạn không có quyền có thao tác này');
        }
    }

}

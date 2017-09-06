<?php

namespace App\Http\Middleware;

use Route;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class RoleCheck
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        
        
        //待去掉
        //if(request()->user()->role->where('role','adminer')->toArray()){
            return $next($request);
        //}

        $route = Route::currentRouteAction();
        foreach (request()->user()->role as $role) {
            $gate = $role->permission()->where('route',$route)->get()->toArray();
            if($gate){
                return $next($request);
            }
        }
        echo '没有权限';exit;
        
    }
}

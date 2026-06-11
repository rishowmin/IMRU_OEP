<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * @property array $guards
 */

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->guards = $guards; // capture before calling parent

        return parent::handle($request, $next, ...$guards);
    }


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $guardRoutes = [
                'admin'   => 'admin.login',
                'student' => 'academic.login',
                'teacher' => 'academic.login',
            ];

            foreach ($this->guards as $guard) {
                if (isset($guardRoutes[$guard])) {
                    return route($guardRoutes[$guard]);
                }
            }

            return route('login');
        }
    }
}

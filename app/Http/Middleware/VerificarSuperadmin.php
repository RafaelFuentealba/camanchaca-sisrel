<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSuperadmin {
    public function handle(Request $request, Closure $next) {
        if (!$request->session()->has('superadmin')) {
            return redirect()->to('ingresar');
        }
        return $next($request);
    }
}

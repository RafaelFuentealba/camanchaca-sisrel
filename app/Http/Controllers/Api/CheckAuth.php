<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\JWTAuth;
use Ahc\Jwt\JWTException;
use Illuminate\Http\Request;

class CheckAuth extends JWTAuth {

    public function checkToken(String $token) {
        try {
            return $this->validateToken($token);
        } catch (JWTException $e) {
            return false;
        }
    }

    public function protectRoute(Request $request, $role) {
        $token = $request->header("auth-token");

        // Validar que está recibiendo el token
        if (!$token) return response(["message" => "Usted no está autorizado para acceder a esta información."], 401);

        $checkToken = $this->checkToken($token);
        if ($checkToken == false) return response(["message" => "El token de autenticación expiró."], 401);
        if ($checkToken["role"] != $role) return response(["message" => "Usted no tiene permisos para acceder a esta información"], 403); 

        return $checkToken;
    }
}
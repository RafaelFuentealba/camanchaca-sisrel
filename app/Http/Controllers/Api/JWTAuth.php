<?php

namespace App\Http\Controllers\Api;

use Ahc\Jwt\JWT;

class JWTAuth {
    // url => https://github.com/adhocore/php-jwt
    private $jwt;

    function __construct() {
        $key = env("SECRET_KEY_API", "secret_key");

        $this->jwt = new JWT($key, "HS256");
    }

    public function createToken(String $rut, $role) {
        $token = $this->jwt->encode(["run" => $rut, "role" => $role]);
        return $token;
    }

    public function validateToken(String $token) {
        $payload = $this->jwt->decode($token);
        return $payload;
    }
}
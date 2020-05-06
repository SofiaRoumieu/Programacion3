<?php
use Firebase\JWT\JWT;
//include_once 'vendor/firebase/php-jwt/src/JWT.php';

class GestionJWT
{
    public static $claveSecreta = 'pro3-parcial';
    public static $tipoEncriptacion =['HS256'];
    private static $aud = null;
    
    public static function CrearToken($datos)
    {   
        $hora = time();
        $payload = array(
        "iat" => $hora,
        //"exp" => $hora + 300,
        "email" => $datos->email,
        "clave" => $datos->clave
        );
        return JWT::encode($payload, self::$claveSecreta);
    }
    
    public static function ValidarToken($token)
    {
        if(empty($token))
        {
            throw new Exception("El token esta vacio.");
        } 
        try {
            $decodificado = JWT::decode($token,self::$claveSecreta,self::$tipoEncriptacion);
        } 
        catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public static function Obtener($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        );
    }
}

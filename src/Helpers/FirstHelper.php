<?php namespace App\Helpers;

class FirstHelper {
    public static function request(string $method_name): mixed
    {
        $r = new Request;
        return $r->$method_name();
    }

    public static function auth(string $method_name): mixed
    {
        $r = new Auth;
        return $r->$method_name();
    }

    public static function headers(string $method_name): mixed
    {
        $r = new Headers;
        return $r->$method_name();
    }

    public static function general(string $method_name): mixed
    {
        $r = new General;
        return $r->$method_name();
    }
}
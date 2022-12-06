<?php namespace App\Core;

class Config {
    /**
     * getting the stuff from config files
     * 
     * @param string $what
     * 
     * @return mixed
     */
    public static function get_me(string $what): string|array
    {
        $c = new Config;
        return $c->real_get_me($what);
    }

    /**
     * getting the stuff from config files
     * but for real
     * 
     * @param string $what
     * 
     * @return mixed
     */
    private function real_get_me(string $what): string|array
    {
        $dir = __DIR__ . '/../../config/';
        $file = $dir . $what . '.php';
        return is_file($file) ? include($file) : [];
    }
}
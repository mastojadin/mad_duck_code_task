<?php namespace App\Helpers;

class Auth {
    private string $uri;
    
    public function __construct()
    {
        $this->uri = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

    public function is_auth(): void
    {
        if ($this->uri !== '/') {
            FirstHelper::headers('isset_bearer');
        }
    }
}
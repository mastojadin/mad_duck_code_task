<?php namespace App\Helpers;

use App\Core\Config;

class Request {
    private $method;
    private $uri;
    private $my_routes;
    
    public function __construct()
    {
        $this->method = !empty($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
        $tmp_uri = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $this->uri = $this->clean_uri($tmp_uri);
        $this->my_routes = Config::get_me('routes');
    }

    public function check_method(): void
    {
        if (!isset($this->my_routes[$this->method])) {
            throw new \Exception('Http Method Not Regonized');
        }
    }

    public function check_route(): void
    {
        $route = $this->my_routes[$this->method];
        if (!isset($route[$this->uri])) {
            throw new \Exception('Route Not Regonized');
        }
    }

    public function get_class_method_names(): array
    {
        return $this->my_routes[$this->method][$this->uri];
    }

    private function clean_uri(string $tmp_uri): string
    {
        $tmp_string = $tmp_uri;

        $vars = Config::get_me('vars');
        if (strpos($vars['base'], 'http://localhost') === 0) {
            $to_replace = str_replace('http://localhost', '', $vars['base']);
            $tmp_string = str_replace($to_replace, '', $tmp_string);
        }
        if (strpos($vars['base'], 'https://localhost') === 0) {
            $to_replace = str_replace('https://localhost', '', $vars['base']);
            $tmp_string = str_replace($to_replace, '', $tmp_string);
        }

        $tmp_string = str_replace('index.php', '', $tmp_string);

        if ($tmp_string == '') {
            $tmp_string = '/' . $tmp_string;
        }

        $query_string = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
        $tmp_string = str_replace($query_string, '', $tmp_string);

        return $tmp_string;
    }
}